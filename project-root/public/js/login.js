async function login(){

    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    const data = await apiFetch("/auth/login",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            correo:correo,
            password:password
        })
    });

    if(data.success){
        window.location.href="index.php/chat";
    }else{
        document.getElementById("msg").innerText=data.message;
    }

}

async function registrar(){

    const nombre = document.getElementById("nombre").value;
    const apellido_paterno = document.getElementById("apellido_paterno").value;
    const apellido_materno = document.getElementById("apellido_materno").value;
    const correo = document.getElementById("correo_reg").value;

    const data = await apiFetch("/auth/register",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            nombre:nombre,
            apellido_paterno:apellido_paterno,
            apellido_materno:apellido_materno,
            correo:correo
        })
    });

    document.getElementById("msg").innerText=data.message;

}