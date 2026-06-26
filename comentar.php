<?php
require_once "verifica_login.php";
require_once "conexao.php";
require_once "funcoes.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

$noticia = filter_input(INPUT_POST, "noticia_id", FILTER_VALIDATE_INT);
$comentario = limpar($_POST["comentario"] ?? "");
$usuario = $_SESSION["usuario"];

if (!$noticia) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT id
    FROM noticias
    WHERE id = ?
");

$sql->execute([$noticia]);

if ($sql->rowCount() == 0) {
    header("Location: index.php");
    exit;
}

if (empty(trim($comentario))) {
    header("Location: noticia.php?id=" . $noticia . "&erro=comentario");
    exit;
}


$sql = $pdo->prepare("
    INSERT INTO comentarios
    (noticia_id, usuario_id, comentario)
    VALUES (?, ?, ?)
");

$sql->execute([
    $noticia,
    $usuario,
    $comentario
]);

header("Location: noticia.php?id=" . $noticia);
exit;
?>