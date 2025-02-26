<?php
session_start();
require_once 'google-drive-config.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $_SESSION['access_token'] = $token;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Erro ao autenticar: " . $token['error_description'];
        exit();
    }
}

$authUrl = $client->createAuthUrl();
header("Location: $authUrl");
exit();
?>
