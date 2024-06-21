# BezPapirove PHP Library

## CI/CD ![build main branch](https://github.com/BezPapirove/bezpapirove_php_lib/actions/workflows/php.yml/badge.svg?branch=main)

> Common library for **BezPapirove s.r.o.** which provide classes useable in PHP projects.  

> Library is fully covered by unit tests.


### Info
- [PHP 8.0](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download)
- [Packagist](https://packagist.org/packages/bezpapirove/bezpapirove_php_lib)

### List of some important functions 

#### - file handler

resolve correct file path in storage

```php
FolderStructure::getFolderStructureFromFileName($filename) : array
FolderStructure::pathExists(string $base_path, array $path_list) : bool
FolderStructure::createFolderStructure(string $base_path, array $path_list) : bool
```

#### - handling file in data storage

```php
use \Bezpapirove\BezpapirovePhpLib\File\Handler;

$handler = new Handler(string $base_path);

$handler->download(string $file_name);
$handler->upload(string $file_path) : string
$handler->exists(string $file_name) : bool
```

#### - generate file UUID name

#### - db connector
