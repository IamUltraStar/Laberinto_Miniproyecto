const laberintoElement = document.getElementById('laberinto');
const mensajeElement = document.getElementById('mensaje');
const cronometroElement = document.getElementById('cronometro-output');
const worklaberintoElement = document.getElementById('work-laberinto');
const titlegameElement = document.getElementById('title-game');

let niveles;
let posJugador;
let laberintoActual = 0;
let intervalo;

window.addEventListener("beforeunload", (event) => {
    actualizarNivel();
});

async function cargarLaberintos() {
    try {
        const response = await fetch("../static/laberintos.json");
        const data = await response.json();
        return data.niveles;
    } catch (error) {
        console.error('Error al cargar los laberintos:', error);
        mensajeElement.textContent = 'Error al cargar el laberinto. Inténtalo nuevamente.';
    }
}

cargarLaberintos().then(data => {
    niveles = data;
    crearLaberinto();
});

function limpiarLaberinto() {
    while (laberintoElement.firstChild) {
        laberintoElement.removeChild(laberintoElement.firstChild);
    }
}

function crearLaberinto() {
    if (!niveles[nivelActual] || !niveles[nivelActual].laberintos[laberintoActual]) {
        mensajeElement.textContent = 'Nivel no disponible.';
        return;
    }

    limpiarLaberinto();
    laberintoElement.style.gridTemplateColumns = `repeat(${nivelActual+5},40px)`;

    (niveles[nivelActual]).laberintos[laberintoActual].forEach((row, rowIndex) => {
        row.forEach((cell, colIndex) => {
            const cellElement = document.createElement('div');
            cellElement.classList.add('cell');

            if (cell === 1) {
                cellElement.classList.add('wall');
            } else if (cell === 2) {
                cellElement.classList.add('start');
            } else if (cell === 3) {
                cellElement.classList.add('exit');
            }

            laberintoElement.appendChild(cellElement);
        });
    });

    posJugador = {x: (niveles[nivelActual]).posX, y: (niveles[nivelActual]).posY}
    dibujarJugador();
}

function dibujarJugador() {
    const cells = document.querySelectorAll('.cell');
    cells.forEach(cell => cell.classList.remove('player'));
    const ubicarJugador = posJugador.y * ((niveles[nivelActual]).laberintos[laberintoActual]).length + posJugador.x;
    cells[ubicarJugador].classList.add('player');
}

function moverJugador(dx, dy) {
    const newX = posJugador.x + dx;
    const newY = posJugador.y + dy;
    const maxLimit = ((niveles[nivelActual]).laberintos[laberintoActual]).length;

    if (newX !== -1 && newY !== -1 && newX !== maxLimit && newY !== maxLimit) {
        if (((niveles[nivelActual]).laberintos[laberintoActual])[newY][newX] !== 1 ) {
            posJugador.x = newX;
            posJugador.y = newY;
            dibujarJugador();
            checkExit();
        }
    }
}

function checkExit() {
    if (((niveles[nivelActual]).laberintos[laberintoActual])[posJugador.y][posJugador.x] === 3) {
        clearInterval(intervalo);
        mensajeElement.textContent = '¡Has salido del laberinto!';
        document.removeEventListener('keydown', manejarMovimiento);
        worklaberintoElement.textContent = "Siguiente Nivel";
        worklaberintoElement.disabled = false;
        worklaberintoElement.onclick = cargarSiguienteNivel;
        worklaberintoElement.focus()
    }
}

function iniciarCronometro() {
    document.addEventListener('keydown', manejarMovimiento);
    worklaberintoElement.disabled = true;
    intervalo = setInterval(() => {
        tiempo++;
        const horas = Math.floor(tiempo / 3600);
        const minutos = Math.floor((tiempo % 3600) / 60);
        const segundos = tiempo % 60;
        cronometroElement.textContent = `Cronómetro: ${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
    }, 1000);
}

function cargarSiguienteNivel() {
    if (laberintoActual !== 4) {
        laberintoActual++;
    }else if (nivelActual !== 4) {
        nivelActual++;
        laberintoActual = 0;
        puntajeActual += 3000;
        document.getElementById("perfil-puntaje").textContent = puntajeActual.toString();
        actualizarNivel();
    }else{
        mensajeElement.textContent = "En estos momentos no hay mas niveles disponibles.";
        return;
    }
    titlegameElement.textContent = `Juego del Laberinto: Nivel ${nivelActual + 1} - Laberinto N°${laberintoActual+1}`;
    crearLaberinto();
    worklaberintoElement.textContent = "Iniciar";
    worklaberintoElement.onclick = iniciarCronometro;
    mensajeElement.textContent = "";
}

function actualizarNivel() {
    const formData = new FormData();
    formData.append('nivel', nivelActual);
    formData.append('tiempo_jugado', tiempo);
    formData.append('puntaje', puntajeActual);

    navigator.sendBeacon('../actions/actualizar_nivel_usuario.php', formData);
}

function manejarMovimiento(event) {
    switch (event.key) {
        case 'ArrowUp':
            event.preventDefault();
            moverJugador(0, -1);
            break;
        case 'ArrowDown':
            event.preventDefault();
            moverJugador(0, 1);
            break;
        case 'ArrowLeft':
            moverJugador(-1, 0);
            break;
        case 'ArrowRight':
            moverJugador(1, 0);
            break;
    }
}

function openDialogEditDataUser() {
    document.getElementById("dialogEditDataUser").show();
    document.body.style.overflow = "hidden";
}

function closeDialogEditDataUser() {
    document.body.style.overflow = "visible";
}
