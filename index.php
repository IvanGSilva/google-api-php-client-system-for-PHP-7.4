<?php
session_start();
require_once 'google-drive-config.php';

// Se o usuário já está autenticado, redireciona para o dashboard
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: dashboard.php');
    exit();
}

// Se a URL tiver um "code" do Google, processamos a autenticação
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

// Se não há token, exibe botão para login
$authUrl = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Google Drive</title>
</head>
<body>
    <h2>Login no Google Drive</h2>
    <a href="<?= $authUrl ?>">Entrar com o Google</a>
</body>
</html>
