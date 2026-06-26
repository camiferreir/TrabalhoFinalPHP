<?php
require_once "verifica_login.php";
require_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$sql = $pdo->prepare("
SELECT *
FROM noticias
WHERE id = ?
AND autor = ?
");

$sql->execute([$id, $usuario]);

$noticia = $sql->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    die("Você não tem permissão para excluir esta notícia.");
}


if (!empty($noticia["imagem"])) {

    $caminho = "imagens/" . $noticia["imagem"];

    if (file_exists($caminho)) {
        unlink($caminho);
    }

}

$delete = $pdo->prepare("
DELETE FROM noticias
WHERE id = ?
AND autor = ?
");

$delete->execute([$id, $usuario]);

header("Location: dashboard.php");
exit;
?>