<?php
require_once 'google-drive-config.php';

// Lidar com o processo de upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['name'] != '') {
        // Criação do arquivo no Google Drive
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($_FILES['file']['name']);

        $content = file_get_contents($_FILES['file']['tmp_name']);
        $uploadedFile = $service->files->create($file, [
            'data' => $content,
            'mimeType' => $_FILES['file']['type'],
            'uploadType' => 'multipart'
        ]);

        echo "Arquivo enviado com sucesso! ID: " . $uploadedFile->id;
    } else {
        echo "Erro no upload! Nenhum arquivo selecionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Upload de Arquivo</title>
</head>
<body>
    <h2>Upload de Arquivo</h2>

    <!-- Formulário de upload -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <input type="submit" value="Enviar">
    </form>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
