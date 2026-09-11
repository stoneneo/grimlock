![Grimlock Logo](grimlock.png)

# Module Rest Client

---

## How to use

### Call a Rest Api

1. Call a GET Endpoint

```php
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

$restClient = new RestClient('http://localhost:8080/api/v1/');
$restClient->addHeader(
                'Authorization', 
                'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30');

//Return Object Response
$response = $restClient->get('customers');

if ($response->code == 200) {
    $data = $response->body;
} 

//Use $data
```

2. Call a POST Endpoint

```php
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

$restClient = new RestClient('http://localhost:8080/api/v1/');
$restClient->addHeader(
                'Authorization', 
                'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30');

//Return Object Response
$response = $restClient->post('customers');

if ($response->code == 200) {
    $data = $response->body;
} 

//Use $data
```