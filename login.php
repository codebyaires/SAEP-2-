<?php
require_once 'conexao.php';
// require_once 'menu.php';

if (!empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = $pdo->prepare('SELECT id_user,nome,senha FROM user WHERE email=?');
  $st->execute([trim($_POST['email'] ?? '')]);  
  $u = $st->fetch();
  // echo $_POST['senha'] ;
  // echo "<br>";
  // echo $u['senha'];
  // die;
  if ($u && password_verify($_POST['senha'] ?? '', $u['senha'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $u['id_user'];
    $_SESSION['user_nome'] = $u['nome'];
    header('Location: menu.php');
    exit;
  }
  $erro = 'E-mail ou senha inválidos.';
}
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Entrar — Estoque SAEP</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <main style="max-width:480px;margin:8vh auto">
    <div class="panel">
      <h1>Estoque SAEP</h1>
      <p>Entre para acessar o sistema.</p><?php if ($erro): ?><p class="erro"><?= htmlspecialchars($erro) ?></p><?php endif ?><form method="post"><label>E-mail<input type="email" name="email" required autocomplete="username"></label><label>Senha<input type="password" name="senha" required autocomplete="current-password"></label><button type="submit">Entrar</button></form>
    </div>
  </main>
</body>

</html>