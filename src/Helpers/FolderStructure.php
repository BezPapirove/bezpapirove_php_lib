<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Helpers;

use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Symfony\Component\Uid\Uuid;

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
final class FolderStructure
{
    /**
     * getFolderStructureFromFileName
     *
     * @param Uuid $fileName accept UUID v4 file name
     *
     * @return array|string[] returns folder list in array
     */
    public static function getFolderStructureFromFileName(Uuid $fileName, int $levels = 3): array
    {
        $folder_structure = [];

        for ($i = 0; $i < $levels; $i++) {
            $folder_structure[] = mb_substr($fileName->toRfc4122(), ($i * 2), 2);
        }

        return $folder_structure;
    }

    /**
     * pathExists
     *
     * @param string $basePath
     * @param array|string[] $pathList
     *
     * @return bool returns true when path list exists in folder structure
     */
    public static function pathExists(string $basePath, array $pathList): bool
    {
        $folder = $basePath;
        if (is_dir($folder) === false) {
            return false;
        }
        foreach ($pathList as $sub) {
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
     * @param string $basePath
     * @param array|string[] $pathList
     *
     * @return bool returns true when path list exists in folder structure
     *
     * @throws NotValidInputException when bad file name provided
     * @throws \RuntimeException
     */
    public static function createFolderStructure(string $basePath, array $pathList): bool
    {
        $folder = $basePath;
        if (is_dir($folder) === false) {
            throw new NotValidInputException('Base path not exists: ' . $basePath);
        }
        if (is_writable($folder) === false) {
            throw new NotValidInputException('Base path is not writeable for app: ' . $basePath);
        }
        foreach ($pathList as $sub) {
            $folder .= '/' . $sub;
        }
        if (is_dir($folder) === false) {
            if ( ! mkdir($folder, 0777, true) && ! is_dir($folder)) {
                throw new \RuntimeException('Can not create directory: ' . $folder);
            }
        }
        return true;
    }

}
