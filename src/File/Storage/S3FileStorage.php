<?php

final class S3FileStorage implements FileStorageInterface
{
    public function __construct(
        private S3Client $client,
        private string $bucket,
        private string $basePath = ''
    ) {}

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

        return (string) $result['Body'];
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

    private function getKey(Uuid $uuid): string
    {
        $folders = FolderStructure::getFolderStructureFromFileName($uuid);
        return trim($this->basePath . '/' . implode('/', $folders) . '/' . $uuid->toRfc4122(), '/');
    }
}
