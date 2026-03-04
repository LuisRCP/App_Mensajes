<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Messenger - Login</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#0b141a;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    color:white;
}

.container{
    background:#111b21;
    padding:40px;
    border-radius:10px;
    width:320px;
}

h2{
    text-align:center;
}

input{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:none;
    border-radius:5px;
    background:#202c33;
    color:white;
}

button{
    width:100%;
    padding:10px;
    margin-top:15px;
    border:none;
    border-radius:5px;
    background:#00a884;
    color:white;
    cursor:pointer;
}

.switch{
    text-align:center;
    margin-top:10px;
    cursor:pointer;
    color:#00a884;
}

.hidden{
    display:none;
}

</style>
</head>

<body>

<div class="container">

    <h2>Messenger</h2>

    <!-- LOGIN -->
    <div id="loginBox">

        <input id="correo" placeholder="Correo">
        <input id="password" type="password" placeholder="Contraseña">

        <button onclick="login()">Entrar</button>

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

        <div class="switch" onclick="mostrarLogin()">
            Ya tengo cuenta
        </div>

    </div>

    <p id="msg"></p>

</div>

<script src="/js/api.js"></script>
<script src="/js/login.js"></script>

<script>

function mostrarRegistro(){
    document.getElementById("loginBox").classList.add("hidden");
    document.getElementById("registroBox").classList.remove("hidden");
}

function mostrarLogin(){
    document.getElementById("registroBox").classList.add("hidden");
    document.getElementById("loginBox").classList.remove("hidden");
}

</script>

</body>
</html>