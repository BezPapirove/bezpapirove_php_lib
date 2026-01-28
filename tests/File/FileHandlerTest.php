<?php
declare(strict_types=1);

namespace Tests\File;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;
use Bezpapirove\BezpapirovePhpLib\File\FileHandler;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class FileHandlerTest extends TestCase
{
    private string $path;
    private string|null $f = null;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir();
    }

    protected function tearDown(): void
    {
        if (empty($this->f) === false && is_file($this->f)) {
            unlink($this->f);
        }
    }

    /**
     * @throws FileNotFoundException
     * @throws NotValidInputException
     */
    public function testMethods(): void
    {
        $biz_rule = new FileHandler($this->path);
        $reflection = new \ReflectionClass($biz_rule);
        $this->assertTrue($reflection->hasMethod('saveFile'));
        $this->assertTrue($reflection->hasMethod('duplicateFile'));
        $this->assertTrue($reflection->hasMethod('readFile'));
        $this->assertTrue($reflection->hasMethod('deleteFile'));
        $this->assertTrue($reflection->hasMethod('fileExists'));
        $this->assertTrue($reflection->hasMethod('getFilePath'));
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     * @throws FileNotFoundException
     */
    public function testSaveFile(): Uuid
    {
        $h = new FileHandler($this->path);
        $this->f = tempnam($this->path, 't_');
        $result = $h->saveFile($this->f);
        $this->assertNotFalse($result, 'Returned result is FALSE');
        $this->assertTrue(is_file($h->getFilePath($result)), 'Created file doesnt exists: ' . $result);

        return $result;
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     * @throws FileNotFoundException
     */
    public function testDuplicateFile(): Uuid
    {
        $h = new FileHandler($this->path);
        $this->f = tempnam($this->path, 't_');
        $uuid = $h->saveFile($this->f);
        $result = $h->duplicateFile($uuid);
        $this->assertNotFalse($result, 'Returned result is FALSE');
        $this->assertTrue(is_file($h->getFilePath($result)), 'Created file doesnt exists: ' . $result);

        return $result;
    }

    /**
     * @throws NotValidInputException
     * @throws FileNotFoundException
     */
    #[Depends('testSaveFile')]
    public function testFileExists(Uuid $result): Uuid
    {
        $fileHandler = new FileHandler($this->path);
        $fileExists = $fileHandler->fileExists($result);
        $this->assertTrue($fileExists, 'Dont find existing file');

        return $result;
    }

    /**
     * @throws FileNotFoundException
     * @throws NotValidInputException
     * @throws OperationErrorException
     */
    #[Depends('testFileExists')]
    public function testDeleteFile(Uuid $result): void
    {
        $fileHandler = new FileHandler($this->path);
        $fileHandler->deleteFile($result);
        $fileExists = $fileHandler->fileExists($result);
        $this->assertFalse($fileExists, 'File has not been deleted');
    }
}
