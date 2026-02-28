const receptorId = 2;
let ultimoMensajeId = 0;

async function cargarConversacion() {

    const data = await apiFetch(`/chat/conversation/${receptorId}`);

    const box = document.getElementById("messages");
    box.innerHTML = "";

    data.mensajes.forEach(m => {
        agregarMensaje(m);
        ultimoMensajeId = m.mensajeId;
    });
}

function agregarMensaje(m) {
    const div = document.createElement("div");
    div.innerText = m.emisor_id + ": " + m.mensaje_contenido;
    document.getElementById("messages").appendChild(div);
}

async function enviar() {

    const texto = document.getElementById("mensaje").value;

    await apiFetch("/chat/send", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            receptor_id: receptorId,
            mensaje: texto
        })
    });

    document.getElementById("mensaje").value = "";
}

setInterval(cargarConversacion, 2000);

cargarConversacion();