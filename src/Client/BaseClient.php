<?php

namespace Bezpapirove\BezpapirovePhpLib\Client;

use Bezpapirove\BezpapirovePhpLib\Exception\RequestErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;

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

    protected $client;
    protected $response_data = null;
    protected $error;
    protected $base_url;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public function __construct()
    {
        $this->client = new Client();
        return $this;
    }

    protected function runRequest($endpoint, $method = self::METHOD_GET, $data = null, $file = null): Response
    {

        $options = [];

        if (false === empty($file) && is_file($file)) {
            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data',
            ];
            $options['multipart'] = [
                [
                    'name'     => $file,
                    'contents' => Utils::tryFopen($file, 'r'),
                    'filename' => $file
                ]
            ];
        } else {
            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
        }

        if (false === empty($data) && is_array($data)) {
            $options['body'] = json_encode($data);
        } elseif (false === empty($data)) {
            $options['body'] = $data;
        }
        $response = $this->client->request($method, $this->base_url . $endpoint, $options);
        if ($response->getStatusCode() != 200) {
            throw new RequestErrorException($response->getStatusCode() . ' - ' . $response->getReasonPhrase() . ' - ' . $response->getBody());
        }
        return $response;
    }

    public function getResponseData(string $key = null, $default = null)
    {
        if (empty($key)) {
            return ($this->response_data == null ? $default : $this->response_data);
        } else {
            if (is_array($this->response_data)) {
                return $this->response_data[$key] ?? $default;
            } else {
                return ($this->response_data == null ? $default : $this->response_data);
            }
        }
    }

    /**
     * Get the value of error
     */
    public function getError()
    {
        return $this->error;
    }
}
