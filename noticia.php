<?php
require_once "conexao.php";
require_once "funcoes.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if(!$id){
    header("Location: index.php");
    exit;
}

// Busca a notícia
$sql = $pdo->prepare("
SELECT noticias.*, usuarios.nome
FROM noticias
INNER JOIN usuarios
ON noticias.autor = usuarios.id
WHERE noticias.id = ?
");

$sql->execute([$id]);

$noticia = $sql->fetch(PDO::FETCH_ASSOC);

if(!$noticia){
    die("Notícia não encontrada.");
}

// Conta curtidas
$sqlLikes = $pdo->prepare("
SELECT COUNT(*) AS total
FROM likes_noticia
WHERE noticia_id = ?
");

$sqlLikes->execute([$id]);

$totalLikes = $sqlLikes->fetch(PDO::FETCH_ASSOC);

// Conta comentários
$sqlComentarios = $pdo->prepare("
SELECT COUNT(*) AS total
FROM comentarios
WHERE noticia_id = ?
");

$sqlComentarios->execute([$id]);

$totalComentarios = $sqlComentarios->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário já curtiu
$curtiu = false;

if(usuarioLogado()){

    $verifica = $pdo->prepare("
    SELECT id
    FROM likes_noticia
    WHERE noticia_id=?
    AND usuario_id=?
    ");

    $verifica->execute([
        $id,
        $_SESSION["usuario"]
    ]);

    $curtiu = $verifica->rowCount() > 0;
}

// Busca comentários
$sqlLista = $pdo->prepare("
SELECT comentarios.*, usuarios.nome
FROM comentarios

INNER JOIN usuarios

ON comentarios.usuario_id = usuarios.id

WHERE noticia_id=?

ORDER BY comentarios.data DESC
");

$sqlLista->execute([$id]);

$comentarios = $sqlLista->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($noticia["titulo"]) ?></title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container">

<a class="navbar-brand"
href="index.php">

📰 Babado Total

</a>

<div>

<?php if(usuarioLogado()){ ?>

<a
href="dashboard.php"
class="btn btn-light">

Dashboard

</a>

<?php } ?>

</div>

</div>

</nav>

<div class="container mt-5">

<div class="card shadow">

<?php if(!empty($noticia["imagem"])){ ?>

<img
src="imagens/<?= $noticia["imagem"] ?>"
class="noticia-img">

<?php } ?>

<div class="card-body">

<h1>

<?= htmlspecialchars($noticia["titulo"]) ?>

</h1>

<p>

<strong>Autor:</strong>

<?= htmlspecialchars($noticia["nome"]) ?>

</p>

<p>

<strong>Publicado em:</strong>

<?= formatarData($noticia["data"]) ?>

</p>

<hr>

<p class="noticia-texto">

<?= nl2br(htmlspecialchars($noticia["noticia"])) ?>

</p>

<hr>

<div class="d-flex gap-3">

<span class="badge bg-danger">

❤️ <?= $totalLikes["total"] ?>

</span>

<span class="badge bg-primary">

💬 <?= $totalComentarios["total"] ?>

</span>

</div>

<br>
<!-- BOTÃO CURTIR -->

<?php if(usuarioLogado()){ ?>

    <?php if(!$curtiu){ ?>

        <a
            href="curtir.php?id=<?= $noticia["id"] ?>"
            class="btn btn-danger">

            ❤️ Curtir

        </a>

    <?php }else{ ?>

        <button
            class="btn btn-secondary"
            disabled>

            ❤️ Curtido

        </button>

    <?php } ?>

<?php }else{ ?>

<div class="alert alert-warning mt-3">

Faça login para curtir esta notícia.

</div>

<?php } ?>

<hr>

<h3 class="mt-4">

💬 Comentários (<?= $totalComentarios["total"] ?>)

</h3>

<?php if(usuarioLogado()){ ?>

<form
action="comentar.php"
method="POST"
class="mb-4">

<input
type="hidden"
name="noticia_id"
value="<?= $noticia["id"] ?>">

<div class="mb-3">

<textarea
name="comentario"
class="form-control"
rows="4"
placeholder="Escreva seu comentário..."
required></textarea>

</div>

<button
class="btn btn-primary">

Comentar

</button>

</form>

<?php }else{ ?>

<div class="alert alert-info">

Faça login para comentar nesta notícia.

</div>

<?php } ?>

<?php if(count($comentarios) > 0){ ?>

<?php foreach($comentarios as $comentario){ ?>

<div class="card mt-3 shadow-sm">

<div class="card-body">

<h5>

👤 <?= htmlspecialchars($comentario["nome"]) ?>

</h5>

<small class="text-muted">

<?= formatarData($comentario["data"]) ?>

</small>

<hr>

<p>

<?= nl2br(htmlspecialchars($comentario["comentario"])) ?>

</p>

</div>

</div>

<?php } ?>

<?php }else{ ?>

<div class="alert alert-secondary">

Nenhum comentário até o momento.

Seja o primeiro a comentar!

</div>

<?php } ?>

</div>

</div>

<div class="mt-4">

<a
href="index.php"
class="btn btn-secondary">

← Voltar para a página inicial

</a>

</div>

</div>

</body>

</html>