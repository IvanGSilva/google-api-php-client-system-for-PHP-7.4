<?php
require_once 'google-drive-config.php';

// Lidar com o processo de upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['name'] != '') {
        // Verifica se o usuário escolheu uma pasta ou deseja criar uma nova
        $folderId = $_POST['folder_id'] ?? null;
        
        // Criação do arquivo no Google Drive
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($_FILES['file']['name']);

        // Se uma pasta foi escolhida, define o arquivo para essa pasta
        if ($folderId) {
            $file->setParents([$folderId]);
        }

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

// Lidar com a criação de uma nova pasta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_folder_name'])) {
    $folderName = $_POST['create_folder_name'];
    if ($folderName != '') {
        $folder = new Google_Service_Drive_DriveFile();
        $folder->setName($folderName);
        $folder->setMimeType('application/vnd.google-apps.folder');
        
        // Cria a nova pasta no Google Drive
        $createdFolder = $service->files->create($folder);
        echo "Pasta criada com sucesso! ID: " . $createdFolder->id;
    } else {
        echo "Erro! Nome da pasta não pode ser vazio.";
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
        <label for="file">Escolha um arquivo:</label>
        <input type="file" name="file" required><br><br>

        <label for="folder_id">Escolha uma pasta:</label>
        <select name="folder_id">
            <option value="">Selecione uma pasta</option>
            <?php
            // Listar as pastas do Google Drive
            $folders = $service->files->listFiles([
                'q' => "mimeType = 'application/vnd.google-apps.folder'",
                'fields' => 'files(id, name)'
            ]);

            foreach ($folders->files as $folder) {
                echo "<option value='{$folder->id}'>{$folder->name}</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Enviar">
    </form>

    <h2>Criar Nova Pasta</h2>
    <!-- Formulário para criar uma nova pasta -->
    <form action="" method="post">
        <label for="create_folder_name">Nome da nova pasta:</label>
        <input type="text" name="create_folder_name" required><br><br>
        <input type="submit" value="Criar Pasta">
    </form>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
