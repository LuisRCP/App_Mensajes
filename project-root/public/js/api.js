const API = "/api";

async function apiFetch(url, options = {}) {

    options.credentials = "include";

    const res = await fetch(API + url, options);

    return await res.json();
}