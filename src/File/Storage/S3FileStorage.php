<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File\Storage;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;

use Symfony\Component\Uid\Uuid;

final class S3FileStorage implements FileStorageInterface
{
    public function __construct(
        private S3Client $client,
        private string $bucket,
        private string $basePath = ''
    ) {
    }

    public function save(string $sourcePath, Uuid $uuid): void
    {
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $this->getKey($uuid),
            'Body'   => fopen($sourcePath, 'rb'),
        ]);
    }

    public function read(Uuid $uuid): string
    {
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $this->getKey($uuid),
        ]);

        return (string)$result['Body'];
    }

    public function delete(Uuid $uuid): void
    {
        $this->client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $this->getKey($uuid),
        ]);
    }

    public function exists(Uuid $uuid): bool
    {
        return $this->client->doesObjectExist(
            $this->bucket,
            $this->getKey($uuid)
        );
    }

    public function duplicate(Uuid $source, Uuid $target): void
    {
        $this->client->copyObject([
            'Bucket'     => $this->bucket,
            'CopySource' => "{$this->bucket}/{$this->getKey($source)}",
            'Key'        => $this->getKey($target),
        ]);
    }

    public function getFileSize(Uuid $uuid): int
    {
        try {
            $result = $this->client->headObject([
                'Bucket' => $this->bucket,
                'Key'    => $this->getKey($uuid),
            ]);

            return (int)$result['ContentLength'];
        } catch (AwsException $e) {
            throw new FileNotFoundException(
                'File does not exist or is not accessible on S3: ' . $uuid
            );
        }
    }

    private function getKey(Uuid $uuid): string
    {
        $folders = FolderStructure::getFolderStructureFromFileName($uuid);
        return trim($this->basePath . '/' . implode('/', $folders) . '/' . $uuid->toRfc4122(), '/');
    }
}
