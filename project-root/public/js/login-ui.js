gsap.from("#container",{
duration:1,
scale:0.8,
opacity:0
})

function mostrarRegistro(){

document.getElementById("loginBox").classList.add("hidden")
document.getElementById("registroBox").classList.remove("hidden")

}

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