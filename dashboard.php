<?php
require_once "verifica_login.php";
require_once "conexao.php";
require_once "funcoes.php";

$idUsuario = $_SESSION["usuario"];

$sql = $pdo->prepare("
SELECT *
FROM noticias
WHERE autor = ?
ORDER BY data DESC
");

$sql->execute([$idUsuario]);

$noticias = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

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

            <a href="index.php" class="btn btn-light me-2">

                Página Inicial

            </a>

            <a href="nova_noticia.php" class="btn btn-warning me-2">

                Nova Fofoca

            </a>

            <a href="editar_usuario.php" class="btn btn-info me-2">

                Minha Conta

            </a>

            <a href="logout.php" class="btn btn-danger">

                Sair

            </a>

        </div>

    </div>

</nav>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2>

            Olá,

            <?php echo $_SESSION["nome"]; ?> 👋

        </h2>

        <p>

            Bem-vindo ao seu painel.

        </p>

    </div>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">

        <h3>

            Suas Notícias

        </h3>

        <a href="nova_noticia.php" class="btn btn-success">

            ➕ Nova Notícia

        </a>

    </div>

<?php

if(count($noticias)==0){

?>

<div class="alert alert-warning">

Você ainda não publicou nenhuma notícia.

</div>

<?php

}else{

?>

<table class="table table-bordered table-hover shadow">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Título</th>

<th>Data</th>

<th>Ações</th>

</tr>

</thead>

<tbody>

<?php

foreach($noticias as $n){

?>

<tr>

<td>

<?php echo $n["id"]; ?>

</td>

<td>

<?php echo $n["titulo"]; ?>

</td>

<td>

<?php echo formatarData($n["data"]); ?>

</td>

<td>

<a
href="editar_noticia.php?id=<?php echo $n["id"]; ?>"
class="btn btn-primary btn-sm">

Editar

</a>

<a
href="excluir_noticia.php?id=<?php echo $n["id"]; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deseja realmente excluir esta notícia?')">

Excluir

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}

?>

</div>

</body>

</html>