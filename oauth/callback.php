<?php
// Start session
session_start();

// Include required libraries
require_once __DIR__ . '/vendor/autoload.php';

// Set up OAuth 2.0 client
$client = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '483619470669-mj5uaa1j7mh0url8molc7nnv846cli2u.apps.googleusercontent.com',    // Replace with your client ID
    'clientSecret'            => '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', // Replace with your client secret
    'redirectUri'             => 'http://localhost/oauth/callback.php', // Replace with your redirect URI
    'urlAuthorize'            => 'https://provider.com/oauth/authorize',
    'urlAccessToken'          => 'https://provider.com/oauth/token',
    'urlResourceOwnerDetails' => 'https://provider.com/oauth/resource'
]);

// Handle the callback
if (isset($_GET['code'])) {
    try {
        // Get access token
        $accessToken = $client->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Store access token in session
        $_SESSION['access_token'] = $accessToken->getToken();

        // Fetch user details
        $resourceOwner = $client->getResourceOwner($accessToken);
        $user = $resourceOwner->toArray();

        // Display user details
        echo 'Hello, ' . htmlspecialchars($user['name']);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        // Handle error
        exit('Error: ' . $e->getMessage());
    }
} else {
    // Redirect to authorization URL if no code is present
    $authorizationUrl = $client->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $client->getState();
    header('Location: ' . $authorizationUrl);
    exit;
}

// Validate state parameter
if (isset($_GET['state']) && $_GET['state'] !== $_SESSION['oauth2state']) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}
?>