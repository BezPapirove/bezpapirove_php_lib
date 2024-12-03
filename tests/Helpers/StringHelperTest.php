<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

final class StringHelperTest extends TestCase
{

    public function testGetNormalizedFileSize(): void
    {
        $this->assertSame(StringHelper::getNormalizedFileSize(1024, 0), '1 kB');
        $this->assertSame(StringHelper::getNormalizedFileSize(1024, 2), '1.00 kB');
        $this->assertSame(StringHelper::getNormalizedFileSize(18937, 2), '18.49 kB');
        $this->assertSame(StringHelper::getNormalizedFileSize(18937, 0), '18 kB');
    }

}
