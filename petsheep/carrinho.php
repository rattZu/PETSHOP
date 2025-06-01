<?php
session_start(); // Inicia a sessão

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "petsheep";

    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario_id'])) {
        echo "Usuário não está logado.";
        exit();
    }

    $usuario_id = $_SESSION['usuario_id']; // Obtém o ID do usuário logado
    $data = json_decode(file_get_contents("php://input"), true);
    $date = date("Y-m-d H:i:s");

    // Obtém o endereço enviado pelo formulário
    $endereco = $conn->real_escape_string($data["endereco"]);

    foreach ($data["produtos"] as $produto) {
        $nome = $conn->real_escape_string($produto["nome"]);
        $preco = floatval($produto["preco"]);
        $quantidade = intval($produto["quantidade"]);
        $total = floatval($produto["total"]);

        // Insere os dados na tabela pedidos, incluindo o ID do usuário e o endereço
        $sql = "INSERT INTO pedidos (usuario_id, produto, preco, quantidade, total, data_pedido, endereco) 
                VALUES ($usuario_id, '$nome', $preco, $quantidade, $total, '$date', '$endereco')";
        $conn->query($sql);
    }

    echo "Pedidos salvos com sucesso!";
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title>Carrinho de Compras</title>
  
  <!-- Bootstrap Icons e CSS -->
  <link href="css/carrinho.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/main.css" rel="stylesheet" />
  <link href="img/giaicon.jpg" rel="icon" />
  
 
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 py-lg-0 px-3 px-lg-0">
    <a href="index.html" class="navbar-brand ms-lg-5">
      <h1 class="m-0 text-uppercase text-dark">
        <i class="flaticon-dog fs-1 text-primary me-3"></i>Pet Shop
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

        <a href="login.php" class="nav-item nav-link bg-primary text-white px-5 ms-lg-5">Login <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>
  </nav>

  <header class="hero-header">
    <div class="container py-5">
      <h1 class="display-5 text-uppercase mb-0">Carrinho de Compras</h1>
    </div>
  </header>

  <main class="main-section container">
    <section>
      <h2 class="section-title">Produtos</h2>
      <div class="products-container" id="products-list">
        <!-- Produtos serão inseridos via JS -->
      </div>
    </section>

    <section>
      <h2 class="section-title">Carrinho</h2>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Item</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="cart-body">
          <!-- Itens do carrinho aqui -->
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
            <td colspan="2" id="cart-total">R$0,00</td>
          </tr>
        </tfoot>
      </table>

      <!-- Campo de endereço -->
      <div class="address-container">
        <label for="endereco" class="form-label">Endereço de entrega:</label>
        <input type="text" id="endereco" class="form-control" placeholder="Digite seu endereço completo" required />
      </div>

      <button class="purchase-button" id="finalizar-compra">Finalizar Compra</button>
    </section>
  </main>

  <script>
    // Lista dos produtos disponíveis
    const produtos = [
      {
        nome: "pedigree vital pro 10kg",
        preco: 165.00,
        img: "img/product-1.png"
      },
      {
        nome: "Ração Megazoo para Hamster 360g",
        preco: 28.00,
        img: "img/hampter.png"
      },
      {
        nome: "Wiskas (sabor carne) 10kg",
        preco: 140.00,
        img: "img/product-2.png"
      }
    ];

    // Carrinho em memória (array de objetos)
    let carrinho = [];

    // Função para formatar preço em R$
    function formatarPreco(valor) {
      return "R$" + valor.toFixed(2).replace(".", ",");
    }

    // Renderizar lista de produtos
    function renderizarProdutos() {
      const container = document.getElementById("products-list");
      container.innerHTML = "";
      produtos.forEach(prod => {
        const div = document.createElement("div");
        div.classList.add("movie-product");
        div.innerHTML = `
          <strong class="product-title">${prod.nome}</strong>
          <img src="${prod.img}" alt="${prod.nome}" class="product-image" />
          <div class="product-price-container">
            <span class="product-price">${formatarPreco(prod.preco)}</span>
            <button type="button" class="button-hover-background" data-nome="${prod.nome}">Adicionar ao carrinho</button>
          </div>
        `;
        container.appendChild(div);
      });
    }

    // Renderizar carrinho na tabela
    function renderizarCarrinho() {
      const tbody = document.getElementById("cart-body");
      tbody.innerHTML = "";
      let totalGeral = 0;

      carrinho.forEach((item, index) => {
        const totalItem = item.preco * item.quantidade;
        totalGeral += totalItem;

        const tr = document.createElement("tr");
        tr.classList.add("cart-product");
        tr.innerHTML = `
          <td>
            <img src="${item.img}" alt="${item.nome}" class="cart-product-image" />
            <strong>${item.nome}</strong>
          </td>
          <td>${formatarPreco(item.preco)}</td>
          <td>
            <input type="number" min="1" class="product-qtd-input" data-index="${index}" value="${item.quantidade}" />
          </td>
          <td>${formatarPreco(totalItem)}</td>
          <td>
            <button class="remove-product-button" data-index="${index}">Remover</button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      document.getElementById("cart-total").innerText = formatarPreco(totalGeral);

      // Adicionar eventos nos inputs de quantidade
      document.querySelectorAll(".product-qtd-input").forEach(input => {
        input.addEventListener("change", e => {
          const idx = e.target.getAttribute("data-index");
          let val = parseInt(e.target.value);
          if (isNaN(val) || val < 1) val = 1;
          e.target.value = val;
          carrinho[idx].quantidade = val;
          renderizarCarrinho();
        });
      });

      // Adicionar evento nos botões remover
      document.querySelectorAll(".remove-product-button").forEach(button => {
        button.addEventListener("click", e => {
          const idx = e.target.getAttribute("data-index");
          carrinho.splice(idx, 1);
          renderizarCarrinho();
        });
      });
    }

    // Adicionar produto ao carrinho
    function adicionarAoCarrinho(nome) {
      const produto = produtos.find(p => p.nome === nome);
      if (!produto) return;

      const itemNoCarrinho = carrinho.find(item => item.nome === nome);
      if (itemNoCarrinho) {
        itemNoCarrinho.quantidade++;
      } else {
        carrinho.push({ ...produto, quantidade: 1 });
      }

      renderizarCarrinho();
    }

    // Enviar pedido para o backend
    document.getElementById("finalizar-compra").addEventListener("click", () => {
      if (carrinho.length === 0) {
        alert("Carrinho vazio!");
        return;
      }

      // Preparar dados para enviar
      const produtosEnvio = carrinho.map(item => ({
        nome: item.nome,
        preco: item.preco,
        quantidade: item.quantidade,
        total: parseFloat((item.preco * item.quantidade).toFixed(2))
      }));

      // Obter o endereço do input
      const endereco = document.getElementById("endereco").value.trim();
      if (!endereco) {
        alert("Por favor, digite o endereço de entrega.");
        return;
      }

      fetch("", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ produtos: produtosEnvio, endereco: endereco })
      })
      .then(res => res.text())
      .then(res => {
        alert("Pedido feito com sucesso!");
        carrinho = [];
        document.getElementById("endereco").value = ""; // Limpar campo de endereço
        renderizarCarrinho();
      })
      .catch(err => {
        alert("Erro ao enviar pedido.");
        console.error(err);
      });
    });

    // Eventos para botões "Adicionar ao carrinho"
    document.addEventListener("click", e => {
      if (e.target.classList.contains("button-hover-background")) {
        const nome = e.target.getAttribute("data-nome");
        adicionarAoCarrinho(nome);
      }
    });

    // Inicialização
    renderizarProdutos();
    renderizarCarrinho();
  </script>
  <!-- Ícone do site -->
  <link href="img/giaicon.jpg" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Ícones personalizados -->
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
    <!-- Estilos do Bootstrap -->
    <link href="css/min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="css/style.css" rel="stylesheet">

</body>
<div class="product-specs">
  <ul class="tabs">
          <!-- Contatos -->
          <div class="col-lg-3 col-md-6">
            <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Entre em Contato</h5>
            <p class="mb-2">
              <i class="bi bi-geo text-primary me-2"></i>Rua 123, Recife, PE, Brasil
            </p>
            <p class="mb-2">
              <i class="bi bi-envelope-open text-primary me-2"></i>info@caomediapetshop.com
            </p>
            <p class="mb-0">
              <i class="bi bi-telephone text-primary me-2"></i>+99 99999999
            </p>
          </div>
          <!-- Links Rápidos -->
          <div class="col-lg-3 col-md-6">
            <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Links Rápidos</h5>
            <div class="d-flex flex-column justify-content-start">
              <a class="text-body mb-2" href="index.html">
                <i class="bi bi-arrow-right text-primary me-2"></i>Início </a>
            </div>
          </div>
       <!-- Redes Sociais -->
<div class="col-lg-3 col-md-6">
  <div class="d-flex justify-content-end">
    <a class="btn btn-outline-primary btn-square me-2" href="https://twitter.com/ZimRattu">
      <i class="bi bi-twitter"></i>
    </a>
    <a class="btn btn-outline-primary btn-square me-2" href="https://github.com/rattzu">
      <i class="bi bi-github"></i>
    </a>
    <a class="btn btn-outline-primary btn-square" href="https://instagram.com/alysu._">
      <i class="bi bi-instagram"></i>
    </a>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </ul>
<!-- Botão de Carrinho Fixo -->
<a href="carrinho.php" class="btn btn-primary btn-lg btn-square fixed-cart">
  <i class="bi bi-cart"></i>
</a>
</div>
</main>
  
      <!-- Rodapé -->
      <footer class="container-fluid bg-dark text-light mt-5 pt-5">
        <div class="container text-center py-4">
          <p class="mb-0">&copy; <a href="#" class="text-primary">Cãomédia Pet Shop</a>. Todos os direitos reservados.</p>
        </div>
      </footer>
</html>


