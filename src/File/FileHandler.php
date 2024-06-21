<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

/**
 * FileHandler is simple file handler for working with files on filesystem
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
final class FileHandler
{
    private string $basePath;
    private Filesystem $fileSystem;

    /**
     * @throws FileNotFoundException
     * @throws NotValidInputException
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->fileSystem = new Filesystem();
        if ( ! is_dir($this->basePath)) {
            throw new FileNotFoundException('Base folder does not exists: ' . $this->basePath);
        }
        if ( ! is_writable($this->basePath)) {
            throw new NotValidInputException('Base folder is not writeable for application: ' . $this->basePath);
        }
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
            $fileUuid = Uuid::v4();
            $folderStructure = FolderStructure::getFolderStructureFromFileName($fileUuid);
            if (FolderStructure::createFolderStructure($this->basePath, $folderStructure)) {
                $this->fileSystem->rename($filePath, $this->getFilePath($fileUuid));
            }
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when saving file: ' . $e->getMessage());
        }

        return $fileUuid;
    }

    /**
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @throws FileNotFoundException
     */
    public function readFile(Uuid $fileName): string
    {
        if ( ! $this->fileExists($fileName)) {
            throw new FileNotFoundException('File does not exist: ' . $fileName);
        }

        return $this->fileSystem->readFile($this->getFilePath($fileName));
    }

    /**
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @throws FileNotFoundException
     * @throws OperationErrorException
     */
    public function deleteFile(Uuid $fileName): bool
    {
        if ( ! $this->fileExists($fileName)) {
            throw new FileNotFoundException('File does not exist: ' . $fileName);
        }

        try {
            $this->fileSystem->remove($this->getFilePath($fileName));
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when saving file: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * exists
     *
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @return bool returns true when file is on filesystem stored
     */
    public function fileExists(Uuid $fileName): bool
    {
        return $this->fileSystem->exists($this->getFilePath($fileName));
    }

    public function getFilePath(Uuid $fileName): string
    {
        $folderStructure = FolderStructure::getFolderStructureFromFileName($fileName);

        return $this->basePath . '/' . implode('/', $folderStructure) . '/' . $fileName->toRfc4122();
    }

}
