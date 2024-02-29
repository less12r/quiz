function calcularCalificacion() {
    let puntuacion = 0;

    // Obtener respuestas del formulario
    const respuestas = document.querySelectorAll(".cont_eva .pregunta");

    // Calcular puntuación
    respuestas.forEach(pregunta => {
        const respuestaSeleccionada = pregunta.querySelector('input[name="' + pregunta.id + '"]:checked');

        if (respuestaSeleccionada) {
            const valorRespuesta = respuestaSeleccionada.value;

            // Evaluar respuestas correctas
            if (valorRespuesta === "apple" || valorRespuesta === "cat" || valorRespuesta === "sun") {
                puntuacion++;
            }
        }
    });

    // Mostrar resultado
    const resultadoDiv = document.getElementById("resultado");
    resultadoDiv.innerHTML = "Tu puntuación es: " + puntuacion + " de 3.";
}
