<?php
$erro = ""; // Inicializa a variável de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $conn = new mysqli('localhost', 'root', '', 'petsheep');

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];

    // Valida os campos
    if (empty($usuario) || empty($email) || empty($senha) || empty($confirmar)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } elseif ($senha !== $confirmar) {
        $erro = "As senhas não coincidem.";
    } else {
        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere os dados no banco de dados usando prepared statement
        $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $usuario, $email, $senhaHash);

        if ($stmt->execute()) {
            header("Location: login.php"); // Redireciona para a página de login
            exit();
        } else {
            $erro = "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro</title>
  <link rel="stylesheet" href="css/login.css">
  <link href="img/giaicon.jpg" rel="icon">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 px-3">
    <a href="index.html" class="navbar-brand">
      <h1 class="m-0 text-uppercase text-dark"><i class="flaticon-dog fs-1 text-primary me-3"></i>Cãomédia Pet Shop</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav ms-auto">
        <a href="index.html" class="nav-item nav-link active">Início</a>
        <a href="produtos.html" class="nav-item nav-link">Produtos</a>
        <a href="login.php" class="nav-item nav-link">Entrar</a>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="text-center">Cadastro</h2>
    <?php if (!empty($erro)): ?>
      <p class="text-danger text-center"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>
    <form method="POST" action="cadastro.php" class="mx-auto" style="max-width: 400px;">
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuário</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
        <input type="password" name="confirmar_senha" class="form-control" required>
      </div>
      <button type="submit" name="submit" class="btn btn-success w-100">Cadastrar</button>
      <div class="mt-3 text-center">
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
