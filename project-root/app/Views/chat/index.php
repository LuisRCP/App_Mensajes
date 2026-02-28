<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
</head>
<body>

<h2>Messenger MVC</h2>

<p>Usuario ID: <?= esc($usuarioId) ?></p>

<a href="/logout">Cerrar sesión</a>

<hr>

<div id="messages"
     style="height:300px;overflow:auto;border:1px solid black"></div>

<br>

<input id="mensaje" placeholder="Escribe mensaje">
<button onclick="enviar()">Enviar</button>

<script>
const usuarioId = <?= $usuarioId ?>;
</script>

<script src="/js/api.js"></script>
<script src="/js/chat.js"></script>

</body>
</html>