// Variable global para almacenar el ID de la pregunta a eliminar
var idPreguntaEliminar;

// Función para mostrar el modal de agregar tema
function agregarTema() {
    modalTema = document.getElementById("modalTema");
    modalTema.style.display = "block";
}

// Función para cerrar el modal de agregar tema
function cerrarTema() {
    modalTema = document.getElementById("modalTema");
    modalTema.style.display = "none";
}

// Función para cerrar el modal de eliminar pregunta
function cerrarEliminar() {
    modalPregunta = document.getElementById("modalPregunta");
    modalPregunta.style.display = "none";
}

// Redirige a la página de edición de pregunta con el ID de la pregunta seleccionada
function editarPregunta(idPregunta) {
    window.location.href = "editarpregunta.php?idPregunta=" + idPregunta;
}

// Redirige a la página de eliminación de pregunta con el ID de la pregunta seleccionada
function eliminarPregunta() {
    window.location.href = "eliminarpregunta.php?idPregunta=" + idPreguntaEliminar;
}

// Muestra el modal de confirmación para eliminar una pregunta, almacenando el ID de la pregunta
function abrirModalEliminar(idPregunta) {
    idPreguntaEliminar = idPregunta;
    modalPregunta = document.getElementById("modalPregunta");
    modalPregunta.style.display = "block";
}

// Función para marcar la página activa en la barra de navegación
function paginaActiva(id) {
    var paginas = document.querySelectorAll(".icono");
    for (i = 0; i < paginas.length; i++) {
        paginas[i].className = "icono";
    }
    paginas[id].className = "icono selected";
}
