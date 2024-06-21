<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use PHPUnit\Framework\TestCase;

final class FolderStructureTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir();
    }

    public function testMethods(): void
    {
        $biz_rule = new FolderStructure();
        $reflection = new \ReflectionClass($biz_rule);
        $this->assertTrue($reflection->hasMethod('getFolderStructureFromFileName'));
        $this->assertTrue($reflection->hasMethod('pathExists'));
        $this->assertTrue($reflection->hasMethod('createFolderStructure'));
    }

    /**
     * @throws NotValidInputException
     */
    public function testGetFolderStructureFromFileNameValid(): void
    {
        $result = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertTrue(is_array($result), 'Bad result provided');
    }

    public function testException(): void
    {
        $this->expectException(NotValidInputException::class);
        FolderStructure::getFolderStructureFromFileName('bad_Filename');
    }

    /**
     * @throws NotValidInputException
     */
    public function testGetFolderStructureFromFileNameResult(): void
    {
        $result = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertSame($result, ['c7', 'fd', '97']);
    }

    /**
     * @throws NotValidInputException
     */
    public function testPathExists(): void
    {
        $this->assertIsString($this->path);
        $path = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertFalse(FolderStructure::pathExists($this->path, $path));
    }

    /**
     * @throws NotValidInputException
     */
    public function testCreateFolderStructure(): void
    {
        $this->assertIsString($this->path);
        $path = FolderStructure::getFolderStructureFromFileName('a71d07ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertTrue(FolderStructure::createFolderStructure($this->path, $path));
    }
}
