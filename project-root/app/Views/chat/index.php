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

                <label class="file-btn">
                    <i class="fa fa-paperclip"></i>
                    <input type="file" id="fileInput" hidden>
                </label>

                <input id="mensaje" placeholder="Escribe un mensaje">

                <button onclick="enviar()">Enviar</button>

            </div>
            <div id="filePreview" class="file-preview" style="display:none;">
                <div id="previewContent"></div>

                <div class="preview-actions">
                    <button id="cancelPreview">Cancelar</button>
                    <button id="sendPreview">Enviar</button>
                </div>
            </div>

            <div id="uploadProgress" class="upload-progress" style="display:none;">
                <div class="upload-bar"></div>
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

</body>

</html>