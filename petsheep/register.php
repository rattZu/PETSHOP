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
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    // Insere os dados no banco de dados
    $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES ('$usuario', '$email', '$senha')";
    if ($conn->query($sql) === TRUE) {
        // Redireciona para a página de login após o registro bem-sucedido
        header("Location: login.php");
        exit();
    } else {
        echo "Erro ao criar conta: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>