<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File;

use Bezpapirove\BezpapirovePhpLib\File\Storage\FileStorageInterface;
use Bezpapirove\BezpapirovePhpLib\File\Factory\FileStorageFactory;
use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

/**
 * FileHandler is simple file handler for working with files on different backand (local filesystem, AWS S3, ...)
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
final class FileHandler
{
    private static ?FileStorageInterface $sharedStorage = null;

    private FileStorageInterface $storage;

    private Filesystem $fileSystem;

    public function __construct(string $basePath)
    {
        if (self::$sharedStorage === null) {
            self::$sharedStorage = FileStorageFactory::createFromConfig(
                require __DIR__ . '/../../../../config/fileStorage.php',
                $basePath
            );
        }

        $this->storage = self::$sharedStorage;
        $this->fileSystem = new Filesystem();
    }

    /**
     * @param string $filePath accept existing file on filesystem
     *
     * @return Uuid returns UUID name on filesystem
     *
     * @throws NotValidInputException when bad file name provided or file does not exist
     * @throws OperationErrorException
     */
    public function saveFile(string $filePath): Uuid
    {
        if ( ! $this->fileSystem->exists($filePath)) {
            throw new NotValidInputException('File does not exist: ' . $filePath);
        }
        
        try {
            $uuid = Uuid::v4();
            $this->storage->save($filePath, $uuid);
            return $uuid;
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when saving file: ' . $e->getMessage());
        }
        
    }

    /**
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @return Uuid returns UUID name on filesystem
     *
     * @throws FileNotFoundException
     * @throws OperationErrorException
     */
    public function duplicateFile(Uuid $fileName): Uuid
    {
        if ( ! $this->storage->exists($fileName)) {
            throw new FileNotFoundException('File does not exist: ' . $fileName);
        }

        try {
            $new = Uuid::v4();
            $this->storage->duplicate($fileName, $new);
            return $new;
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when duplicating file: ' . $e->getMessage());
        }
    }

    /**
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @throws FileNotFoundException
     */
    public function readFile(Uuid $fileName): string
    {
        if ( ! $this->storage->exists($fileName)) {
            throw new FileNotFoundException('File does not exist: ' . $fileName);
        }
    
        return $this->storage->read($fileName);
    }

    /**
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @throws FileNotFoundException
     * @throws OperationErrorException
     */
    public function deleteFile(Uuid $fileName): bool
    {
        if ( ! $this->storage->exists($fileName)) {
            throw new FileNotFoundException('File does not exist: ' . $fileName);
        }
        try {
            $this->storage->delete($fileName);
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when saving file: ' . $e->getMessage());
        }
        
        return true;
    }

    /**
     * Send file HTTP headers to View or Download in browser
     * 
     * @param Uuid $fileUuid
     * @param string $mode
     * @param mixed $fileName
     * @param mixed $mimeType
     * @throws \LogicException
     * @return void
     */
    public function sendFileHeaders(Uuid $fileUuid, string $mode = 'view', ?string $fileName = null, ?string $mimeType = null): void {
        $mimeType ?: 'application/octet-stream';

        $disposition = match ($mode) {
            'view' => 'inline',
            'download' => 'attachment',
            default => throw new \LogicException('Unknown mode'),
        };

        $fileName ??= (string) $fileUuid;

        header('Content-Length: ' . $this->storage->getFileSize($fileUuid));
        header('Content-Type: ' . $mimeType);
        header(
            'Content-Disposition: ' . $disposition .
            '; filename="' . basename($fileName) . '"; ' .
            "filename*=UTF-8''" . rawurlencode($fileName)
        );
    }
}
