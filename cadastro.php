<?php
require_once "conexao.php";
require_once "funcoes.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = limpar($_POST["nome"]);
    $email = limpar($_POST["email"]);
    $senha = $_POST["senha"];
    $confirmar = $_POST["confirmar"];

    if ($senha != $confirmar) {

        $mensagem = "<div class='alert alert-danger'>As senhas não coincidem.</div>";

    } else {

        $sql = $pdo->prepare("SELECT id FROM usuarios WHERE email=?");
        $sql->execute([$email]);

        if ($sql->rowCount() > 0) {

            $mensagem = "<div class='alert alert-warning'>Este e-mail já está cadastrado.</div>";

        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = $pdo->prepare("INSERT INTO usuarios(nome,email,senha) VALUES(?,?,?)");

            if($sql->execute([$nome,$email,$senhaHash])){

                header("Location: login.php");

            }else{

                $mensagem="<div class='alert alert-danger'>Erro ao cadastrar.</div>";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Cadastro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 shadow">

<h2 class="text-center mb-4">Criar Conta</h2>

<?= $mensagem ?>

<form method="POST">

<div class="mb-3">

<label>Nome</label>

<input
type="text"
name="nome"
class="form-control"
required>

</div>

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

<div class="mb-3">

<label>Confirmar senha</label>

<input
type="password"
name="confirmar"
class="form-control"
required>

</div>

<button class="btn btn-primary w-100">

Cadastrar

</button>

</form>

<div class="text-center mt-3">

<a href="login.php">

Já possui conta? Faça login

</a>

</div>

</div>

</div>

</body>

</html>