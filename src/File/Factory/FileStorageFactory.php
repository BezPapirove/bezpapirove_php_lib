<?php

namespace Bezpapirove\BezpapirovePhpLib\File\Factory;

use Bezpapirove\BezpapirovePhpLib\File\Storage\FileStorageInterface;
use Bezpapirove\BezpapirovePhpLib\File\Storage\LocalFileStorage;
use Bezpapirove\BezpapirovePhpLib\File\Storage\S3FileStorage;

final class FileStorageFactory
{
    public static function createFromConfig(array $config, string $basePath): FileStorageInterface
    {
        return match ($config['driver']) {
            'local' => new LocalFileStorage($basePath),

            's3' => new S3FileStorage(
                $config['s3']['client'],
                $config['s3']['bucket'],
                $basePath
            ),

            default => throw new \LogicException('Unknown storage driver'),
        };
    }
}
