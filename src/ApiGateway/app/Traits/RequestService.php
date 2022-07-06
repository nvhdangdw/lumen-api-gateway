<?php

declare(strict_types = 1);

namespace App\Traits;

use GuzzleHttp\Client;

trait RequestService
{
    /**
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($requestUrl, $query = [], $headers = []) : string
    {
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        // Append other header
        if (isset($this->headers)) {
            $headers = array_merge($headers, $this->headers);
        }

        $response = $client->request('GET', $requestUrl,
            [
                'query' => $query,
                'headers' => $headers
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($requestUrl, $fromParams = [], $headers = []) : string
    {
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        // Append other header
        if (isset($this->headers)) {
            $headers = array_merge($headers, $this->headers);
        }

        $response = $client->request('POST', $requestUrl,
            [
                'form_params' => $fromParams,
                'headers' => $headers
            ]
        );

        return $response->getBody()->getContents();
    }
}
