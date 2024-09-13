<?php
// client_example.php

require 'Bind9ApiClient.php';

try {
    // Initialize the client with the API server's base URL
    $apiClient = new Bind9ApiClient('http://localhost:9501');

    // Option 1: Authenticate and obtain a token
    /*
    $apiClient->login('admin', 'password123');
    */

    // Option 2: Use a sample JWT token (for testing purposes)
    $sampleToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJiaW5kOSthcGkiLCJpYXQiOjE2ODg3NzQ4MDAsImV4cCI6MTY4ODc3ODQwMH0.WxqN1IYJxvFSxQw5Kf3CqKfyQzF5QfEjUJiL1K6eQYE';
    $apiClient->setJwtToken($sampleToken);

    // 1. List all zones
    $zones = $apiClient->getZones();
    echo "Zones:\n";
    print_r($zones);

    // 2. Add a new zone
    /*
    $newZone = 'example.com';
    $addZoneResponse = $apiClient->addZone($newZone);
    echo "Add Zone Response:\n";
    print_r($addZoneResponse);
    */

    // 3. Delete a zone
    /*
    $zoneToDelete = 'example.com';
    $deleteZoneResponse = $apiClient->deleteZone($zoneToDelete);
    echo "Delete Zone Response:\n";
    print_r($deleteZoneResponse);
    */

    // 4. List records in a zone
    /*
    $zoneName = 'example.com';
    $records = $apiClient->getRecords($zoneName);
    echo "Records in {$zoneName}:\n";
    print_r($records);
    */

    // 5. Add a DNS record
    /*
    $zoneName = 'example.com';
    $record = [
        'name' => 'www',
        'type' => 'A',
        'ttl' => 3600,
        'rdata' => '192.0.2.1'
    ];
    $addRecordResponse = $apiClient->addRecord($zoneName, $record);
    echo "Add Record Response:\n";
    print_r($addRecordResponse);
    */

    // 6. Update a DNS record
    /*
    $zoneName = 'example.com';
    $recordId = '00000003'; // Replace with actual record ID
    $updatedRecord = [
        'rdata' => '192.0.2.2'
    ];
    $updateRecordResponse = $apiClient->updateRecord($zoneName, $recordId, $updatedRecord);
    echo "Update Record Response:\n";
    print_r($updateRecordResponse);
    */

    // 7. Delete a DNS record
    /*
    $zoneName = 'example.com';
    $recordId = '00000003'; // Replace with actual record ID
    $deleteRecordResponse = $apiClient->deleteRecord($zoneName, $recordId);
    echo "Delete Record Response:\n";
    print_r($deleteRecordResponse);
    */

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
