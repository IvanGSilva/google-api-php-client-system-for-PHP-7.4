<?php
session_start();
require_once 'google-drive-config.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Google Drive - Painel</title>
</head>
<body>
    <h1>Bem-vindo ao Google Drive</h1>
    <ul>
        <li><a href="upload.php">Fazer Upload</a></li>
        <li><a href="download.php">Baixar Arquivo</a></li>
        <li><a href="delete.php">Excluir Arquivo</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</body>
</html>
