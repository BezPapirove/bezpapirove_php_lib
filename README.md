# BezPapirove PHP Library

### !!! Version 2.0.x is not compatible with 1.x.x !!!


## CI/CD ![build main branch](https://github.com/BezPapirove/bezpapirove_php_lib/actions/workflows/php.yml/badge.svg?branch=main)

> Common library for **BezPapirove s.r.o.** which provide classes useable in PHP projects.  

> Library is fully covered by unit tests.

### Info
- [PHP 8.0](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download)
- [Packagist](https://packagist.org/packages/bezpapirove/bezpapirove_php_lib)

# v2.x.x

#### - file handler

resolve correct file path in storage

```php
FileStorageFactory::createFromConfig(array $config)

FileStorageInterface->save(string $sourcePath, Uuid $uuid): void;
FileStorageInterface->read(Uuid $uuid): string;
FileStorageInterface->delete(Uuid $uuid): void;
FileStorageInterface->exists(Uuid $uuid): bool;
FileStorageInterface->duplicate(Uuid $source, Uuid $target): void;
FileStorageInterface->getFileSize(Uuid $uuid): int;

```


# v1.x.x
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
```

#### - generate file UUID name

#### - db connector
