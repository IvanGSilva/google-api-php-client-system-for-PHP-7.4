<?php
require_once 'google-drive-config.php';

// Lidar com o processo de download
if (isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    if ($fileId) {
        // Obter os detalhes do arquivo (metadados do arquivo, como mimeType)
        $fileMetadata = $service->files->get($fileId);
        
        // Obter o tipo MIME do arquivo
        $mimeType = $fileMetadata->getMimeType();
        $fileName = $fileMetadata->getName();

        // Obter o conteúdo do arquivo
        $file = $service->files->get($fileId, ['alt' => 'media']);
        
        // Definir os cabeçalhos para o download com o tipo MIME correto
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        
        // Imprimir o conteúdo do arquivo
        echo $file->getBody();
        exit; // Certifique-se de que o código abaixo não será executado após o download
    } else {
        echo "ID do arquivo não informado!";
    }
}

// Verificar se o usuário escolheu uma pasta
$folderId = isset($_POST['folder_id']) ? $_POST['folder_id'] : null;

if ($folderId) {
    // Buscar arquivos da pasta específica
    $optParams = [
        'q' => "'$folderId' in parents",  // Filtra os arquivos pela pasta
        'fields' => 'files(id, name, mimeType, createdTime)',
    ];
    $files = $service->files->listFiles($optParams);
} else {
    // Caso nenhuma pasta tenha sido selecionada
    $files = null;
}

// Buscar todas as pastas disponíveis para que o usuário possa escolher
$folders = $service->files->listFiles([
    'q' => "mimeType = 'application/vnd.google-apps.folder'",
    'fields' => 'files(id, name)'
]);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Baixar Arquivo</title>
</head>
<body>
    <h2>Baixar Arquivo</h2>

    <!-- Formulário de escolha de pasta -->
    <form action="" method="post">
        <label for="folder_id">Escolha uma pasta:</label>
        <select name="folder_id" required>
            <option value="">Selecione uma pasta</option>
            <?php
            // Exibe as pastas para o usuário escolher
            foreach ($folders->files as $folder) {
                echo "<option value='{$folder->id}'>{$folder->name}</option>";
            }
            ?>
        </select>
        <input type="submit" value="Listar Arquivos">
    </form>

    <?php if ($files): ?>
        <h3>Arquivos na pasta:</h3>
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
    <?php elseif ($folderId): ?>
        <p>Não há arquivos nesta pasta.</p>
    <?php endif; ?>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
