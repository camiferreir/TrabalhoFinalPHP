<?php
require_once "verifica_login.php";
require_once "conexao.php";
require_once "funcoes.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$usuario = $_SESSION["usuario"];

// Busca a notícia do usuário logado
$sql = $pdo->prepare("
    SELECT *
    FROM noticias
    WHERE id = ? AND autor = ?
");

$sql->execute([$id, $usuario]);

$noticia = $sql->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    die("Notícia não encontrada ou você não tem permissão para editá-la.");
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = limpar($_POST["titulo"]);
    $texto = trim($_POST["noticia"]);
    $imagem = $noticia["imagem"];

    // Nova imagem
    if (
        isset($_FILES["imagem"]) &&
        $_FILES["imagem"]["error"] == 0
    ) {

        $permitidas = ["jpg","jpeg","png","gif","webp"];

        $extensao = strtolower(
            pathinfo(
                $_FILES["imagem"]["name"],
                PATHINFO_EXTENSION
            )
        );

        if (in_array($extensao, $permitidas)) {

            if ($imagem != "" && file_exists("imagens/".$imagem)) {
                unlink("imagens/".$imagem);
            }

            $imagem = uniqid().".".$extensao;

            move_uploaded_file(
                $_FILES["imagem"]["tmp_name"],
                "imagens/".$imagem
            );

        } else {

            $mensagem = "
            <div class='alert alert-danger'>
            Formato de imagem inválido.
            </div>";

        }

    }

    if ($mensagem == "") {

        $update = $pdo->prepare("
            UPDATE noticias
            SET
                titulo = ?,
                noticia = ?,
                imagem = ?
            WHERE
                id = ?
                AND autor = ?
        ");

        if ($update->execute([
            $titulo,
            $texto,
            $imagem,
            $id,
            $usuario
        ])) {

            header("Location: dashboard.php");
            exit;

        } else {

            $mensagem = "
            <div class='alert alert-danger'>
            Erro ao atualizar a notícia.
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

<title>Editar Notícia</title>

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

Editar Fofoca

</h2>

<?= $mensagem ?>

<form
method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Título

</label>

<input
type="text"
name="titulo"
class="form-control"
required
value="<?= htmlspecialchars($noticia["titulo"]) ?>">

</div>

<div class="mb-3">

<label class="form-label">

Notícia

</label>

<textarea
name="noticia"
rows="10"
class="form-control"
required><?= htmlspecialchars($noticia["noticia"]) ?></textarea>

</div>

<?php if($noticia["imagem"]!=""){ ?>

<div class="mb-3">

<label class="form-label">

Imagem atual

</label>

<br>

<img
src="imagens/<?= $noticia["imagem"] ?>"
class="img-fluid rounded shadow"
style="max-width:300px;">

</div>

<?php } ?>

<div class="mb-4">

<label class="form-label">

Trocar imagem (opcional)

</label>

<input
type="file"
name="imagem"
class="form-control"
accept=".jpg,.jpeg,.png,.gif,.webp">

</div>

<div class="d-flex gap-2">

<button class="btn btn-primary">

💾 Salvar Alterações

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

Cancelar

</a>

</div>

</form>

</div>

</div>

</body>

</html>