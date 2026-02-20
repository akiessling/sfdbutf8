<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/sfdbutf8.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Sfdbutf8\Controller;

use StefanFroemken\Sfdbutf8\Converter\CollationConverter;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Controller to alter collation of database tables and columns
 */
class Utf8Controller extends ActionController
{
    public function __construct(private \TYPO3\CMS\Backend\Template\ModuleTemplateFactory $moduleTemplateFactory)
    {
    }

    protected function initializeView(): void
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
//        $buttonBar = $moduleTemplate
//            ->getDocHeaderComponent()
//            ->getButtonBar();
//
//        // Bookmark
//        $shortcutButton = $buttonBar->makeShortcutButton()
//            ->setModuleName('tools_sfdbutf8')
//            ->setGetVariables(['route', 'module', 'id'])
//            ->setDisplayName('SF DB UTF-8 Module');
//        $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    public function showAction(?string $collation = null): \Psr\Http\Message\ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $collations = [];
        /** @var \TYPO3\CMS\Core\Database\Connection $connection */
        $connection = $this->getConnectionPool()->getConnectionByName('Default');
        $statement = $connection->executeQuery('SHOW COLLATION WHERE Charset like "utf8%"');
        while ($row = $statement->fetchAssociative()) {
            $collations[$row['Collation']] = $row['Collation'];
        }
        $selectedCollation = $collation ?? 'utf8_general_ci';
        $moduleTemplate->assignMultiple([
            'collations' => $collations,
            'selectedCollation' => $selectedCollation,
        ]);
        return $moduleTemplate->renderResponse('Utf8/Show');
    }

    public function dbCheckAction(string $collation): \Psr\Http\Message\ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // show all tables with additional settings
        $connection = $this->getConnectionPool()->getConnectionByName('Default');
        $tableStatement = $connection->executeQuery('SHOW TABLE STATUS');

        $tables = [];
        while ($table = $tableStatement->fetchAssociative()) {
            $columnStatement = $connection->executeQuery('SHOW FULL COLUMNS FROM ' . $table['Name'] . ' WHERE Collation <> \'\'');
            while ($column = $columnStatement->fetchAssociative()) {
                $column['isBinaryCollation'] = is_string($column['Collation'] ?? null) && str_ends_with(strtolower($column['Collation']), '_bin');
                $table['columns'][] = $column;
            }
            $tables[] = $table;
        }

        $moduleTemplate->assignMultiple([
            'collation' => $collation,
            'tables' => $tables,
        ]);
        return $moduleTemplate->renderResponse('Utf8/DbCheck');
    }

    public function convertAction(string $collation): \Psr\Http\Message\ResponseInterface
    {
        $collationConverter = $this->getCollationConverter();
        $collationConverter->convert($collation);

        $this->addFlashMessage(
            LocalizationUtility::translate('messageChangeSuccessful.description', 'sfdbutf8', [$collation]),
            LocalizationUtility::translate('messageChangeSuccessful.title', 'sfdbutf8', [$collation])
        );

        return $this->redirect(
            'show',
            null,
            null,
            ['collation' => $collation]
        );
    }

    protected function getCollationConverter(): CollationConverter
    {
        return GeneralUtility::makeInstance(CollationConverter::class);
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
