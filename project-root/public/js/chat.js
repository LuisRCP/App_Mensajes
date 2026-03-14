let receptorId = null;
let ultimoMensajeId = 0;
const IA_ID = 3;
let archivoPendiente = null;

async function cargarConversaciones() {
  const data = await apiFetch("/chat/conversaciones");

  const box = document.getElementById("usuarios");

  if (!box) return;

  box.innerHTML = "";

  data.conversaciones.forEach((c) => {
    const div = document.createElement("div");
    div.className = "usuario";

    let ultimo = "Sin mensajes";

    if (c.mensaje_contenido) {
      ultimo = c.mensaje_contenido;
    } else if (c.tipo === "imagen") {
      ultimo = "📷 Imagen";
    } else if (c.tipo === "video") {
      ultimo = "🎥 Video";
    } else if (c.tipo === "audio") {
      ultimo = "🎧 Audio";
    } else if (c.tipo === "archivo") {
      ultimo = "📎 Archivo";
    }

    const avatar = c.usuario_avatar
      ? "/" + c.usuario_avatar
      : "/img/default-avatar.jpg";

    div.innerHTML = `
    <div class="avatar">
        <img src="${avatar}">
    </div>

    <div class="usuario-info">
        <b>${c.persona_Nombre} ${c.persona_ApellidoPaterno}</b>
        <small>${ultimo}</small>
    </div>
`;

    div.onclick = () => {
      receptorId = c.usuarioId;

      const empty = document.getElementById("emptyState");
      if (empty) empty.style.display = "none";

      ultimoMensajeId = 0;

      const messages = document.getElementById("messages");
      if (messages) messages.innerHTML = "";

      const chatUser = document.getElementById("chatUser");
      if (chatUser) {
        chatUser.innerText = c.persona_Nombre + " " + c.persona_ApellidoPaterno;
      }

      const avatarHeader = document.getElementById("chatAvatar");

      if (avatarHeader) {
        avatarHeader.src = c.usuario_avatar
          ? "/" + c.usuario_avatar
          : "/img/default-avatar.jpg";
      }

      document.querySelectorAll(".usuario").forEach((u) => {
        u.classList.remove("activo");
      });

      div.classList.add("activo");

      const chatInput = document.getElementById("chatInput");
      if (chatInput) chatInput.style.display = "flex";

      /* cerrar menú en móvil */

      const container = document.querySelector(".container");
      if (container) container.classList.remove("menu-open");

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

    if (!box) return;

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
    contenido = `
      <video controls class="chat-video">
        <source src="/${m.ruta_archivo}">
      </video>
    `;
  } else if (m.tipo === "audio") {
    contenido = `
      <audio controls>
        <source src="/${m.ruta_archivo}">
      </audio>
    `;
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

  if (!box) return;

  box.appendChild(div);

  box.scrollTo({
    top: box.scrollHeight,
    behavior: "smooth",
  });
}

async function enviar() {
  const input = document.getElementById("mensaje");

  if (!input) return;

  const texto = input.value;

  if (!texto || !receptorId) return;

  input.value = "";

  if (receptorId == IA_ID) {
    const typing = document.getElementById("typing");

    if (typing) {
      typing.innerText = "Asistente IA está escribiendo...";
    }
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

  const typing = document.getElementById("typing");
  if (typing) typing.innerText = "";

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

  if (progressBox) progressBox.style.display = "block";
  if (bar) bar.style.width = "0%";

  const xhr = new XMLHttpRequest();

  xhr.open("POST", "/api/chat/sendFile");

  xhr.upload.onprogress = function (e) {
    if (e.lengthComputable && bar) {
      const percent = (e.loaded / e.total) * 100;

      bar.style.width = percent + "%";
    }
  };

  xhr.onload = function () {
    if (progressBox) progressBox.style.display = "none";

    cargarConversacion();
    cargarConversaciones();
  };

  xhr.onerror = function () {
    if (progressBox) progressBox.style.display = "none";

    alert("Error subiendo archivo");
  };

  xhr.send(formData);
}

const mensajeInput = document.getElementById("mensaje");

if (mensajeInput) {
  mensajeInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      enviar();
    }
  });
}

const fileInput = document.getElementById("fileInput");

function mostrarPreview(file) {
  const box = document.getElementById("filePreview");
  const content = document.getElementById("previewContent");

  if (!box || !content) return;

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

const cancelPreview = document.getElementById("cancelPreview");

if (cancelPreview) {
  cancelPreview.onclick = function () {
    archivoPendiente = null;

    document.getElementById("filePreview").style.display = "none";
  };
}

const sendPreview = document.getElementById("sendPreview");

if (sendPreview) {
  sendPreview.onclick = function () {
    if (!archivoPendiente) return;

    subirArchivo(archivoPendiente);

    archivoPendiente = null;

    document.getElementById("filePreview").style.display = "none";
  };
}

const avatarBtn = document.getElementById("myAvatar");
const avatarInput = document.getElementById("avatarInput");

if (avatarBtn) {

  avatarBtn.onclick = () => {
    avatarInput.click();
  };

}

if (avatarInput) {

  avatarInput.addEventListener("change", async function(){

    if(!this.files.length) return;

    const file = this.files[0];

    const formData = new FormData();
    formData.append("avatar", file);

    const res = await fetch("/api/chat/subirAvatar",{
      method:"POST",
      body:formData
    });

    const data = await res.json();

    if(data.success){

      const url = "/" + data.avatar + "?t=" + Date.now();

      document.getElementById("myAvatar").src = url;

    }

  });

}

setInterval(() => {
  if (receptorId) {
    cargarConversacion();
  }
}, 2000);

cargarConversaciones();
cargarConversacion();
