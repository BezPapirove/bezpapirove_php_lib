<?php
declare(strict_types=1);

namespace Tests\File;

use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;
use Bezpapirove\BezpapirovePhpLib\File\FileHandler;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class FileHandlerTest extends TestCase
{
    private string $basePath;
    private ?string $tempFile = null;

    protected function setUp(): void
    {
        $this->basePath = sys_get_temp_dir() . '/filehandler_test';

        if ( ! is_dir($this->basePath)) {
            mkdir($this->basePath, recursive: true);
        }
    }

    protected function tearDown(): void
    {
        if ($this->tempFile && is_file($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
        
    public function testMethods(): void
    {
        $handler = new FileHandler($this->getLocalConfig());
        $reflection = new \ReflectionClass($handler);

        $this->assertTrue($reflection->hasMethod('saveFile'));
        $this->assertTrue($reflection->hasMethod('duplicateFile'));
        $this->assertTrue($reflection->hasMethod('readFile'));
        $this->assertTrue($reflection->hasMethod('deleteFile'));
        $this->assertTrue($reflection->hasMethod('fileExists'));
        $this->assertTrue($reflection->hasMethod('sendFileHeaders'));
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     */
    public function testSaveFile(): Uuid
    {
        $handler = new FileHandler($this->getLocalConfig());

        $this->tempFile = tempnam(sys_get_temp_dir(), 'fh_');
        file_put_contents($this->tempFile, 'test content');

        $uuid = $handler->saveFile($this->tempFile);

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertTrue($handler->fileExists($uuid));

        return $uuid;
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     */
    public function testDuplicateFile(): Uuid
    {
        $handler = new FileHandler($this->getLocalConfig());

        $this->tempFile = tempnam(sys_get_temp_dir(), 'fh_');
        file_put_contents($this->tempFile, 'duplicate test');

        $original = $handler->saveFile($this->tempFile);
        $copy = $handler->duplicateFile($original);

        $this->assertTrue($handler->fileExists($original));
        $this->assertTrue($handler->fileExists($copy));

        return $copy;
    }

    #[Depends('testSaveFile')]
    public function testFileExists(Uuid $uuid): void
    {
        $handler = new FileHandler($this->getLocalConfig());

        $this->assertTrue($handler->fileExists($uuid));
    }

    #[Depends('testSaveFile')]
    public function testDeleteFile(Uuid $uuid): void
    {
        $handler = new FileHandler($this->getLocalConfig());

        $handler->deleteFile($uuid);

        $this->assertFalse($handler->fileExists($uuid));
    }

    /** @return array<string, mixed> */
    private function getLocalConfig(): array
    {
        return [
            'driver' => 'local',
            'local' => [
                'basepath' => $this->basePath,
            ],
        ];
    }
}
