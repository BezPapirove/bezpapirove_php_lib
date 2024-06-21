<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use Ramsey\Uuid\Uuid;

/**
 * Handler is simple file handler for working with files on filesystem
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
class Handler
{
    protected string $base_path;

    /**
     * @throws FileNotFoundException
     * @throws NotValidInputException
     */
    public function __construct(string $base_path)
    {
        $this->base_path = $base_path;
        if (is_dir($this->base_path) === false) {
            throw new FileNotFoundException('Base folder does not exists: ' . $this->base_path);
        }
        if (is_writable($this->base_path) === false) {
            throw new NotValidInputException('Base folder is not writeable for application: ' . $this->base_path);
        }
    }

    /**
     * upload
     *
     * @param string $file_path accept existing file on filesystem
     *
     * @return string returns UUID name on filesystem
     *
     * @throws NotValidInputException when bad file name provided or file does not exists
     * @throws OperationErrorException
     */
    public function upload(string $file_path): string
    {
        if (is_file($file_path) === false) {
            throw new NotValidInputException('File does not exist: ' . $file_path);
        }
        try {
            $fi = Uuid::uuid4()->toString();
            $fs = FolderStructure::getFolderStructureFromFileName($fi);
            if (FolderStructure::createFolderStructure($this->base_path, $fs)) {
                rename($file_path, $this->base_path . '/' . implode('/', $fs) . '/' . $fi);
            } else {
                throw new \Exception('Failed to created folder strucure');
            }
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures when uploading file: ' . $e->getMessage());
        }
        return $fi;
    }

    /**
     * download
     *
     * @param string $file_name accept UUID v4 file name
     */
    public function download(string $file_name): bool
    {
        return false;
    }

    /**
     * delete
     *
     * @param string $file_name accept UUID v4 file name
     */
    public function delete(string $file_name): bool
    {
        return false;
    }

    /**
     * exists
     *
     * @param string $file_name accept UUID v4 file name
     *
     * @return bool returns true when file is on filesystem stored
     *
     * @throws NotValidInputException when bad file name provided or file doesnt exists
     * @throws OperationErrorException
     */
    public function exists(string $file_name): bool
    {
        if (Uuid::isValid($file_name) === false) {
            throw new NotValidInputException('Input is not valid UUID v4: ' . $file_name);
        }
        try {
            $fs = FolderStructure::getFolderStructureFromFileName($file_name);
            return is_file($this->base_path . '/' . implode('/', $fs) . '/' . $file_name);
        } catch (\Exception $e) {
            throw new OperationErrorException('Error occures finding file: ' . $e->getMessage());
        }
    }

}
