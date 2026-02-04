<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\File\Storage;

use Symfony\Component\Uid\Uuid;

interface FileStorageInterface
{
    public function save(string $sourcePath, Uuid $uuid): void;

    public function read(Uuid $uuid): string;

    public function delete(Uuid $uuid): void;

    public function exists(Uuid $uuid): bool;

    public function duplicate(Uuid $source, Uuid $target): void;
}
