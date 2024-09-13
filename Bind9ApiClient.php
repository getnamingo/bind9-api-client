<?php
// Bind9ApiClient.php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Bind9ApiClient
{
    private $client;
    private $baseUrl;
    private $jwtToken;
    private $username;
    private $password;
    private $debugMode;

    /**
     * Constructor
     *
     * @param string $baseUrl The base URL of the BIND9 API server (e.g., http://localhost:9501)
     * @param bool $debugMode Whether to use Basic Auth (for debugging)
     * @param string|null $username Username for Basic Auth
     * @param string|null $password Password for Basic Auth
     */
    public function __construct(string $baseUrl, bool $debugMode = false, string $username = null, string $password = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->debugMode = $debugMode;
        $this->username = $username;
        $this->password = $password;

        $config = [
            'base_uri' => $this->baseUrl,
            'timeout'  => 10.0,
        ];

        if ($this->debugMode && $this->username && $this->password) {
            $config['auth'] = [$this->username, $this->password];
        }

        $this->client = new Client($config);
    }

    /**
     * Authenticate and obtain JWT token
     *
     * @param string $username
     * @param string $password
     * @return void
     * @throws Exception if authentication fails
     */
    public function login(string $username, string $password): void
    {
        try {
            $response = $this->client->post('/login', [
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            if (isset($data['token'])) {
                $this->jwtToken = $data['token'];
            } else {
                throw new Exception('JWT token not found in response.');
            }
        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            throw new Exception("Authentication failed: " . $message);
        }
    }

    /**
     * Set the JWT token manually (useful for testing with a sample token)
     *
     * @param string $token
     * @return void
     */
    public function setJwtToken(string $token): void
    {
        $this->jwtToken = $token;
    }

    /**
     * Get all zones
     *
     * @return array
     * @throws Exception on failure
     */
    public function getZones(): array
    {
        return $this->request('GET', '/zones');
    }

    /**
     * Add a new zone
     *
     * @param string $zoneName
     * @return array
     * @throws Exception on failure
     */
    public function addZone(string $zoneName): array
    {
        return $this->request('POST', '/zones', [
            'json' => ['zone' => $zoneName],
        ]);
    }

    /**
     * Delete a zone
     *
     * @param string $zoneName
     * @return array
     * @throws Exception on failure
     */
    public function deleteZone(string $zoneName): array
    {
        return $this->request('DELETE', "/zones/{$zoneName}");
    }

    /**
     * Get all records in a zone
     *
     * @param string $zoneName
     * @return array
     * @throws Exception on failure
     */
    public function getRecords(string $zoneName): array
    {
        return $this->request('GET', "/zones/{$zoneName}/records");
    }

    /**
     * Add a DNS record to a zone
     *
     * @param string $zoneName
     * @param array $record Associative array with keys: name, type, ttl, rdata
     * @return array
     * @throws Exception on failure
     */
    public function addRecord(string $zoneName, array $record): array
    {
        return $this->request('POST', "/zones/{$zoneName}/records", [
            'json' => $record,
        ]);
    }

    /**
     * Update a DNS record in a zone
     *
     * @param string $zoneName
     * @param string $recordId
     * @param array $record Associative array with keys: name, type, ttl, rdata
     * @return array
     * @throws Exception on failure
     */
    public function updateRecord(string $zoneName, string $recordId, array $record): array
    {
        return $this->request('PUT', "/zones/{$zoneName}/records/{$recordId}", [
            'json' => $record,
        ]);
    }

    /**
     * Delete a DNS record from a zone
     *
     * @param string $zoneName
     * @param string $recordId
     * @return array
     * @throws Exception on failure
     */
    public function deleteRecord(string $zoneName, string $recordId): array
    {
        return $this->request('DELETE', "/zones/{$zoneName}/records/{$recordId}");
    }

    /**
     * Internal method to handle HTTP requests
     *
     * @param string $method HTTP method
     * @param string $uri API endpoint
     * @param array $options Guzzle request options
     * @return array Decoded JSON response
     * @throws Exception on failure
     */
    private function request(string $method, string $uri, array $options = []): array
    {
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if ($this->jwtToken) {
            $options['headers']['Authorization'] = "Bearer {$this->jwtToken}";
        }

        try {
            $response = $this->client->request($method, $uri, $options);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            return $data;
        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            throw new Exception("API request failed: " . $message);
        }
    }
}
