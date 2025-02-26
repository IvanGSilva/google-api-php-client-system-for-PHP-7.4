<?php
session_start();
require_once dirname(__FILE__) . '/google-api-php-client/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('REDIRECT URI SET ON GOOGLE CLOUD CONSOLE');
$client->addScope(Google_Service_Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            unset($_SESSION['access_token']);
            header('Location: index.php');
            exit();
        }
    }
} elseif (basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header('Location: index.php');
    exit();
}

$service = new Google_Service_Drive($client);
?>
