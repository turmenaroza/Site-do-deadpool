<?php

$mysqli = new mysqli("localhost", "root", "root", "loginAcademiaHandebol1_db");
if ($mysqli->connect_errno) {
    die("Erro de conexão: " . $mysqli->connect_error);
}


session_start();


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location:login.php");
    exit;
}


$msg = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"] ?? "";
    $pass = $_POST["password"] ?? "";

  
    $stmt = $mysqli->prepare("SELECT pk, username, senha FROM usuarios WHERE username=? AND senha=?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->fetch_assoc();
    $stmt->close();

   
    if ($dados) {
        $_SESSION["user_id"] = $dados["pk"]; 
        $_SESSION["username"] = $dados["username"];
        header("Location: deadpool.php");
        exit;
    } else {
        $msg = "Usuário ou senha incorretos!";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Login Simples</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!empty($_SESSION["user_id"])): ?>
    
    <div class="card">
        <h3>Bem-vindo, <?= htmlspecialchars($_SESSION["username"]) ?>!</h3>
        <p>Sessão ativa, certeza que quer sair?.</p>
        <p><a href="?logout=1">Sair</a></p>
    </div>

<?php else: ?>
   
    <div class="card">
        <h3>Login</h3>
        <?php if ($msg): ?><p class="msg"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p><small>Dica: admin / 123</small></p>
    </div>
<?php endif; ?>

</body>
</html>
