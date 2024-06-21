<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use PHPUnit\Framework\TestCase;

final class FolderStructureTest extends TestCase
{
    private $path;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir();
    }

    public function testMethods()
    {
        $biz_rule = new FolderStructure();
        $reflection = new \ReflectionClass($biz_rule);
        $this->assertTrue($reflection->hasMethod('getFolderStructureFromFileName'));
        $this->assertTrue($reflection->hasMethod('pathExists'));
        $this->assertTrue($reflection->hasMethod('createFolderStructure'));
    }

    public function testGetFolderStructureFromFileNameValid()
    {
        $result = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertTrue(is_array($result), 'Bad result provided');
    }

    /**
     * @expectedException Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException
     */
    public function testException()
    {
        $this->expectException("Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException");
        FolderStructure::getFolderStructureFromFileName('bad_Filename');
    }

    public function testGetFolderStructureFromFileNameResult()
    {
        $result = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertSame($result, ['c7', 'fd', '97']);
    }

    public function testPathExists()
    {
        $this->assertIsString($this->path);
        $path = FolderStructure::getFolderStructureFromFileName('c7fd97ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertFalse(FolderStructure::pathExists($this->path, $path));
    }

    public function testCreateFolderStructure()
    {
        $this->assertIsString($this->path);
        $path = FolderStructure::getFolderStructureFromFileName('a71d07ae-b67c-4468-a32c-93c613e3a46f');
        $this->assertTrue(FolderStructure::createFolderStructure($this->path, $path));
    }
}
