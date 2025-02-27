<?php
require_once 'google-drive-config.php';

// Função para listar subpastas dentro de uma pasta
function getSubfolders($service, $parentId) {
    $query = "mimeType = 'application/vnd.google-apps.folder' and trashed = false and '$parentId' in parents";
    $subfolders = $service->files->listFiles([
        'q' => $query,
        'fields' => 'files(id, name)'
    ]);
    return $subfolders->getFiles();
}

// Função para listar arquivos dentro de uma pasta
function getFilesInFolder($service, $folderId) {
    $optParams = [
        'q' => "'$folderId' in parents and mimeType != 'application/vnd.google-apps.folder'",  // Filtra os arquivos e exclui pastas
        'fields' => 'files(id, name, mimeType, createdTime)'
    ];
    $files = $service->files->listFiles($optParams);
    return $files->getFiles();
}

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
        exit;
    } else {
        echo "ID do arquivo não informado!";
    }
}

// Variáveis para controle das pastas e arquivos
$folderId = isset($_POST['folder_id']) ? $_POST['folder_id'] : null;
$subfolderId = isset($_POST['subfolder_id']) ? $_POST['subfolder_id'] : null;

// Carregar pastas iniciais
$rootFolders = getSubfolders($service, 'root');

// Se uma pasta foi selecionada, buscar as subpastas e arquivos
if ($folderId) {
    if($subfolderId){
        $files = getFilesInFolder($service, $subfolderId);
    }else{
        $files = getFilesInFolder($service, $folderId);
    }
} else {
    $subfolders = [];
    $files = [];
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Baixar Arquivo</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function loadSubfolders(level, parentId) {
        if (parentId) {
            $.ajax({
                url: 'load_subfolders.php',
                type: 'POST',
                data: { parent_id: parentId },
                success: function(response) {
                    if (response.trim() !== '') {
                        // Exibir o próximo dropdown de subpasta
                        $("#subfolders_" + level).show();
                        $("#sub_folder_" + level).html(response);
                    } else {
                        // Se não houver mais subpastas, esconde o dropdown
                        $("#subfolders_" + level).hide();
                    }
                }
            });
        } else {
            // Esconde todos os dropdowns superiores
            for (let i = level; i <= 5; i++) {
                $("#subfolders_" + i).hide();
                $("#sub_folder_" + i).html('<option value="">Selecione uma subpasta</option>');
            }
        }
    }
</script>

</head>
<body>
    <h2>Baixar Arquivo</h2>

    <!-- Formulário de escolha de pasta -->
    <form action="" method="post">
        <label for="folder_id">Escolha uma pasta-mãe:</label>
        <select name="folder_id" id="folder_id" onchange="loadSubfolders(1, this.value)">
            <option value="">Selecione uma pasta</option>
            <?php
            foreach ($rootFolders as $folder) {
                echo "<option value='{$folder->getId()}'>{$folder->getName()}</option>";
            }
            ?>
        </select><br><br>

        <!-- Dropdown para subpastas -->
        <div id="subfolders_1" style="display: none;">
            <label for="sub_folder_1">Escolha uma subpasta:</label>
            <select name="subfolder_id" id="sub_folder_1" onchange="loadSubfolders(2, this.value)">
                <option value="">Selecione uma subpasta</option>
                <?php
                foreach ($subfolders as $subfolder) {
                    echo "<option value='{$subfolder->getId()}'>{$subfolder->getName()}</option>";
                }
                ?>
            </select><br><br>
        </div>
        <div id="subfolders_2" style="display: none;">
            <label for="sub_folder_2">Escolha uma subpasta:</label>
            <select name="subfolder_id" id="sub_folder_2" onchange="loadSubfolders(3, this.value)">
                <option value="">Selecione uma subpasta</option>
                <?php
                foreach ($subfolders as $subfolder) {
                    echo "<option value='{$subfolder->getId()}'>{$subfolder->getName()}</option>";
                }
                ?>
            </select><br><br>
        </div>

        <input type="submit" value="Listar Arquivos">
    </form>

    <!-- Exibição dos arquivos da pasta/subpasta -->
    <?php if ($files): ?>
        <h3>Arquivos na pasta:</h3>
        <ul>
            <?php foreach ($files as $file): ?>
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
    <?php elseif ($folderId || $subfolderId): ?>
        <p>Não há arquivos nesta pasta.</p>
    <?php endif; ?>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
