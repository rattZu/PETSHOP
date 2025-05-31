<?php
if (isset($_POST['submit'])) {
    // Conexão com o banco de dados
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'petsheep';
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se os campos estão preenchidos
    if (empty($usuario) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    // Verifica se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Verifica se o usuário ou e-mail já existe
    $sql_check = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $usuario, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Usuário ou e-mail já cadastrado.";
        $stmt_check->close();
        $conn->close();
        exit;
    }
    $stmt_check->close();

    // Insere os dados no banco de dados
    $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $usuario, $email, $senha_hash);

    if ($stmt->execute()) {
        echo "Conta criada com sucesso!";
        // Redireciona para a página de login
        header("Location: index.html");
        exit;
    } else {
        echo "Erro ao criar conta: " . $stmt->error;
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
}
?>