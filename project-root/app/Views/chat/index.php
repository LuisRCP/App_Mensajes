<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Messenger MVC</title>

<link rel="stylesheet" href="/css/chat.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>

body{
margin:0;
font-family:Arial;
background:#0b141a;
height:100vh;
overflow:hidden;
}

.container{
display:flex;
height:100vh;
}

/* SIDEBAR */

.sidebar{
width:320px;
background:#111b21;
border-right:1px solid #202c33;
display:flex;
flex-direction:column;
}

.sidebar-header{
padding:20px;
font-size:20px;
font-weight:bold;
}

.search-box{
padding:10px;
}

.search-box input{
width:100%;
padding:10px;
border:none;
border-radius:5px;
background:#202c33;
color:white;
}

.chat-list{
flex:1;
overflow-y:auto;
}

.chat-user{
padding:15px;
border-bottom:1px solid #202c33;
cursor:pointer;
transition:.2s;
}

.chat-user:hover{
background:#1f2c34;
transform:translateX(5px);
}

/* CHAT AREA */

.chat-area{
flex:1;
display:flex;
flex-direction:column;
background:#0b141a;
}

.chat-header{
padding:15px;
background:#202c33;
display:flex;
justify-content:space-between;
align-items:center;
}

.chat-header a{
color:#00a884;
text-decoration:none;
}

/* MENSAJES */

.messages{
flex:1;
overflow-y:auto;
padding:20px;
display:flex;
flex-direction:column;
gap:10px;
}

/* BURBUJAS */

.message{
max-width:60%;
padding:10px 14px;
border-radius:10px;
font-size:14px;
animation:fadeIn .3s ease;
}

.me{
align-self:flex-end;
background:#00a884;
color:white;
}

.other{
align-self:flex-start;
background:#202c33;
color:white;
}

@keyframes fadeIn{
from{
opacity:0;
transform:translateY(10px);
}
to{
opacity:1;
transform:translateY(0);
}
}

/* INPUT */

.chat-input{
padding:15px;
background:#202c33;
display:flex;
gap:10px;
}

.chat-input input{
flex:1;
padding:12px;
border:none;
border-radius:6px;
background:#111b21;
color:white;
}

.chat-input button{
padding:12px 20px;
border:none;
border-radius:6px;
background:#00a884;
color:white;
cursor:pointer;
transition:.2s;
}

.chat-input button:hover{
transform:scale(1.1);
}

/* ESCRIBIENDO */

.typing{
font-size:12px;
color:#aaa;
padding-left:20px;
}

</style>

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

<div class="chat-input">
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

<script>

/* ANIMACION DE ENTRADA */

gsap.from(".sidebar",{
x:-200,
opacity:0,
duration:0.8
})

gsap.from(".chat-area",{
x:200,
opacity:0,
duration:0.8,
delay:.2
})

gsap.from(".sidebar-header",{
y:-20,
opacity:0,
delay:.5
})

gsap.from(".chat-input",{
y:40,
opacity:0,
delay:.8
})

/* ANIMAR MENSAJES NUEVOS */

const observer = new MutationObserver(()=>{

const mensajes = document.querySelectorAll(".message")

if(mensajes.length){

gsap.from(mensajes[mensajes.length-1],{
y:20,
opacity:0,
duration:.3
})

}

})

observer.observe(document.getElementById("messages"),{
childList:true
})

/* ENTER PARA ENVIAR */

document.getElementById("mensaje").addEventListener("keypress",(e)=>{

if(e.key==="Enter"){
enviar()
}

})

/* INDICADOR ESCRIBIENDO */

let typingTimer

document.getElementById("mensaje").addEventListener("input",()=>{

document.getElementById("typing").innerText="Escribiendo..."

clearTimeout(typingTimer)

typingTimer=setTimeout(()=>{
document.getElementById("typing").innerText=""
},1000)

})

</script>

</body>
</html>
