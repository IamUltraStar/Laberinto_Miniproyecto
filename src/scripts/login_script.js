const loginBody = document.getElementById("login-body");
const registerBody = document.getElementById("register-body");
const mainContainer = document.querySelector(".main-container");

function changeMainBody() {
    loginBody.classList.toggle("hidden");
    registerBody.classList.toggle("hidden");
    mainContainer.classList.toggle("mainContainerWidth-700");
    mainContainer.classList.toggle("mainContainerWidth-1000");
}

function removeErrorParam() {
    const url = new URL(window.location);
    url.searchParams.delete('error'); // Elimina el parámetro 'error' de la URL
    window.history.replaceState({}, document.title, url); // Actualiza la URL sin recargar
}
