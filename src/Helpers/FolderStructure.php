<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Helpers;

use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * FolderStructure
 *
 * Helper for working with folder structure
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
class FolderStructure
{
    /**
     * getFolderStructureFromFileName
     *
     * @param string $file_name accept UUID v4 file name
     *
     * @return array returns folder list in array
     *
     * @throws throws \NotValidInputException when bad file name provided
     */
    public static function getFolderStructureFromFileName(string $file_name, int $levels = 3): array
    {
        $folder_structure = [];

        if (Uuid::isValid($file_name) === false) {
            throw new NotValidInputException('Not valid file name: ' . $file_name);
        }

        for ($i = 0; $i < $levels; $i++) {
            $folder_structure[] = mb_substr($file_name, ($i * 2), 2);
        }

        return $folder_structure;
    }

    /**
     * pathExists
     *
     * @param string $base_path
     * @param array $path_list
     *
     * @return bool returns true when path list exists in folder structure
     *
     * @throws throws \NotValidInputException when bad file name provided
     */
    public static function pathExists(string $base_path, array $path_list): bool
    {
        $folder = $base_path;
        if (is_dir($folder) === false) {
            return false;
        }
        foreach ($path_list as $sub) {
            $folder .= '/' . $sub;
            if (is_dir($folder) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * createFolderStructure
     *
     * @param string $base_path
     * @param array $path_list
     *
     * @return bool returns true when path list exists in folder structure
     *
     * @throws throws \NotValidInputException when bad file name provided
     */
    public static function createFolderStructure(string $base_path, array $path_list): bool
    {
        $folder = $base_path;
        if (is_dir($folder) === false) {
            throw new NotValidInputException('Base path not exists: ' . $base_path);
        }
        if (is_writable($folder) === false) {
            throw new NotValidInputException('Base path is not writeable for app: ' . $base_path);
        }
        foreach ($path_list as $sub) {
            $folder .= '/' . $sub;
        }
        if (is_dir($folder) === false) {
            if (mkdir($folder, 0777, true) === false) {
                throw new Exception('Can not create directory: ' . $folder);
            }
        }
        return true;
    }

}
