<?php
require_once 'google-drive-config.php';

// Função para listar apenas as pastas de nível superior (sem subpastas)
function getRootFolders($service) {
    $query = "mimeType = 'application/vnd.google-apps.folder' and trashed = false and 'root' in parents";
    $folders = $service->files->listFiles([
        'q' => $query,
        'fields' => 'files(id, name)'
    ]);
    return $folders->getFiles();
}

// Buscar apenas as pastas de nível superior
$rootFolders = getRootFolders($service);

// Processo de Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    if (!empty($_FILES['file']['name'])) {
        // Obtém o ID da pasta ou subpasta escolhida
        $folderId = $_POST['final_folder'] ?? null; // Usar o campo oculto final_folder

        // Criação do arquivo no Google Drive
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($_FILES['file']['name']);

        // Se uma pasta foi escolhida, define o arquivo para essa pasta
        if ($folderId) {
            $file->setParents([$folderId]); // Define a pasta pai (onde o arquivo será salvo)
        }

        // Envio do arquivo para o Google Drive
        $content = file_get_contents($_FILES['file']['tmp_name']);
        $uploadedFile = $service->files->create($file, [
            'data' => $content,
            'mimeType' => $_FILES['file']['type'],
            'uploadType' => 'multipart'
        ]);

        echo "Arquivo enviado com sucesso! ID: " . $uploadedFile->id;
    } else {
        echo "Erro! Nenhum arquivo selecionado.";
    }
}

// Criar Nova Pasta dentro da pasta escolhida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_folder_name'])) {
    $folderName = $_POST['create_folder_name'];
    $parentFolderId = $_POST['parent_folder_create'] ?? null;

    if (!empty($folderName)) {
        $folder = new Google_Service_Drive_DriveFile();
        $folder->setName($folderName);
        $folder->setMimeType('application/vnd.google-apps.folder');

        if ($parentFolderId) {
            $folder->setParents([$parentFolderId]);
        }

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
    <title>Gerenciar Arquivos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadSubfolders(level, parentId, formType) {
            if (parentId) {
                $.ajax({
                    url: 'load_subfolders.php',
                    type: 'POST',
                    data: { parent_id: parentId },
                    success: function(response) {
                        if (response.trim() !== '') {
                            // Se for o formulário de upload
                            if (formType === 'upload') {
                                $("#subfolders_" + level).show();
                                $("#sub_folder_" + level).html(response);
                            }
                            // Se for o formulário de criação de pasta
                            else if (formType === 'create') {
                                $("#subfolders_create_" + level).show();
                                $("#sub_folder_create_" + level).html(response);
                            }

                            // Remove dropdowns de níveis superiores para evitar bagunça
                            for (let i = level + 1; i <= 5; i++) {
                                if (formType === 'upload') {
                                    $("#subfolders_" + i).hide();
                                    $("#sub_folder_" + i).html('<option value="">Selecione uma subpasta</option>');
                                } else if (formType === 'create') {
                                    $("#subfolders_create_" + i).hide();
                                    $("#sub_folder_create_" + i).html('<option value="">Selecione uma subpasta</option>');
                                }
                            }

                            // Atualiza o campo oculto com o ID da última pasta
                            if (formType === 'upload') {
                                $("#final_folder").val(parentId);
                            } else if (formType === 'create') {
                                $("#final_folder_create").val(parentId);
                            }
                        } else {
                            // Se não houver mais subpastas, define o último ID selecionado
                            if (formType === 'upload') {
                                $("#final_folder").val(parentId);
                            } else if (formType === 'create') {
                                $("#final_folder_create").val(parentId);
                            }
                        }
                    }
                });
            } else {
                // Esconde todos os dropdowns superiores se a pasta mãe for deselecionada
                for (let i = level; i <= 5; i++) {
                    if (formType === 'upload') {
                        $("#subfolders_" + i).hide();
                        $("#sub_folder_" + i).html('<option value="">Selecione uma subpasta</option>');
                    } else if (formType === 'create') {
                        $("#subfolders_create_" + i).hide();
                        $("#sub_folder_create_" + i).html('<option value="">Selecione uma subpasta</option>');
                    }
                }

                // Limpa o campo oculto
                if (formType === 'upload') {
                    $("#final_folder").val(""); 
                } else if (formType === 'create') {
                    $("#final_folder_create").val(""); 
                }
            }
        }
    </script>
</head>
<body>
    <h2>Upload de Arquivo</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file">Escolha um arquivo:</label>
        <input type="file" name="file" required><br><br>

        <label for="parent_folder">Escolha a pasta-mãe:</label>
        <select id="parent_folder" name="parent_folder" onchange="loadSubfolders(1, this.value, 'upload')">
            <option value="">Selecione uma pasta</option>
            <?php foreach ($rootFolders as $folder) {
                echo "<option value='{$folder->getId()}'>{$folder->getName()}</option>";
            } ?>
        </select><br><br>

        <div id="subfolders_1" class="subfolder-container" style="display: none;">
            <label>Escolha uma subpasta:</label>
            <select id="sub_folder_1" name="sub_folder_1" onchange="loadSubfolders(2, this.value, 'upload')">
                <option value="">Selecione uma subpasta</option>
            </select><br><br>
        </div>

        <div id="subfolders_2" class="subfolder-container" style="display: none;">
            <label>Escolha uma sub-subpasta:</label>
            <select id="sub_folder_2" name="sub_folder_2" onchange="loadSubfolders(3, this.value, 'upload')">
                <option value="">Selecione uma sub-subpasta</option>
            </select><br><br>
        </div>

        <input type="hidden" id="final_folder" name="final_folder">
        <input type="submit" value="Enviar">
    </form>

    <h2>Criar Nova Pasta</h2>
    <form action="" method="post">
        <label for="create_folder_name">Nome da nova pasta:</label>
        <input type="text" name="create_folder_name" required><br><br>

        <label for="parent_folder_create">Escolha a pasta-mãe:</label>
        <select id="parent_folder_create" name="parent_folder_create" onchange="loadSubfolders(1, this.value, 'create')">
            <option value="">Criar na raiz</option>
            <?php foreach ($rootFolders as $folder) {
                echo "<option value='{$folder->getId()}'>{$folder->getName()}</option>";
            } ?>
        </select><br><br>

        <div id="subfolders_create_1" class="subfolder-container" style="display: none;">
            <label>Escolha uma subpasta:</label>
            <select id="sub_folder_create_1" name="sub_folder_create_1" onchange="loadSubfolders(2, this.value, 'create')">
                <option value="">Selecione uma subpasta</option>
            </select><br><br>
        </div>

        <input type="hidden" id="final_folder_create" name="final_folder_create">
        <input type="submit" value="Criar Pasta">
    </form>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
