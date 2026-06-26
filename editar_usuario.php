<?php
require_once "verifica_login.php";
require_once "conexao.php";
require_once "funcoes.php";

$id = $_SESSION["usuario"];

$sql = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$sql->execute([$id]);

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = limpar($_POST["nome"]);
    $email = limpar($_POST["email"]);

    if (!empty($_POST["senha"])) {

        $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

        $update = $pdo->prepare("
        UPDATE usuarios
        SET nome=?, email=?, senha=?
        WHERE id=?
        ");

        $update->execute([
            $nome,
            $email,
            $senha,
            $id
        ]);

    } else {

        $update = $pdo->prepare("
        UPDATE usuarios
        SET nome=?, email=?
        WHERE id=?
        ");

        $update->execute([
            $nome,
            $email,
            $id
        ]);

    }

    $_SESSION["nome"] = $nome;

    $mensagem = "
    <div class='alert alert-success'>
    Dados atualizados com sucesso!
    </div>";

}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<title>Minha Conta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

<div class="card shadow p-4">

<h2>Minha Conta</h2>

<?= $mensagem ?>

<form method="POST">

<label>Nome</label>

<input
class="form-control mb-3"
name="nome"
value="<?= htmlspecialchars($usuario["nome"]) ?>"
required>

<label>E-mail</label>

<input
type="email"
class="form-control mb-3"
name="email"
value="<?= htmlspecialchars($usuario["email"]) ?>"
required>

<label>Nova senha (opcional)</label>

<input
type="password"
class="form-control mb-4"
name="senha">

<button class="btn btn-success">

Salvar Alterações

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

Voltar

</a>

<a
href="excluir_usuario.php"
class="btn btn-danger"
onclick="return confirm('Deseja excluir sua conta?')">

Excluir Conta

</a>

</form>

</div>

</div>

</body>

</html>