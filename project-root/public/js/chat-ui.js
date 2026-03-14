gsap.from(".sidebar", {
  x: -200,
  opacity: 0,
  duration: 0.8,
});

gsap.from(".chat-area", {
  x: 200,
  opacity: 0,
  duration: 0.8,
  delay: 0.2,
});

gsap.from(".sidebar-header", {
  y: -20,
  opacity: 0,
  delay: 0.5,
});

const chatInput = document.querySelector(".chat-input");

if (chatInput) {
  gsap.from(chatInput, {
    y: 40,
    opacity: 0,
    delay: 0.8,
  });
}

let typingTimer;

if (mensajeInput) {
  mensajeInput.addEventListener("input", () => {
    const typing = document.getElementById("typing");

    if (!typing) return;

    typing.innerText = "Escribiendo...";

    clearTimeout(typingTimer);

    typingTimer = setTimeout(() => {
      typing.innerText = "";
    }, 1000);
  });
}

const messagesContainer = document.getElementById("messages");

const observer = new MutationObserver(() => {
  const mensajes = document.querySelectorAll(".msg");

  if (mensajes.length) {
    const ultimo = mensajes[mensajes.length - 1];

    gsap.from(ultimo, {
      y: 10,
      scale: 0.95,
      opacity: 0,
      duration: 0.25,
      ease: "power2.out",
    });

    if (messagesContainer) {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
  }
});

if (messagesContainer) {
  observer.observe(messagesContainer, {
    childList: true,
  });
}

gsap.registerPlugin(MotionPathPlugin);

const scene = document.getElementById("planeScene");

if (scene) {
  function crearAvion() {
    const width = scene.clientWidth;
    const height = scene.clientHeight;

    const y1 = Math.random() * height;
    const y2 = Math.random() * height;
    const y3 = Math.random() * height;

    const d = `M -150 ${y1} Q ${width / 2} ${y2} ${width + 150} ${y3}`;

    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

    path.setAttribute("d", d);
    path.setAttribute("fill", "none");

    scene.appendChild(path);

    const plane = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "polygon",
    );

    plane.setAttribute("points", "0,0 18,8 0,16 5,8");
    plane.setAttribute("fill", "#6c8cff");

    scene.appendChild(plane);

    const dur = 10 + Math.random() * 6;

    let lastTrail = 0;

    gsap.to(plane, {
      motionPath: {
        path: path,
        align: path,
        autoRotate: true,
      },

      duration: dur,
      ease: "none",
      repeat: -1,

      onUpdate: function () {
        const now = Date.now();

        if (now - lastTrail < 70) return;

        lastTrail = now;

        const x = gsap.getProperty(plane, "x");
        const y = gsap.getProperty(plane, "y");

        const trail = document.createElementNS(
          "http://www.w3.org/2000/svg",
          "circle",
        );

        trail.setAttribute("cx", x);
        trail.setAttribute("cy", y);
        trail.setAttribute("r", Math.random() * 2 + 1);
        trail.setAttribute("fill", "#6c8cff");
        trail.setAttribute("opacity", "0.7");

        scene.appendChild(trail);

        gsap.to(trail, {
          opacity: 0,
          r: 0.5,
          duration: 4,
          ease: "power1.out",
          onComplete: () => trail.remove(),
        });
      },
    });
  }

  const totalAviones = window.innerWidth < 600 ? 3 : 8;

  for (let i = 0; i < totalAviones; i++) {
    crearAvion();
  }
}

function toggleChats() {
  const container = document.querySelector(".container");

  if (!container) return;

  container.classList.toggle("menu-open");
}

function abrirChat() {
  const container = document.querySelector(".container");

  if (container) {
    container.classList.add("chat-open");
  }
}

function volverChats() {
  const container = document.querySelector(".container");

  if (container) {
    container.classList.remove("chat-open");
  }
}
