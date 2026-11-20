<?php
// require_once __DIR__ . 'includes\menu.php';
require_once 'conexao.php';
$msg = '';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');

    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 6) {
        $erro = 'Informe nome, e-mail válido e senha com pelo menos 6 caracteres.';
    } else {
        try {
            $st = $pdo->prepare('INSERT INTO user(nome,email,senha,tipo,telefone) VALUES(?,?,?,?,?)');
            $st->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $tipo, $telefone]);
            $msg = 'Usuário cadastrado com sucesso.';
        } catch (PDOException $e) {
            $erro = 'Não foi possível cadastrar. Verifique se o e-mail já está em uso.';
        }
    }
}
$usuarios = $pdo->query('SELECT id_user,nome,email,criado_em FROM user ORDER BY nome')->fetchAll();
?>
<h1>Cadastro de usuários</h1>
    <?php if ($msg): ?><p class="msg"><?= htmlspecialchars($msg) ?></p><?php endif ?><?php if ($erro): ?>
      <p class="erro"><?= htmlspecialchars($erro) ?></p><?php endif ?><form method="post">
        <label>Nome<input name="nome" required maxlength="100"></label>
        <label>E-mail<input type="email" name="email" required maxlength="100"></label>
        <label>Senha (mín. 6 caracteres)
            <input type="password" name="senha" required minlength="6"></label>
        <label>Tipo de usuário
            <select name="tipo" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </label>
        <label>Telefone<input type="text" name="telefone" maxlength="15"></label>
        <button>Salvar usuário</button></form>

<h1>Usuários cadastrados</h1>
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Criado em</th>
        </tr>
    </thead>
    <button>Login</button>
    <tbody><?php foreach ($usuarios as $u): ?><tr>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['criado_em']) ?></td>
            </tr><?php endforeach ?></tbody>