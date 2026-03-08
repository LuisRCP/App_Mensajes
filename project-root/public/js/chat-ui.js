/* ANIMACIONES */

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

const chatInput = document.querySelector(".chat-input");

if(chatInput){
gsap.from(chatInput,{
y:40,
opacity:0,
delay:.8
});
}

/* INDICADOR ESCRIBIENDO */

let typingTimer;
const mensajeInput = document.getElementById("mensaje");


if(mensajeInput){

mensajeInput.addEventListener("input",()=>{

document.getElementById("typing").innerText="Escribiendo..."

clearTimeout(typingTimer)

typingTimer=setTimeout(()=>{
document.getElementById("typing").innerText=""
},1000)

})

}


/* ANIMAR MENSAJES */

const observer = new MutationObserver(()=>{

const mensajes = document.querySelectorAll(".msg")

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