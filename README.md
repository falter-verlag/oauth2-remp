# REMP Provider for OAuth 2.0 Client

This package provides REMP OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, add the repository to your `composer.json` file:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/falter-verlag/oauth2-remp"
    }
  ]
}
```

After adding the repository, you can install the package using composer:

```
composer require falter-verlag/oauth2-remp
```

## Usage

Usage is the same as The League's OAuth client, using `\Falter\OAuth2\Client\Provider\Remp` as the provider.

### Authorization Code Flow

```php
$provider = new Falter\OAuth2\Client\Provider\Remp([
    'clientId'          => '{remp-client-id}',
    'clientSecret'      => '{remp-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
    'rempUrl'           => 'https://crm.press',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Failed to get resource owner: '.$e->getMessage());
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/oauth2-github/blob/master/LICENSE) for more information.