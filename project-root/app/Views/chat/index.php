<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Messenger MVC</title>

<link rel="stylesheet" href="/css/chat.css">

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="sidebar-header">
            <h2>Chats</h2>
        </div>

        <div class="search-box">
            <input placeholder="Buscar chat...">
        </div>

        <div id="usuarios" class="chat-list"></div>

    </div>


    <!-- CHAT AREA -->
    <div class="chat-area">

        <div class="chat-header">
            <span id="chatUser">Selecciona un usuario</span>
            <a href="/logout">Cerrar sesión</a>
        </div>

        <div id="messages" class="messages"></div>

        <div class="chat-input">
            <input id="mensaje" placeholder="Escribe un mensaje">
            <button onclick="enviar()">Enviar</button>
        </div>

    </div>

</div>

<script>
const usuarioId = <?= $usuarioId ?>;
</script>

<script src="/js/api.js"></script>
<script src="/js/chat.js"></script>

</body>
</html>