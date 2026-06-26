<?php
require_once "conexao.php";
require_once "funcoes.php";

$sql = $pdo->query("
SELECT noticias.*, usuarios.nome
FROM noticias
INNER JOIN usuarios
ON noticias.autor = usuarios.id
ORDER BY data DESC
");

$noticias = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Babado Total</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="index.php">

📰 Babado Total

</a>

<div>

<?php if(usuarioLogado()){ ?>

<a href="dashboard.php" class="btn btn-light me-2">

Dashboard

</a>

<a href="nova_noticia.php" class="btn btn-warning me-2">

Nova Fofoca

</a>

<a href="logout.php" class="btn btn-danger">

Sair

</a>

<?php } else { ?>

<a href="login.php" class="btn btn-success me-2">

Login

</a>

<a href="cadastro.php" class="btn btn-primary">

Cadastro

</a>

<?php } ?>

</div>

</div>

</nav>

<div class="container mt-5">

<h1 class="mb-4">

🔥 Últimas Fofocas

</h1>

<div class="row">

<?php foreach($noticias as $noticia){ ?>

<div class="col-md-4 mb-4">

<div class="card shadow h-100">

<?php

if($noticia["imagem"]!=""){

?>

<img
src="imagens/<?php echo $noticia["imagem"]; ?>"
class="card-img-top"
style="height:250px;object-fit:cover;">

<?php

}

?>

<div class="card-body">

<h4>

<?php echo $noticia["titulo"]; ?>

</h4>

<p>

<?php echo resumo($noticia["noticia"],120); ?>

</p>

<p>

<strong>Autor:</strong>

<?php echo $noticia["nome"]; ?>

</p>

<p>

<strong>Publicado:</strong>

<?php echo formatarData($noticia["data"]); ?>

</p>

<a
href="noticia.php?id=<?php echo $noticia["id"]; ?>"
class="btn btn-pink">

Ler mais

</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</body>

</html>