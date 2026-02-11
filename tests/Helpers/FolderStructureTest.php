<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Exception\NotValidInputException;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

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

    public function testGetFolderStructureFromFileNameValid(): void
    {
        $uuid = Uuid::v4();
        $folderStructure = FolderStructure::getFolderStructureFromFileName($uuid);

        $this->assertIsArray($folderStructure, 'Bad result provided');
    }

    public function testGetFolderStructureFromFileNameResultDefault(): void
    {
        $result = FolderStructure::getFolderStructureFromFileName(Uuid::fromString('c7fd97ae-b67c-4468-a32c-93c613e3a46f'));
        $this->assertSame($result, ['c7', 'fd', '97']);
        $this->assertCount(3, $result, 'Bad result provided');
    }

    public function testGetFolderStructureFromFileNameResult(): void
    {
        $result = FolderStructure::getFolderStructureFromFileName(Uuid::fromString('c7fd97ae-b67c-4468-a32c-93c613e3a46f'), 4);
        $this->assertSame($result, ['c7', 'fd', '97', 'ae']);
        $this->assertCount(4, $result, 'Bad result provided');
    }

    #[Depends('testCreateFolderStructure')]
    public function testPathExists(Uuid $result): void
    {
        $this->assertIsString($this->path);
        $path = FolderStructure::getFolderStructureFromFileName($result);
        $this->assertTrue(FolderStructure::pathExists($this->path, $path));
    }

    /**
     * @throws NotValidInputException
     */
    public function testCreateFolderStructure(): Uuid
    {
        $this->assertIsString($this->path);
        $uuid = Uuid::v4();
        $path = FolderStructure::getFolderStructureFromFileName($uuid);
        $this->assertTrue(FolderStructure::createFolderStructure($this->path, $path));

        return $uuid;
    }
}
