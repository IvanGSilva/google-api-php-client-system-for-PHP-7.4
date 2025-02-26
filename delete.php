<?php
require_once 'google-drive-config.php';

// Lidar com a exclusão do arquivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    if ($fileId) {
        try {
            // Excluir o arquivo do Google Drive
            $service->files->delete($fileId);
            echo "Arquivo excluído com sucesso!";
        } catch (Exception $e) {
            echo "Erro ao excluir o arquivo: " . $e->getMessage();
        }
    } else {
        echo "ID do arquivo não informado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Excluir Arquivo</title>
</head>
<body>
    <h2>Excluir Arquivo</h2>

    <!-- Formulário de exclusão -->
    <form action="" method="post">
        <input type="text" name="file_id" placeholder="ID do Arquivo" required>
        <input type="submit" value="Excluir">
    </form>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
