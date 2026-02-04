<?php

final class LocalFileStorage implements FileStorageInterface
{
    public function __construct(
        private string $basePath,
        private Filesystem $filesystem = new Filesystem()
    ) {}

    public function save(string $sourcePath, Uuid $uuid): void
    {
        $target = $this->getPath($uuid);
        FolderStructure::createFolderStructure(
            $this->basePath,
            FolderStructure::getFolderStructureFromFileName($uuid)
        );

        $this->filesystem->rename($sourcePath, $target);
    }

    public function read(Uuid $uuid): string
    {
        return file_get_contents($this->getPath($uuid));
    }

    public function delete(Uuid $uuid): void
    {
        $this->filesystem->remove($this->getPath($uuid));
    }

    public function exists(Uuid $uuid): bool
    {
        return $this->filesystem->exists($this->getPath($uuid));
    }

    public function duplicate(Uuid $source, Uuid $target): void
    {
        $this->filesystem->dumpFile(
            $this->getPath($target),
            file_get_contents($this->getPath($source))
        );
    }

    private function getPath(Uuid $uuid): string
    {
        $folders = FolderStructure::getFolderStructureFromFileName($uuid);
        return $this->basePath . '/' . implode('/', $folders) . '/' . $uuid->toRfc4122();
    }
}
