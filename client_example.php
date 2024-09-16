<?php
// client_example.php

require 'Bind9ApiClient.php';

try {
    // Initialize the client with the API server's base URL
    $apiClient = new Bind9ApiClient('http://localhost:7650');

    // Authenticate and obtain a token
    $apiClient->login('admin', 'password123');

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
    // Define the current record to identify it
    $currentRecord = [
        'name' => 'www',
        'type' => 'A',
        'rdata' => '192.0.2.1'
    ];
    // Define the new data for the record
    $newRecord = [
        'rdata' => '192.0.2.2'
    ];
    $updateRecordResponse = $apiClient->updateRecord($zoneName, $currentRecord, $newRecord);
    echo "Update Record Response:\n";
    print_r($updateRecordResponse);
    */

    // 7. Delete a DNS record
    /*
    $zoneName = 'example.com';
    // Define the record to delete
    $record = [
        'name' => 'www',
        'type' => 'A',
        'rdata' => '192.0.2.2'
    ];
    $deleteRecordResponse = $apiClient->deleteRecord($zoneName, $record);
    echo "Delete Record Response:\n";
    print_r($deleteRecordResponse);
    */

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
