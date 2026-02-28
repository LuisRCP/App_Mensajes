async function login() {

    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    const data = await apiFetch("/auth/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ correo, password })
    });

    if (data.success) {
        window.location.href = "chat";
    } else {
        document.getElementById("msg").innerText = data.message;
    }
}