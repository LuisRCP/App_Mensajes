let receptorId = null;
let ultimoMensajeId = 0;
const IA_ID = 3;
let archivoPendiente = null;

async function cargarConversaciones() {
  const data = await apiFetch("/chat/conversaciones");

  const box = document.getElementById("usuarios");

  box.innerHTML = "";

  data.conversaciones.forEach((c) => {
    const div = document.createElement("div");

    div.className = "usuario";

    let ultimo = "Sin mensajes";

    if (c.mensaje_contenido) {
      ultimo = c.mensaje_contenido;
    }
    else if (c.tipo === "imagen") {
      ultimo = "📷 Imagen";
    }
    else if (c.tipo === "video") {
      ultimo = "🎥 Video";
    }
    else if (c.tipo === "audio") {
      ultimo = "🎧 Audio";
    }
    else if (c.tipo === "archivo") {
      ultimo = "📎 Archivo";
    }
    
    div.innerHTML = `
            <strong>${c.persona_Nombre} ${c.persona_ApellidoPaterno}</strong>
            <br>
            <small>${ultimo}</small>
        `;

    div.onclick = () => {
      receptorId = c.usuarioId;
      document.getElementById("emptyState").style.display = "none";

      ultimoMensajeId = 0;
      document.getElementById("messages").innerHTML = "";

      document.getElementById("chatUser").innerText =
        c.persona_Nombre + " " + c.persona_ApellidoPaterno;

      document.querySelectorAll(".usuario").forEach((u) => {
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

async function cargarConversacion() {
  if (!receptorId) return;

  try {
    const data = await apiFetch(`/chat/conversation/${receptorId}`);

    if (!data.mensajes) return;

    const box = document.getElementById("messages");

    data.mensajes.forEach((m) => {
      if (m.mensajeId > ultimoMensajeId) {
        agregarMensaje(m);

        ultimoMensajeId = m.mensajeId;
      }
    });
  } catch (e) {
    console.log("Error cargando conversación", e);
  }
}

function agregarMensaje(m) {
  const div = document.createElement("div");

  div.classList.add("msg");

  // mensaje propio
  if (m.emisor_id == usuarioId) {
    div.classList.add("me");
  }

  const fecha = new Date(m.fecha_enviado);

  const hora = fecha.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  });

  let contenido = "";

  if (m.tipo === "imagen") {
    contenido = `<img src="/${m.ruta_archivo}" class="chat-img">`;
  } else if (m.tipo === "video") {
    contenido = `<video controls class="chat-video">
                 <source src="/${m.ruta_archivo}">
               </video>`;
  } else if (m.tipo === "audio") {
    contenido = `<audio controls>
                 <source src="/${m.ruta_archivo}">
               </audio>`;
  } else if (m.tipo === "archivo") {
    const nombre = m.nombre_original ?? "archivo";

    contenido = `<a href="/${m.ruta_archivo}" target="_blank">📎 ${nombre}</a>`;
  } else {
    contenido = document.createTextNode(m.mensaje_contenido).textContent;
  }

  div.innerHTML = `
        <span>
            ${contenido}
            <div class="msg-time">${hora}</div>
        </span>
    `;

  const box = document.getElementById("messages");

  box.appendChild(div);

  box.scrollTo({
    top: box.scrollHeight,
    behavior: "smooth",
  });
}

async function enviar() {
  const texto = document.getElementById("mensaje").value;

  if (!texto || !receptorId) return;

  document.getElementById("mensaje").value = "";

  // mostrar indicador de IA escribiendo
  if (receptorId == IA_ID) {
    document.getElementById("typing").innerText =
      "Asistente IA está escribiendo...";
  }

  await apiFetch("/chat/send", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      receptor_id: receptorId,
      mensaje: texto,
    }),
  });

  // quitar indicador
  document.getElementById("typing").innerText = "";

  cargarConversacion();
  cargarConversaciones();
}

async function subirArchivo(file) {
  if (!file || !receptorId) return;

  const formData = new FormData();

  formData.append("archivo", file);
  formData.append("receptor_id", receptorId);

  const progressBox = document.getElementById("uploadProgress");
  const bar = document.querySelector(".upload-bar");

  progressBox.style.display = "block";
  bar.style.width = "0%";

  const xhr = new XMLHttpRequest();

  xhr.open("POST", "/api/chat/sendFile");

  xhr.upload.onprogress = function (e) {
    if (e.lengthComputable) {
      const percent = (e.loaded / e.total) * 100;

      bar.style.width = percent + "%";
    }
  };

  xhr.onload = function () {
    progressBox.style.display = "none";

    cargarConversacion();
    cargarConversaciones();
  };
  xhr.onerror = function () {
    progressBox.style.display = "none";
    alert("Error subiendo archivo");
  };

  xhr.send(formData);
}

document.getElementById("mensaje").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    enviar();
  }
});

const fileInput = document.getElementById("fileInput");
function mostrarPreview(file) {
  const box = document.getElementById("filePreview");
  const content = document.getElementById("previewContent");

  box.style.display = "block";

  content.innerHTML = "";

  if (file.type.startsWith("image/")) {
    const img = document.createElement("img");

    img.src = URL.createObjectURL(file);

    content.appendChild(img);
  } else {
    const p = document.createElement("p");

    p.innerText = "📎 " + file.name;

    content.appendChild(p);
  }
}
if (fileInput) {
  fileInput.addEventListener("change", function () {
    if (!this.files.length) return;

    archivoPendiente = this.files[0];

    mostrarPreview(archivoPendiente);
  });
}
document.getElementById("cancelPreview").onclick = function () {
  archivoPendiente = null;

  document.getElementById("filePreview").style.display = "none";
};
document.getElementById("sendPreview").onclick = function () {
  if (!archivoPendiente) return;

  subirArchivo(archivoPendiente);

  archivoPendiente = null;

  document.getElementById("filePreview").style.display = "none";
};
setInterval(() => {
  if (receptorId) {
    cargarConversacion();
  }
}, 2000);

cargarConversaciones();
cargarConversacion();
