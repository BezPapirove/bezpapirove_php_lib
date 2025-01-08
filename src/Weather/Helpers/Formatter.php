<?php
/**
 * Description of Formatter
 *
 * description
 * 08.01.2025
 *
 * @author Bc. Tomáš Otruba <tomas@bezpapirove.cz>
 *
 * @link http://www.bezpapirove.cz/
 *
 * @version 1.0.0
 */

declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Weather\Helpers;

class Formatter
{

    public static function getWindDirectionFromDegrees(int $meteorological_direction) : string
    {
        $result = '';
        if ($meteorological_direction >= 0 && $meteorological_direction <= 23) {
            $result = 'S';
        }
        if ($meteorological_direction >= 23 && $meteorological_direction < 68) {
            $result = 'SV';
        }
        if ($meteorological_direction >= 68 && $meteorological_direction < 113) {
            $result = 'V';
        }
        if ($meteorological_direction >= 113 && $meteorological_direction < 158) {
            $result = 'JV';
        }
        if ($meteorological_direction >= 158 && $meteorological_direction < 203) {
            $result = 'J';
        }
        if ($meteorological_direction >= 203 && $meteorological_direction < 248) {
            $result = 'JZ';
        }
        if ($meteorological_direction >= 248 && $meteorological_direction < 293) {
            $result = 'Z';
        }
        if ($meteorological_direction >= 293 && $meteorological_direction < 337) {
            $result = 'SZ';
        }
        if ($meteorological_direction >= 337 && $meteorological_direction <= 360) {
            $result = 'S';
        }
        return $result;
    }

}
