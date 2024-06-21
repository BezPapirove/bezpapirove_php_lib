<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Client;

use Bezpapirove\BezpapirovePhpLib\Exception\RequestErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use JsonException;
use Psr\Http\Message\ResponseInterface;

/**
 * BaseClient is base HTTP client based on GuzzleHttp Client
 *
 * @author Tomáš Otruba <tomas@bezpapirove.cz>
 * @copyright 2024 BezPapírově s.r.o.
 *
 * @version 1.0
 */
class BaseClient
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    protected Client $client;
    protected mixed $response_data = null;
    protected string $base_url;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getResponseData(string $key = null, mixed $default = null): mixed
    {
        if (empty($key)) {
            return ($this->response_data ?? $default);
        }

        if (is_array($this->response_data)) {
            return $this->response_data[$key] ?? $default;
        }

        return ($this->response_data ?? $default);
    }

    /**
     * @throws GuzzleException
     * @throws RequestErrorException
     * @throws JsonException
     *
     * @param string $endpoint
     * @param string $method
     * @param null|mixed $data
     * @param null|string $file
     */
    protected function runRequest(string $endpoint, string $method = self::METHOD_GET, mixed $data = null, string $file = null): ResponseInterface
    {

        $options = [];

        if (empty($file) === false && is_file($file)) {
            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data',
            ];
            $options['multipart'] = [
                [
                    'name'     => $file,
                    'contents' => Utils::tryFopen($file, 'r'),
                    'filename' => $file,
                ],
            ];
        } else {
            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
        }

        if (empty($data) === false && is_array($data)) {
            $options['body'] = json_encode($data, JSON_THROW_ON_ERROR);
        } elseif (empty($data) === false) {
            $options['body'] = $data;
        }
        $response = $this->client->request($method, $this->base_url . $endpoint, $options);
        if ($response->getStatusCode() !== 200) {
            throw new RequestErrorException($response->getStatusCode() . ' - ' . $response->getReasonPhrase() . ' - ' . $response->getBody());
        }
        return $response;
    }
}
