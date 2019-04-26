# php-allegro-rest-api
Simple interface for Allegro REST API resources

## Authorization and Tokens ##
In order to use Allegro REST Api, you have to register your application and authorize it (https://developer.api.allegro.pl/auth/).

### Authorization link ###
```php
$api = new Api($clientId, $clientSecret, $apiKey, $redirectUri, null, null);
echo $api->getAuthorizationUri();
```

### Getting new token ###
```php
# example contents of your_redirect_uri.com/index.php
$code = $_GET['code'];
$api = new Api($clientId, $clientSecret, $apiKey, $redirectUri, null, null);
$response = $api->getNewAccessToken($code);
# response contains json with your access_token and refresh_token
```

### Refreshing existing token ###
```php
$api = new Api($clientId, $clientSecret, $apiKey, $redirectUri, $accessToken, $refreshToken);
$response = $api->refreshAccessToken();
# response contains json with your new access_token and refresh_token
```

## Example usage ##
```php
$api = new Api($clientId, $clientSecret, $apiKey, $redirectUri, $accessToken, $refreshToken);

// GET https://api.allegro.pl/{resource}
// $api->{resource}->get();

// GET https://api.allegro.pl/categories
$api->categories->get();

// GET https://api.allegro.pl/{resource}/{resource_id}
// $api->{resource}({resource_id})->get();

// GET https://api.allegro.pl/categories/2
$api->categories(2)->get();

// PUT https://api.allegro.pl/{resource}/{resource_id}/{command-name}-command/{uuid}
// $api->{resource}({resource_id})->commands()->{command_name}($data);

// PUT https://api.allegro.pl/offers/12345/change-price-commands/84c16171-233a-42de-8115-1f1235c8bc0f
$api->offers(12345)->commands()->change_price($data);
```
