<?php

namespace Tests\File;

use Bezpapirove\BezpapirovePhpLib\File\Handler;
use PHPUnit\Framework\TestCase;

final class HandlerTest extends TestCase
{

    private $path;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir();
    }

    public function testMethods()
    {
        $biz_rule = new Handler($this->path);
        $reflection = new \ReflectionClass($biz_rule);
        $this->assertTrue($reflection->hasMethod('download'));
        $this->assertTrue($reflection->hasMethod('upload'));
        $this->assertTrue($reflection->hasMethod('exists'));
    }

    
}
