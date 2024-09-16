# Help

When you create, update or delete MX or DS records, please use the following changes:

```bash
    'rdata' => [
        'exchange' => 'example.com.',
        'preference' => 20
    ],
```
	
```bash
	$rdata = [
    'keytag' => 2371,             // Key tag of the DNSKEY
    'algorithm' => 8,             // Algorithm used (e.g., 8 = RSASHA256)
    'digestType' => 2,            // Digest type (e.g., 2 = SHA-256)
    'digest' => '5E2B4D2C35B2EFA8F2C1D9C4A2DFF5B2904B529A3F1E9E3A56E4F4E6C7B92DF2'
	];
```