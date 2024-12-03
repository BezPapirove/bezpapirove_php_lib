<?php

declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Helpers;

/**
 * FolderStructure
 *
 * Helper for format strings and numbers
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
final class StringHelper
{

    const SIZES_METRICS = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    /**
     * getNormalizedFileSize
     * 
     * returns normalized file size 
     *
     * @param int $bytes original file size in bytes
     *
     * @return string returns normalized file size with metrics
     */
    public static function getNormalizedFileSize(int $bytes, int $dec = 2): string
    {
        $factor = floor((strlen(strval($bytes)) - 1) / 3);

        return sprintf("%.{$dec}f %s", ($bytes / (1024 ** $factor)), self::SIZES_METRICS[$factor]);
    }
}
