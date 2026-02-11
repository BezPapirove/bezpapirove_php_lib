<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File\Factory;

use Bezpapirove\BezpapirovePhpLib\File\Storage\FileStorageInterface;
use Bezpapirove\BezpapirovePhpLib\File\Storage\LocalFileStorage;
use Bezpapirove\BezpapirovePhpLib\File\Storage\S3FileStorage;

final class FileStorageFactory
{
    /** @param array<string, mixed> $config */
    public static function createFromConfig(array $config): FileStorageInterface
    {
        return match ($config['driver']) {
            'local' => new LocalFileStorage($config['local']['basepath']),

            's3' => new S3FileStorage(
                $config['s3']['client'],
                $config['s3']['bucket'],
                $config['s3']['basepath'],
            ),

            default => throw new \LogicException('Unknown storage driver'),
        };
    }
}
