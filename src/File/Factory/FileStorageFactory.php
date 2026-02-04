<?php

final class FileStorageFactory
{
    public static function createFromConfig(array $config, ?string $legacyBasePath): FileStorageInterface
    {
        return match ($config['driver']) {
            'local' => new LocalFileStorage(
                $legacyBasePath ?? $config['local']['base_path']
            ),
            's3' => new S3FileStorage(
                $config['s3']['client'],
                $config['s3']['bucket'],
                $config['s3']['base_path']
            ),
            default => throw new \LogicException('Unknown storage driver'),
        };
    }
}
