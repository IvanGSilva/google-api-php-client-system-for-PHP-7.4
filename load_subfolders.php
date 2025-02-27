<?php
require_once 'google-drive-config.php';

// Função para buscar subpastas dentro de uma pasta
function getSubfolders($service, $parentId) {
    $query = "mimeType = 'application/vnd.google-apps.folder' and trashed = false and '$parentId' in parents";
    $subfolders = $service->files->listFiles([
        'q' => $query,
        'fields' => 'files(id, name)'
    ]);

    return $subfolders->getFiles();
}

// Verifica se foi enviado um ID de pasta via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parent_id'])) {
    $parentId = $_POST['parent_id'];
    $subfolders = getSubfolders($service, $parentId);

    if (count($subfolders) > 0) {
        echo '<option value="">Selecione uma subpasta</option>';
        foreach ($subfolders as $folder) {
            echo "<option value='{$folder->getId()}'>{$folder->getName()}</option>";
        }
    } else {
        echo ""; // Nenhuma subpasta encontrada
    }
    exit;
}
?>
