<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Messenger - Login</title>

<link rel="stylesheet" href="/css/login.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

</head>

<body>

<canvas id="particles"></canvas>

<div class="container" id="container">

<h2>Messenger</h2>

<!-- LOGIN -->

<div id="loginBox">

<input id="correo" placeholder="Correo">
<input id="password" type="password" placeholder="Contraseña">

<button onclick="login()">Entrar</button>

<div id="msg" class="msg"></div>


<div class="switch" onclick="mostrarRegistro()">
Crear cuenta
</div>

</div>


<!-- REGISTRO -->

<div id="registroBox" class="hidden">

<input id="nombre" placeholder="Nombre">
<input id="apellido_paterno" placeholder="Apellido paterno">
<input id="apellido_materno" placeholder="Apellido materno">
<input id="correo_reg" placeholder="Correo">

<button onclick="registrar()">Registrarse</button>

<div id="msg" class="msg"></div>


<div class="switch" onclick="mostrarLogin()">
Ya tengo cuenta
</div>

</div>

</div>

<script src="/js/api.js"></script>
<script src="/js/login.js"></script>
<script src="/js/login-ui.js"></script>

</body>
</html>