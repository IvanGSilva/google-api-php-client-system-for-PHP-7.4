<?php
require_once 'google-drive-config.php';

// Lidar com o processo de download
if (isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    if ($fileId) {
        $file = $service->files->get($fileId, ['alt' => 'media']);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="downloaded_file"');
        echo $file->getBody();
        exit; // Certifique-se de que o código abaixo não será executado após o download
    } else {
        echo "ID do arquivo não informado!";
    }
}

// Buscar os 10 arquivos mais recentes
$optParams = [
    'orderBy' => 'createdTime desc',
    'pageSize' => 10,
    'fields' => 'files(id, name, mimeType, createdTime)'
];
$files = $service->files->listFiles($optParams);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Baixar Arquivo</title>
</head>
<body>
    <h2>Baixar Arquivo</h2>
    
    <!-- Formulário de pesquisa -->
    <form action="" method="post">
        <input type="text" name="file_id" placeholder="ID do Arquivo">
        <input type="submit" value="Baixar">
    </form>

    <h3>Arquivos Recentes:</h3>
    <ul>
        <?php foreach ($files->getFiles() as $file): ?>
            <li>
                <?= $file->getName(); ?> - <small>(<?= $file->getMimeType(); ?>)</small>
                <br>
                <strong>ID:</strong> <?= $file->getId(); ?>
                <br>
                <form action="" method="post">
                    <input type="hidden" name="file_id" value="<?= $file->getId(); ?>">
                    <button type="submit">Baixar</button>
                </form>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
