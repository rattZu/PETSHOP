// Seleciona o botão do menu, os links de navegação e o ícone do botão
const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

// Adiciona um evento de clique ao botão do menu
menuBtn.addEventListener("click", (e) => {
  // Alterna a classe 'open' no contêiner de links de navegação
  navLinks.classList.toggle("open");

  // Verifica se o menu está aberto e altera o ícone do botão
  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

// Fecha o menu ao clicar em qualquer link dentro dele
navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

// Configurações padrão para animações do ScrollReveal
const scrollRevealOption = {
  distance: "50px", // Distância que o elemento percorre durante a animação
  origin: "bottom", // Origem da animação (de onde o elemento aparece)
  duration: 1000,   // Duração da animação em milissegundos
};

// Aplica animações aos elementos do cabeçalho
ScrollReveal().reveal(".header__content h4", {
  ...scrollRevealOption,
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 500, // Atraso de 500ms antes de iniciar a animação
});
ScrollReveal().reveal(".header__content h2", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__content p", {
  ...scrollRevealOption,
  delay: 1500,
});
ScrollReveal().reveal(".header__btn", {
  ...scrollRevealOption,
  delay: 2000,
});

// Aplica animações aos cartões de introdução
ScrollReveal().reveal(".intro__card", {
  ...scrollRevealOption,
  interval: 500, // Intervalo de 500ms entre as animações de múltiplos elementos
});

// Aplica animações às imagens da seção "Sobre"
ScrollReveal().reveal(
  ".about__row:nth-child(3) .about__image img, .about__row:nth-child(5) .about__image img",
  {
    ...scrollRevealOption,
    origin: "left", // Animação vindo da esquerda
  }
);
ScrollReveal().reveal(".about__row:nth-child(4) .about__image img", {
  ...scrollRevealOption,
  origin: "right", // Animação vindo da direita
});

// Aplica animações ao conteúdo da seção "Sobre"
ScrollReveal().reveal(".about__content span", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".about__content h4", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".about__content p", {
  ...scrollRevealOption,
  delay: 1500,
});

// Aplica animações aos cartões de produtos
ScrollReveal().reveal(".product__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Aplica animações aos cartões de serviços
ScrollReveal().reveal(".service__card", {
  duration: 1000, // Duração personalizada de 1000ms
  interval: 500,
});

// Configura o carrossel de imagens usando a biblioteca Swiper
const swiper = new Swiper(".swiper", {
  slidesPerView: 3,    // Mostra 3 slides por vez
  spaceBetween: 20,   // Espaço de 20px entre os slides
  loop: true,         // Faz o carrossel repetir continuamente
});

// Aplica animações às imagens da grade do Instagram
ScrollReveal().reveal(".instagram__grid img", {
  duration: 1000, // Duração personalizada de 1000ms
  interval: 500,
});

// Variável para armazenar a posição anterior da rolagem
let lastScrollTop = 0;

// Adiciona um evento de rolagem para abrir/fechar a navbar automaticamente
window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

  if (currentScroll < lastScrollTop) {
    // Usuário está subindo a tela: abre a navbar
    navLinks.classList.add("open");
    menuBtnIcon.setAttribute("class", "ri-close-line");
  } else {
    // Usuário está descendo a tela: fecha a navbar
    navLinks.classList.remove("open");
    menuBtnIcon.setAttribute("class", "ri-menu-line");
  }

  // Atualiza a posição anterior da rolagem
  lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Evita valores negativos
});