let receptorId = null;
let ultimoMensajeId = 0;

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

    // si el mensaje es mío
    if(m.emisor_id == usuarioId){
        div.classList.add("me");
    }

    div.innerHTML = `<span>${m.mensaje_contenido}</span>`;

    document.getElementById("messages").appendChild(div);

}

async function enviar(){

    const texto = document.getElementById("mensaje").value;

    if(!texto) return;

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

    document.getElementById("mensaje").value = "";

    cargarConversacion();
    cargarConversaciones();

}

setInterval(cargarConversacion,2000);

// cargar primera vez
cargarConversaciones();
cargarConversacion();
