<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Messenger - Login</title>

<<<<<<< HEAD
<link rel="stylesheet" href="/css/login.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

=======
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>

*{
box-sizing:border-box;
margin:0;
padding:0;
font-family:Arial;
}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:#0b141a;
overflow:hidden;
}

/* PARTICULAS */

canvas{
position:absolute;
top:0;
left:0;
}

/* CONTENEDOR */

.container{

width:360px;
padding:35px;

background:#111b21;

border-radius:15px;

box-shadow:
0 20px 40px rgba(0,0,0,0.6),
0 0 30px rgba(0,168,132,0.2);

color:white;

display:flex;
flex-direction:column;
gap:10px;

z-index:2;

}

/* TITULO */

h2{
text-align:center;
margin-bottom:15px;
}

/* INPUTS */

input{

width:100%;
padding:14px;

margin-top:15px;

border:none;
border-radius:8px;

background:#202c33;
color:white;

outline:none;

transition:.3s;
}

input:focus{
box-shadow:0 0 10px #00a884;
}

/* BOTON */

button{

width:100%;
padding:14px;

margin-top:22px;

border:none;
border-radius:8px;

background:#00a884;

color:white;

font-weight:bold;

cursor:pointer;

transition:.3s;
}

button:hover{
transform:scale(1.05);
}

/* SWITCH */

.switch{

text-align:center;
margin-top:18px;

cursor:pointer;

color:#00e1b2;

font-size:16px;
}

.hidden{
display:none;
}

</style>
>>>>>>> 48f5cfbaeb488545812cd315bd0056352d2e3868
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
<<<<<<< HEAD

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
=======

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

</div>

<script>

/* ANIMACION */

gsap.from("#container",{
duration:1,
scale:0.8,
opacity:0
})

/* CAMBIAR A REGISTRO */

function mostrarRegistro(){

document.getElementById("loginBox").classList.add("hidden")
document.getElementById("registroBox").classList.remove("hidden")

}

/* CAMBIAR A LOGIN */

function mostrarLogin(){

document.getElementById("registroBox").classList.add("hidden")
document.getElementById("loginBox").classList.remove("hidden")

}

/* PARTICULAS */

const canvas=document.getElementById("particles")
const ctx=canvas.getContext("2d")

canvas.width=window.innerWidth
canvas.height=window.innerHeight

let particles=[]

for(let i=0;i<50;i++){

particles.push({
x:Math.random()*canvas.width,
y:Math.random()*canvas.height,
size:Math.random()*2,
speedX:(Math.random()-0.5)*0.5,
speedY:(Math.random()-0.5)*0.5
})

}

function animate(){

ctx.clearRect(0,0,canvas.width,canvas.height)

particles.forEach(p=>{

p.x+=p.speedX
p.y+=p.speedY

ctx.fillStyle="#00a884"

ctx.beginPath()
ctx.arc(p.x,p.y,p.size,0,Math.PI*2)
ctx.fill()

})

requestAnimationFrame(animate)

}

animate()

</script>
>>>>>>> 48f5cfbaeb488545812cd315bd0056352d2e3868

</body>
</html>
