<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'petsheep';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Obtém o ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// Busca as informações do usuário no banco de dados
$sql = "SELECT usuario, email FROM usuarios WHERE idusuarios = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $nome = $usuario['usuario'];
    $email = $usuario['email'];
} else {
    echo "Usuário não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página do Usuário</title>
  <link href="css/main.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/user.css" rel="stylesheet">
</head>
<body>
   <!-- Barra de navegação -->
   <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 py-lg-0 px-3 px-lg-0">
      <a href="index.html" class="navbar-brand ms-lg-5">
        <h1 class="m-0 text-uppercase text-dark">
        </h1>
      </a>
        <h1 class="m-0 text-uppercase text-dark">
          <i class="flaticon-dog fs-1 text-primary me-3"></i>😂👉🧠🔥
        </h1>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
          <a href="index.html" class="nav-item nav-link active">Início</a>
          <a href="carrinho.php" class="nav-item nav-link">Produtos</a>
          <a href="user.php" class="nav-item nav-link">Usuario</a>
          <div class="nav-item dropdown">
            <div class="dropdown-menu m-0"></div>
          </div>
          <a href="login.php" class="nav-item nav-link nav-contato.html bg-primary text-white px-5 ms-lg-5">Login <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </nav>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h3>
      </div>
      <div class="card-body">
        <p><strong>Usuário:</strong> <?php echo htmlspecialchars($nome); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <!-- Botão de sair -->
        <form action="logout.php" method="post">
    <button type="submit" class="btn btn-danger">Sair</button>
</form>

</body>
</html>