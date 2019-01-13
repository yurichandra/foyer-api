<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class HttpService
{
    protected $client;

    protected $headers = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    private function sendResponse($response)
    {
        return [
            'status' => $response->getStatusCode(),
            'message' => json_decode((string) $response->getBody()),
        ];
    }

    /**
     * Set headers to perform request.
     */
    public function setHeaders(Request $request)
    {
        $this->headers = [
            'User-Agent' => $request->header('User-Agent'),
            'Authorization' => $request->header('Authorization'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    /**
     * Handle GET method.
     */
    public function get($url, $data)
    {
        try {
            $response = $this->client->request('GET', $url, [
                'query' => $data,
                'header' => $this->headers
            ]);

            return $this->sendResponse($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $this->sendResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle POST method.
     */
    public function post($url, $data)
    {
        try {
            $response = $this->client->request('POST', $url, [
                'json' => $data,
                'header' => $this->headers,
            ]);

            return $this->sendResponse($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $this->sendResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle PATCH method.
     */
    public function patch($url, $data)
    {
        try {
            $response = $this->client->request('PATCH', $url, [
                'json' => $data,
                'header' => $this->headers,
            ]);

            return $this->sendResponse($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $this->sendResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle DELETE method.
     */
    public function delete($url)
    {
        try {
            $response = $this->client->request('DELETE', $url, [
                'header' => $this->headers,
            ]);

            return $this->sendResponse($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $this->sendResponse($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
