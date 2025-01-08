<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Bezpapirove\BezpapirovePhpLib\Weather\Weather;
use PHPUnit\Framework\TestCase;

final class WeatherTest extends TestCase
{

    public function testMethods(): void
    {
        $weather = new Weather('https://api.openweathermap.org/data/2.5/weather', 'c589d9c4c028fee733be0a5e2d50fb25');
        $reflection = new \ReflectionClass($weather);
        $this->assertTrue($reflection->hasMethod('get'), 'Missing "get" method');
        $this->assertTrue($reflection->hasMethod('getIcon'), 'Missing "getIcon" method');
        $this->assertTrue($reflection->hasMethod('windDirection'), 'Missing "windDirection" method');
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf('Bezpapirove\BezpapirovePhpLib\Weather\Weather', new Weather('https://api.openweathermap.org/data/2.5/weather', 'c589d9c4c028fee733be0a5e2d50fb25'));
    }

    public function testGet(): void
    {
        $weather = new Weather('https://api.openweathermap.org/data/2.5/weather', 'c589d9c4c028fee733be0a5e2d50fb25');
        $result = (string)($weather->get(49.168384, 16.6756352));
        $this->assertJson($result, 'Result is not valid JSON');
        $this->assertNotEmpty($weather->getIcon($result));
        $this->assertNotEmpty($weather->windDirection($result));
    }

}
