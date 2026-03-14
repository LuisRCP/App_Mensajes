<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Messenger MVC</title>

    <link rel="stylesheet" href="/css/chat.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/MotionPathPlugin.min.js"></script>

</head>

<body>

    <div class="container">

        <!-- OVERLAY PARA MOVIL -->
        <div class="overlay" onclick="toggleChats()"></div>


        <!-- SIDEBAR / LISTA DE CHATS -->

        <div class="sidebar">

            <div class="sidebar-header">
                Chats
            </div>

            <div class="search-box">
                <input placeholder="Buscar chat...">
            </div>

            <!-- LISTA DE USUARIOS -->
            <div id="usuarios" class="chat-list"></div>

        </div>


        <!-- AREA DEL CHAT -->

        <div class="chat-area">

            <!-- HEADER DEL CHAT -->

            <div class="chat-header">

                <div class="chat-user">

                    <button class="menu-btn" onclick="toggleChats()">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="chat-user-info">

                        <img id="chatAvatar" src="/img/default-avatar.jpg" class="avatar-header">

                        <span id="chatUser">Selecciona un usuario</span>

                    </div>

                </div>

                <!-- TU PERFIL -->

                <div class="profile-area">

                    <img id="myAvatar" src="/img/default-avatar.png" class="avatar-header profile-avatar">

                    <input type="file" id="avatarInput" accept="image/*" capture="environment" hidden>

                </div>

            </div>


            <!-- MENSAJES -->

            <div id="messages" class="messages"></div>


            <!-- ESTADO VACIO -->

            <div id="emptyState" class="empty-state">

                <svg id="planeScene" viewBox="0 0 800 400"></svg>

                <p>Selecciona un chat para comenzar a conversar</p>

            </div>


            <!-- INDICADOR ESCRIBIENDO -->

            <div id="typing" class="typing"></div>


            <!-- INPUT CHAT -->

            <div id="chatInput" class="chat-input" style="display:none;">

                <label class="file-btn">
                    <i class="fa fa-paperclip"></i>
                    <input type="file" id="fileInput" hidden>
                </label>

                <input id="mensaje" placeholder="Escribe un mensaje">

                <button onclick="enviar()">
                    <i class="fa fa-paper-plane"></i>
                </button>

            </div>


            <!-- PREVIEW ARCHIVO -->

            <div id="filePreview" class="file-preview" style="display:none;">

                <div id="previewContent"></div>

                <div class="preview-actions">

                    <button id="cancelPreview">
                        Cancelar
                    </button>

                    <button id="sendPreview">
                        Enviar
                    </button>

                </div>

            </div>


            <!-- PROGRESO SUBIDA -->

            <div id="uploadProgress" class="upload-progress" style="display:none;">
                <div class="upload-bar"></div>
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