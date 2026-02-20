# TYPO3 Extension `sfdbutf8`

| :warning: WARNING                                 |
|:--------------------------------------------------|
| Backup your database before running a conversion! |

![Build Status](https://github.com/froemken/sfdbutf8/workflows/CI/badge.svg)

With sfdbutf8 you can change the collation of tables and columns of
TYPO3s default database connection.

It can only change the collation from utf8_* to a different or same
utf8_* collation. It's not possible to change from latin* to utf8_*.

Keep in mind that no conversion of the content itself will happen!

## 2 Usage

### Installation from fork

If you want to install this fork from GitHub, run:

```bash
composer config repositories.sfdbutf8 vcs https://github.com/akiessling/sfdbutf8.git
composer require stefanfroemken/sfdbutf8:dev-main
```

### 2.2 Minimal setup

1) Visit BE module of sfdbutf8
2) Analyze tables collations
3) Choose a collation you want to use
4) Click "convert"
