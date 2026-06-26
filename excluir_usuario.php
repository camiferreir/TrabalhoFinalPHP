<?php
require_once "verifica_login.php";
require_once "conexao.php";

$id = $_SESSION["usuario"];

$sql = $pdo->prepare("
SELECT imagem
FROM noticias
WHERE autor=?
");

$sql->execute([$id]);

while ($img = $sql->fetch(PDO::FETCH_ASSOC)) {

    if (!empty($img["imagem"])) {

        $arquivo = "imagens/" . $img["imagem"];

        if (file_exists($arquivo)) {
            unlink($arquivo);
        }

    }

}


$delete = $pdo->prepare("
DELETE FROM usuarios
WHERE id=?
");

$delete->execute([$id]);

session_destroy();

header("Location: index.php");
exit;
?>