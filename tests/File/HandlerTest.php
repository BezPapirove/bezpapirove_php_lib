<?php
declare(strict_types=1);

namespace Tests\File;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;
use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Exception\OperationErrorException;
use Bezpapirove\BezpapirovePhpLib\File\Handler;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class HandlerTest extends TestCase
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
        $biz_rule = new Handler($this->path);
        $reflection = new \ReflectionClass($biz_rule);
        $this->assertTrue($reflection->hasMethod('upload'));
        $this->assertTrue($reflection->hasMethod('download'));
        $this->assertTrue($reflection->hasMethod('delete'));
        $this->assertTrue($reflection->hasMethod('exists'));
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     * @throws FileNotFoundException
     */
    public function testUpload(): string
    {
        $h = new Handler($this->path);
        $this->f = tempnam($this->path, 't_');
        $result = $h->upload($this->f);
        $this->assertNotFalse($result, 'Returned result is FALSE');
        $this->assertTrue(Uuid::isValid($result), 'Returned result is not valid UUID: ' . $result);
        $fs = FolderStructure::getFolderStructureFromFileName($result);
        $this->assertTrue(is_file($this->path . '/' . implode('/', $fs) . '/' . $result), 'Created file doesnt exists: ' . $result);

        return $result;
    }

    /**
     * @throws OperationErrorException
     * @throws NotValidInputException
     * @throws FileNotFoundException
     */
    #[Depends('testUpload')]
    public function testExists(string $result): void
    {
        $h = new Handler($this->path);
        $result = $h->exists($result);
        $this->assertTrue($result, 'Dont find existing file');
    }
}
