<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <title>Messenger MVC</title>

    <link rel="stylesheet" href="/css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/MotionPathPlugin.min.js"></script>


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
            <div id="emptyState" class="empty-state">

            <svg id="planeScene" viewBox="0 0 800 400"></svg>

            <p>Selecciona un chat para comenzar a conversar</p>

            </div>

            <div id="typing" class="typing"></div>

            <div id="chatInput" class="chat-input" style="display:none;">
                <input id="mensaje" placeholder="Escribe un mensaje">
                <button onclick="enviar()">Enviar</button>
            </div>

        </div>

    </div>

    </div>


    <script>
    const usuarioId = <?= $usuarioId ?? 0 ?>;
    </script>

    <script src="/js/api.js"></script>
    <script src="/js/chat.js"></script>
    <script src="/js/chat-ui.js"></script>

    <script>
    /* ANIMACION DE ENTRADA */

    gsap.from(".sidebar", {
        x: -200,
        opacity: 0,
        duration: 0.8
    })

    gsap.from(".chat-area", {
        x: 200,
        opacity: 0,
        duration: 0.8,
        delay: .2
    })

    gsap.from(".sidebar-header", {
        y: -20,
        opacity: 0,
        delay: .5
    })

    gsap.from(".chat-input", {
        y: 40,
        opacity: 0,
        delay: .8
    })

    /* ANIMAR MENSAJES NUEVOS */

    const observer = new MutationObserver(() => {

        const mensajes = document.querySelectorAll(".message")

        if (mensajes.length) {

            gsap.from(mensajes[mensajes.length - 1], {
                y: 20,
                opacity: 0,
                duration: .3
            })

        }

    })

    observer.observe(document.getElementById("messages"), {
        childList: true
    })

    /* ENTER PARA ENVIAR */

    document.getElementById("mensaje").addEventListener("keypress", (e) => {

        if (e.key === "Enter") {
            enviar()
        }

    })

    /* INDICADOR ESCRIBIENDO */

    let typingTimer

    document.getElementById("mensaje").addEventListener("input", () => {

        document.getElementById("typing").innerText = "Escribiendo..."

        clearTimeout(typingTimer)

        typingTimer = setTimeout(() => {
            document.getElementById("typing").innerText = ""
        }, 1000)

    })
    </script>

</body>

</html>