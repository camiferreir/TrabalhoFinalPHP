<?php

require_once "conexao.php";
require_once "funcoes.php";

$id=$_GET["id"];

$sql=$pdo->prepare("
SELECT noticias.*,usuarios.nome
FROM noticias
INNER JOIN usuarios
ON noticias.autor=usuarios.id
WHERE noticias.id=?
");

$sql->execute([$id]);

$noticia=$sql->fetch(PDO::FETCH_ASSOC);

if(!$noticia){

die("Notícia não encontrada.");

}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $noticia["titulo"]; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<a href="index.php" class="btn btn-secondary mb-4">

← Voltar

</a>

<div class="card shadow">

<?php

if($noticia["imagem"]!=""){

?>

<img
src="imagens/<?php echo $noticia["imagem"]; ?>"
style="width:100%;max-height:500px;object-fit:cover;">

<?php

}

?>

<div class="card-body">

<h1>

<?php echo $noticia["titulo"]; ?>

</h1>

<p>

<strong>Autor:</strong>

<?php echo $noticia["nome"]; ?>

</p>

<p>

<strong>Publicado:</strong>

<?php echo formatarData($noticia["data"]); ?>

</p>

<hr>

<p style="font-size:20px;line-height:35px;">

<?php echo nl2br($noticia["noticia"]); ?>

</p>

</div>

</div>

</div>

</body>

</html>