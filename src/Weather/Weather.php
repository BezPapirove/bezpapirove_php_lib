<?php
/**
 * Description of Weather
 *
 * description
 * 08.01.2025
 *
 * @author Bc. Tomáš Otruba <tomas@bezpapirove.cz>
 *
 * @link http://www.bezpapirove.cz/
 *
 * @version 1.0.0
 */

declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Weather;

use Bezpapirove\BezpapirovePhpLib\Client\BaseClient;
use Bezpapirove\BezpapirovePhpLib\Weather\Helpers\Formatter;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class Weather extends BaseClient
{

    private $api_key;

    public function __construct(string $base_url, string $api_key)
    {
        $this->base_url = $base_url;
        if (empty($this->base_url)) {
            throw new \Exception('Base URL on client can not be blank');
        }
        $this->api_key = $api_key;
        $this->client = new Client();
        return $this;
    }

    public function get(float $lat, float  $lon, string $lang = 'cz') : string|StreamInterface
    {
        $params = '?lat=' . $lat . '&lon=' . $lon . '&appid=' . $this->api_key . '&units=metric&lang=' . $lang;
        try {
            $result = $this->runRequest($params);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $result->getBody();
    }

    public function getIcon(string $weather_json) : ?string
    {
        $weather_object = json_decode($weather_json);
        if (isset($weather_object->weather[0]->icon)) {
            return 'https://openweathermap.org/img/wn/'.$weather_object->weather[0]->icon.'@2x.png';
        } else {
            return null;
        }
    }

    public function windDirection(string $weather_json) : ?string
    {
        $weather_object = json_decode($weather_json);
        if (isset($weather_object->wind->deg)) {
            return Formatter::getWindDirectionFromDegrees($weather_object->wind->deg);
        } else {
            return null;
        }
    }

}
