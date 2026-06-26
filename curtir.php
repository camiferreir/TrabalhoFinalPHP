
<?php
require_once "verifica_login.php";
require_once "conexao.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$noticia = (int) $_GET["id"];
$usuario = $_SESSION["usuario"];

$sql = $pdo->prepare("SELECT id FROM noticias WHERE id = ?");
$sql->execute([$noticia]);

if ($sql->rowCount() == 0) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT id
    FROM likes_noticia
    WHERE noticia_id = ?
    AND usuario_id = ?
");

$sql->execute([$noticia, $usuario]);

if ($sql->rowCount() == 0) {

    $insert = $pdo->prepare("
        INSERT INTO likes_noticia
        (noticia_id, usuario_id)
        VALUES (?, ?)
    ");

    $insert->execute([$noticia, $usuario]);

}

header("Location: noticia.php?id=" . $noticia);
exit;
?>