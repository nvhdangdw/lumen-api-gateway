<?php

declare(strict_types=1);

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
    public function request(
        $method,
        $requestUrl,
        $data =  [
            'query' => [],
            'form_params' => [],
            'body' => [],
        ],
        $headers = []
    ): string {
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

        $response = $client->request(
            $method,
            $requestUrl,
            array_merge($data, [
                'headers' => $headers
            ])
        );

        return $response->getBody()->getContents();
    }
}
