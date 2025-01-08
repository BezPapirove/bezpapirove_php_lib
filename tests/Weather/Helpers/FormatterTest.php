<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Weather\Helpers\Formatter;
use PHPUnit\Framework\TestCase;

final class FormatterTest extends TestCase
{

    public function testGetWindDirectionFromDegrees(): void
    {
        $this->assertSame(Formatter::getWindDirectionFromDegrees(5), 'S');
        $this->assertSame(Formatter::getWindDirectionFromDegrees(300), 'SZ');
    }

}
