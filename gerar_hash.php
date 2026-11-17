<?php
$hash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';

    if ($senha !== '') {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
    }
}
?>

<form method="POST">
    <label>Digite a senha temporária:</label>
    <input type="password" name="senha" required>
    <button type="submit">Gerar hash</button>
</form>

<?php if ($hash !== ''): ?>
    <p>Hash gerado:</p>
    <textarea rows="4" cols="80"><?php echo htmlspecialchars($hash); ?></textarea>
<?php endif; ?>
