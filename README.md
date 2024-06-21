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
FolderStructure::getFolderStructureFromFileName(Uuid $fileName, int $levels = 3) : array
FolderStructure::pathExists(string $basePath, array $pathList) : bool
FolderStructure::createFolderStructure(string $basePath, array $pathList) : bool
```

#### - handling file in data storage

```php
use \Bezpapirove\BezpapirovePhpLib\File\FileHandler;

$handler = new FileHandler(string $basePath);

$handler->saveFile(string $filePath) : Uuid
$handler->readFile(Uuid $fileName) : string
$handler->fileExists(Uuid $fileName) : bool
$handler->getFilePath(Uuid $fileName) : string
```

#### - generate file UUID name

#### - db connector
