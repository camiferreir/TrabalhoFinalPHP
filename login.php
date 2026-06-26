<?php

require_once "conexao.php";
require_once "funcoes.php";

$mensagem="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=limpar($_POST["email"]);
$senha=$_POST["senha"];

$sql=$pdo->prepare("SELECT * FROM usuarios WHERE email=?");

$sql->execute([$email]);

if($sql->rowCount()>0){

$usuario=$sql->fetch();

if(password_verify($senha,$usuario["senha"])){

$_SESSION["usuario"]=$usuario["id"];
$_SESSION["nome"]=$usuario["nome"];

header("Location: dashboard.php");

exit;

}else{

$mensagem="<div class='alert alert-danger'>Senha incorreta.</div>";

}

}else{

$mensagem="<div class='alert alert-danger'>Usuário não encontrado.</div>";

}

}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 shadow">

<h2 class="text-center mb-4">

Entrar

</h2>

<?= $mensagem ?>

<form method="POST">

<div class="mb-3">

<label>E-mail</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Senha</label>

<input
type="password"
name="senha"
class="form-control"
required>

</div>

<button class="btn btn-success w-100">

Entrar

</button>

</form>

<div class="text-center mt-3">

<a href="cadastro.php">

Criar conta

</a>

</div>

</div>

</div>

</body>

</html>