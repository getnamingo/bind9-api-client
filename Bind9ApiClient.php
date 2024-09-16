<?php
// Bind9ApiClient.php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Bind9ApiClient
{
    private $client;
    private $baseUrl;
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
     * Authenticate
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
        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            throw new Exception("Authentication failed: " . $message);
        }
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
     * @param string $zoneName The name of the DNS zone.
     * @param array $currentRecord Associative array with keys: name, type, rdata
     * @param array $newRecord Associative array with keys: name, ttl, rdata, comment (optional)
     * @return array Response from the API
     * @throws Exception on failure
     */
    public function updateRecord(string $zoneName, array $currentRecord, array $newRecord): array
    {
        // Validate current record identification
        if (
            empty($currentRecord['name']) ||
            empty($currentRecord['type']) ||
            empty($currentRecord['rdata'])
        ) {
            throw new InvalidArgumentException('Current record name, type, and rdata are required for identification.');
        }

        // Prepare the payload
        $payload = [
            'current_name' => $currentRecord['name'],
            'current_type' => strtoupper($currentRecord['type']),
            'current_rdata' => $currentRecord['rdata'],
        ];

        // Include new record data if provided
        if (isset($newRecord['name'])) {
            $payload['new_name'] = $newRecord['name'];
        }
        if (isset($newRecord['ttl'])) {
            $payload['new_ttl'] = intval($newRecord['ttl']);
        }
        if (isset($newRecord['rdata'])) {
            $payload['new_rdata'] = $newRecord['rdata'];
        }
        if (isset($newRecord['comment'])) {
            $payload['new_comment'] = $newRecord['comment'];
        }

        // Send the PUT request to the updated endpoint
        return $this->request('PUT', "/zones/{$zoneName}/records/update", [
            'json' => $payload,
        ]);
    }

    /**
     * Delete a DNS record from a zone
     *
     * @param string $zoneName The name of the DNS zone.
     * @param array $record Associative array with keys: name, type, rdata
     * @return array Response from the API
     * @throws Exception on failure
     */
    public function deleteRecord(string $zoneName, array $record): array
    {
        // Validate record identification
        if (
            empty($record['name']) ||
            empty($record['type']) ||
            empty($record['rdata'])
        ) {
            throw new InvalidArgumentException('Record name, type, and rdata are required for identification.');
        }

        // Prepare the payload
        $payload = [
            'name' => $record['name'],
            'type' => strtoupper($record['type']),
            'rdata' => $record['rdata'],
        ];

        // Send the DELETE request to the updated endpoint
        return $this->request('DELETE', "/zones/{$zoneName}/records/delete", [
            'json' => $payload,
        ]);
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
