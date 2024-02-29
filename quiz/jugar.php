<?php
session_start();

//Si el usuario no esta logeado lo enviamos al index
if (!$_SESSION['usuario']) {
    header("Location:index.php");
}


include("../admin/funciones.php");

$confi = obtenerConfiguracion();
$totalPreguntasPorJuego = $confi['totalPreguntas'];

//Variables que controlan la partida


if (isset($_GET['siguiente'])) { //Ya esta jugando
    //Aumento 1 en las estadísticas
    aumentarRespondidas();

    //Controlar si la respuesta esta bien
    if ($_SESSION['respuesta_correcta'] == $_GET['respuesta']) {
        $_SESSION['correctas'] = $_SESSION['correctas'] + 1;
    }


    $_SESSION['numPreguntaActual'] = $_SESSION['numPreguntaActual'] + 1;
    if ($_SESSION['numPreguntaActual'] < ($totalPreguntasPorJuego)) {
        $preguntaActual = obtenerPreguntaPorId($_SESSION['idPreguntas'][$_SESSION['numPreguntaActual']]);
        $_SESSION['respuesta_correcta'] = $preguntaActual['correcta'];
    } else {
        //Lo enviamos al pagina de los resultados
        //Calculo la cantidad de respuestas incorrectas y lo guardo en una variable global
        $_SESSION['incorrectas'] = $totalPreguntasPorJuego - $_SESSION['correctas'];
        //Obetengo el nombre de la categoria y lo ponogo en una variable global
        $_SESSION['nombreCategoria'] = obtenerNombreTema($_SESSION['idCategoria']);
        $_SESSION['score'] = ($_SESSION['correctas'] * 100) / $totalPreguntasPorJuego;
        header("Location: final.php");
    }
} else { //comenzó a jugar
    $_SESSION['correctas'] = 0;
    $_SESSION['numPreguntaActual'] = 0;
    $_SESSION['preguntas'] = obtenerIdsPreguntasPorCategoria($_SESSION['idCategoria']);
    $_SESSION['idPreguntas'] = array();

    foreach ($_SESSION['preguntas'] as $idPregunta) {
        array_push($_SESSION['idPreguntas'], $idPregunta['id']); // Item agregado
    }

    //Desordeno el arreglo
    shuffle($_SESSION['idPreguntas']);
    $preguntaActual = obtenerPreguntaPorId($_SESSION['idPreguntas'][0]);
    $_SESSION['respuesta_correcta'] = $preguntaActual['correcta'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LESSONS</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <!-- Contenedor principal del juego -->
    <div class="container-juego" id="container-juego">
        <!-- Encabezado del juego -->
        <header class="header">
            <!-- Nombre de la categoría de la pregunta actual -->
            <div class="categoria">
                <?php echo obtenerNombreTema($preguntaActual['tema']) ?>
            </div>
            <!-- Enlace para regresar al índice -->
            <a href="index.php">Back</a>
        </header>
        <!-- Información de la pregunta actual -->
        <div class="info">
            <!-- Estado de la pregunta actual -->
            <div class="estadoPregunta">
                Pregunta <span class="numPregunta"><?php echo $_SESSION['numPreguntaActual'] + 1 ?></span> de <?php echo $totalPreguntasPorJuego ?>
            </div>
            <!-- Enunciado de la pregunta actual -->
            <h3>
                <?php echo html_entity_decode($preguntaActual['pregunta']); ?>
            </h3>
            <!-- Formulario para seleccionar la respuesta -->
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <!-- Opciones de respuesta -->
                <div class="opciones">
                    <!-- Opción A -->
                    <label for="respuesta1" onclick="seleccionar(this)" class="op1">
                        <?php echo html_entity_decode($preguntaActual['opcion_a']); ?>
                        <input type="radio" name="respuesta" value="A" id="respuesta1" required>
                    </label>
                    <!-- Opción B -->
                    <label for="respuesta2" onclick="seleccionar(this)" class="op2">
                        <?php echo html_entity_decode($preguntaActual['opcion_b']); ?>
                        <input type="radio" name="respuesta" value="B" id="respuesta2" required>
                    </label>
                    <!-- Opción C -->
                    <label for="respuesta3" onclick="seleccionar(this)" class="op3">
                        <?php echo html_entity_decode($preguntaActual['opcion_c']); ?>
                        <input type="radio" name="respuesta" value="C" id="respuesta3" required>
                    </label>
                </div>
                <!-- Botón para avanzar a la siguiente pregunta -->
                <div class="boton">
                    <input type="submit" value="Siguiente" name="siguiente">
                </div>
            </form>
        </div>
    </div>
    <!-- Script JavaScript para el juego -->
    <script src="juego.js"></script>
</body>

</html>