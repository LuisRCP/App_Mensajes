let receptorId = null;
let ultimoMensajeId = 0;
const IA_ID = 3;

async function cargarConversaciones(){

    const data = await apiFetch("/chat/conversaciones");

    const box = document.getElementById("usuarios");

    box.innerHTML = "";

    data.conversaciones.forEach(c => {

        const div = document.createElement("div");

        div.className = "usuario";

        const ultimo = c.mensaje_contenido ? c.mensaje_contenido : "Sin mensajes";

        div.innerHTML = `
            <strong>${c.persona_Nombre} ${c.persona_ApellidoPaterno}</strong>
            <br>
            <small>${ultimo}</small>
        `;

        div.onclick = () => {

            receptorId = c.usuarioId;

            document.getElementById("chatUser").innerText =
                c.persona_Nombre + " " + c.persona_ApellidoPaterno;

            document.querySelectorAll(".usuario").forEach(u=>{
                u.classList.remove("activo");
            });

            div.classList.add("activo");

            // mostrar barra de mensaje
            document.getElementById("chatInput").style.display = "flex";

            cargarConversacion();

        };

        box.appendChild(div);

    });

}

async function cargarConversacion(){

    if(!receptorId) return;

    try{

        const data = await apiFetch(`/chat/conversation/${receptorId}`);

        if(!data.mensajes) return;

        const box = document.getElementById("messages");

        box.innerHTML = "";

        data.mensajes.forEach(m => {

            agregarMensaje(m);

            ultimoMensajeId = m.mensajeId;

        });

        box.scrollTop = box.scrollHeight;

    }catch(e){
        console.log("Error cargando conversación", e);
    }

}

function agregarMensaje(m){

    const div = document.createElement("div");

    div.classList.add("msg");

    // mensaje propio
    if(m.emisor_id == usuarioId){
        div.classList.add("me");
    }

    const fecha = new Date(m.fecha_enviado);

    const hora = fecha.toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });

    let contenido = "";

    if(m.tipo === "imagen"){

        contenido = `<img src="/${m.ruta_archivo}" class="chat-img">`;

    }else{

        contenido = m.mensaje_contenido;

    }

    div.innerHTML = `
        <span>
            ${contenido}
            <div class="msg-time">${hora}</div>
        </span>
    `;

    const box = document.getElementById("messages");

    box.appendChild(div);

    box.scrollTop = box.scrollHeight;

}

async function enviar(){

    const texto = document.getElementById("mensaje").value;

    if(!texto || !receptorId) return;

    document.getElementById("mensaje").value = "";

    // mostrar indicador de IA escribiendo
    if(receptorId == IA_ID){
        document.getElementById("typing").innerText =
            "Asistente IA está escribiendo...";
    }

    await apiFetch("/chat/send",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            receptor_id: receptorId,
            mensaje: texto
        })
    });

    // quitar indicador
    document.getElementById("typing").innerText = "";

    cargarConversacion();
    cargarConversaciones();

}

async function enviarImagen(){

    const fileInput = document.getElementById("imagen");

    if(!fileInput.files.length || !receptorId) return;

    const formData = new FormData();

    formData.append("imagen", fileInput.files[0]);
    formData.append("receptor_id", receptorId);

    await fetch("/api/chat/send-image",{
        method:"POST",
        body:formData
    });

    fileInput.value = "";

    cargarConversacion();
    cargarConversaciones();

}

document
.getElementById("mensaje")
.addEventListener("keypress", function(e){

    if(e.key === "Enter"){
        enviar();
    }

});

const imagenInput = document.getElementById("imagen");

if(imagenInput){

    imagenInput.addEventListener("change", function(){
        enviarImagen();
    });

}
setInterval(()=>{
    if(receptorId){
        cargarConversacion();
    }
},2000);

cargarConversaciones();
cargarConversacion();