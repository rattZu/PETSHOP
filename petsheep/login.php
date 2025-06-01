<?php
session_start(); // Inicia a sessão

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Conexão com o banco de dados
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'petsheep';
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $senha = $_POST['senha']; // Não criptografa aqui, pois será comparada com a senha armazenada

    // Consulta SQL para verificar o usuário
    $sql = "SELECT idusuarios, email, senha FROM usuarios WHERE usuario='$usuario'";
    $result = $conn->query($sql);

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifica se a senha fornecida corresponde à senha criptografada no banco
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['usuario_id'] = $row['idusuarios']; // Salva o ID do usuário na sessão
            $_SESSION['email'] = $row['email']; // Salva o email do usuário na sessão
            header("Location: user.php"); // Redireciona para a página do usuário
            exit();
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    } else {
        $erro = "Usuário ou senha inválidos.";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <!-- Link para o arquivo CSS específico da página de login -->
    <link rel="stylesheet" href="css/login.css">
    <!-- Ícone do site -->
    <link href="img/giaicon.jpg" rel="icon">
    <!-- Preconexão para melhorar o desempenho ao carregar fontes -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&amp;family=Roboto:wght@700&amp;display=swap" rel="stylesheet">
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Ícones personalizados -->
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
    <!-- Estilos do Owl Carousel -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Estilos do Bootstrap -->
    <link href="css/main.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Configuração de codificação de caracteres -->
    <meta charset="UTF-8">
    <!-- Título da página -->
    <title>Login</title>
  </head>
  <body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 py-lg-0 px-3 px-lg-0">
      <!-- Link para a página inicial -->
      <a href="index.html" class="navbar-brand ms-lg-5">
        <h1 class="m-0 text-uppercase text-dark">
          <i class="flaticon-dog fs-1 text-primary me-3"></i>Cãomédia Pet Shop
        </h1>
      </a>
      <!-- Botão para expandir/colapsar a barra de navegação em dispositivos móveis -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Links de navegação -->
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
          <a href="index.html" class="nav-item nav-link active">Início</a>
          <a href="contato.html" class="nav-item nav-link">Contato</a>
          <a href="produtos.html" class="nav-item nav-link">Produtos</a>
          <a href="user.php" class="nav-item nav-link">Usuario</a>
          <div class="nav-item dropdown">
            <div class="dropdown-menu m-0">
       
            </div>
          </div>
          <a href="login.php" class="nav-item nav-link nav-contato.html bg-primary text-white px-5 ms-lg-5">Login <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </nav>
    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Container principal da página de login -->
    <div id="container" class="container">
      <div class="row">
        <!-- Formulário de cadastro -->
<div class="form-wrapper align-items-center">
  <form action="register.php" method="POST" class="form sign-up">
    <div class="input-group">
      <i class="bx bxs-user"></i>
      <input type="text" name="usuario" placeholder="Usuário" required>
    </div>
    <div class="input-group">
      <i class="bx bx-mail-send"></i>
      <input type="email" name="email" placeholder="Email" required>
    </div>
    <div class="input-group">
      <i class="bx bxs-lock-alt"></i>
      <input type="password" name="senha" placeholder="Senha" required>
    </div>
    <div class="input-group">
      <i class="bx bxs-lock-alt"></i>
      <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
    </div>
    <button type="submit" name="submit">Criar conta</button>
    <p>
      <span>Já tem uma conta?</span>
      <b onclick="toggle()" class="pointer">Faça login aqui</b>
    </p>
  </form>
</div>
        <!-- Coluna para o formulário de login -->
        <div class="col align-items-center flex-col sign-in">
          <div class="form-wrapper align-items-center">
    <form action="login.php" method="POST" class="form sign-in">
        <h2 class="text-center">Login</h2>
        <?php if (isset($erro)): ?>
            <p class="text-danger text-center"><?php echo $erro; ?></p>
        <?php endif; ?>
        <div class="input-group">
            <i class="bx bxs-user"></i>
            <input type="text" name="usuario" placeholder="Usuário" required>
        </div>
        <div class="input-group">
            <i class="bx bxs-lock-alt"></i>
            <input type="password" name="senha" placeholder="Senha" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Login</button>
        <p>
            <a href="recuperar_senha.php"><b>Esqueceu sua senha?</b></a>
        </p>
        <p>
            <span>Não tem uma conta?</span>
            <b onclick="toggle()" class="pointer">Inscreva-se aqui</b>
        </p>
    </form>
</div>

              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="row content-row">
        <!-- Coluna para o conteúdo de boas-vindas -->
        <div class="col align-items-center flex-col">
          <div class="text sign-in">
            <p style="margin-left: 1px; padding-top: -100px; font-size: 50px;">Bem Vindo! <br>Ao Cãomédia Pet Shop </p>
          </div>
          <div class="img sign-in">
            <!-- Imagem de boas-vindas -->
          </div>
        </div>
        <!-- Coluna para o conteúdo de inscrição -->
        <div class="col align-items-center flex-col">
          <div class="img sign-up">
            <!-- Imagem de inscrição -->
          </div>
          <div class="text sign-up">
            <p style="margin-left: 370px; padding-top: 500px; font-size: 50px;">Seja Um Cliente</p>
          </div>
        </div>
      </div>
      <!-- Script personalizado para a página de login -->
      <script src="js/login.js"></script>
    </div>
  </body>
</html>