// Función para resaltar la opción seleccionada
function seleccionar(labelSeleccionado) {
    // Obtenemos todos los elementos <label>
    var labels = document.getElementsByTagName("label");
    // Quitamos la clase de selección a todos los elementos <label>
    labels[0].className = "";
    labels[1].className = "";
    labels[2].className = "";
    // Añadimos la clase de selección al elemento <label> seleccionado
    labelSeleccionado.className = "opcionSeleccionada";
}

// Función de inicialización para crear gráficos circulares animados
$(function() {
    // Seleccionamos todos los elementos con la clase 'chart' y creamos un gráfico para cada uno
    $('.chart').easyPieChart({
        size: 160,                // Tamaño del gráfico
        barColor: "#36e617",      // Color de la barra de progreso
        scaleLength: 0,           // Longitud de la escala (0 para desactivarla)
        lineWidth: 15,            // Ancho de la línea de la barra de progreso
        trackColor: "#525151",    // Color de la pista del gráfico
        lineCap: "circle",        // Estilo de la línea de la barra de progreso (circle para un círculo completo)
        animate: 2000,            // Duración de la animación en milisegundos
    });
});
