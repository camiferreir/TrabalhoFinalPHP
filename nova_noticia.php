<?php
require_once "verifica_login.php";
require_once "conexao.php";
require_once "funcoes.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = limpar($_POST["titulo"]);
    $noticia = trim($_POST["noticia"]);
    $autor = $_SESSION["usuario"];
    $imagem = "";

    // Upload da imagem
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {

        $permitidas = ["jpg", "jpeg", "png", "gif", "webp"];

        $extensao = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));

        if (in_array($extensao, $permitidas)) {

            if (!is_dir("imagens")) {
                mkdir("imagens", 0777, true);
            }

            $imagem = uniqid() . "." . $extensao;

            move_uploaded_file(
                $_FILES["imagem"]["tmp_name"],
                "imagens/" . $imagem
            );

        } else {

            $mensagem = "<div class='alert alert-danger'>
                Formato de imagem inválido.
            </div>";

        }
    }

    if (empty($mensagem)) {

        $sql = $pdo->prepare("
            INSERT INTO noticias
            (titulo, noticia, autor, imagem)
            VALUES
            (?, ?, ?, ?)
        ");

        if ($sql->execute([$titulo, $noticia, $autor, $imagem])) {

            header("Location: dashboard.php");
            exit;

        } else {

            $mensagem = "<div class='alert alert-danger'>
                Erro ao publicar a notícia.
            </div>";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nova Notícia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark">

<div class="container">

<a href="dashboard.php" class="navbar-brand">

📰 Babado Total

</a>

</div>

</nav>

<div class="container mt-5">

<div class="card shadow p-4">

<h2 class="mb-4">

Publicar Nova Fofoca

</h2>

<?= $mensagem ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Título

</label>

<input
type="text"
name="titulo"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Notícia

</label>

<textarea
name="noticia"
rows="10"
class="form-control"
required></textarea>

</div>

<div class="mb-4">

<label class="form-label">

Imagem

</label>

<input
type="file"
name="imagem"
class="form-control"
accept=".jpg,.jpeg,.png,.gif,.webp">

</div>

<div class="d-flex gap-2">

<button class="btn btn-success">

📢 Publicar

</button>

<a href="dashboard.php" class="btn btn-secondary">

Cancelar

</a>

</div>

</form>

</div>

</div>

</body>

</html>