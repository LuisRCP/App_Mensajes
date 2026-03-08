<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Messenger MVC</title>

<link rel="stylesheet" href="/css/chat.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

<div class="sidebar-header">
Chats
</div>

<div class="search-box">
<input placeholder="Buscar chat...">
</div>

<div id="usuarios" class="chat-list"></div>

</div>


<!-- CHAT -->

<div class="chat-area">

<div class="chat-header">
<span id="chatUser">Selecciona un usuario</span>
<a href="/logout">Cerrar sesión</a>
</div>

<div id="messages" class="messages"></div>

<div id="typing" class="typing"></div>

<div id="chatInput" class="chat-input">
<input id="mensaje" placeholder="Escribe un mensaje">
<button onclick="enviar()">Enviar</button>
</div>

</div>

</div>

<script>
const usuarioId = <?= $usuarioId ?? 0 ?>;
</script>

<script src="/js/api.js"></script>
<script src="/js/chat.js"></script>
<script src="/js/chat-ui.js"></script>

</body>
</html>