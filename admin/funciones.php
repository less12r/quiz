<?php
//Función para obtener el registro de la configuración del sitio
function obtenerConfiguracion()
{
    include("conexion.php");
    //Comprobamos si existe el registro 1 que mantiene la configuraciòn
    //Añadimos un alias AS total para identificar mas facil
    $query = "SELECT COUNT(*) AS total FROM config";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);


    if ($row['total'] == '0') {
        //No existe el registro 1 - DEBO INSERTAR el registro por primera vez
        $query = "INSERT INTO config (id,usuario,password,totalPreguntas)
        VALUES (NULL, 'admin', 'admin','3')";

        if (mysqli_query($conn, $query)) { //Se insertó correctamente

        } else {
            echo "No se pudo insertar en la BD" .mysqli_errno($conn);
        }
    }

    //Selecciono el registro dela configuración
    $query = "SELECT * FROM config  WHERE id='1'";
    $result = mysqli_query($conn, $query);
    $config = mysqli_fetch_assoc($result);
    return $config;
}

//funcion para agrear un nuevo tema a la BD
function agregarNuevoTema($tema){
    include("conexion.php");
    //armamos el query para insertar en la tabla temas
    $query = "INSERT INTO temas (id, nombre)
    VALUES (NULL, '$tema')";

    //insertamos en la tabla temas
    if (mysqli_query($conn, $query)) { //Se insertó correctamente
        $mensaje = "El fue agregado correctamente";
        header("Location: index.php");
    } else {
        $mensaje = "No se pudo insertar en la BD" . mysqli_errno($conn);
    }
    return $mensaje;
}


// Función para obtener todos los temas
function obetenerTodosLosTemas()
{
    include("conexion.php");
    $query = "SELECT * FROM temas";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Función para obtener el nombre de un tema por su ID
function obtenerNombreTema($id){
    include("conexion.php");
    $query = "SELECT * FROM temas WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $tema = mysqli_fetch_array($result);
    
    return $tema['nombre'];
}

// Función para obtener todas las preguntas
function obetenerTodasLasPreguntas()
{
    include("conexion.php");
    $query = "SELECT * FROM preguntas";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Función para obtener una pregunta por su ID
function obtenerPreguntaPorId($id){
    include("conexion.php");
    $query = "SELECT * FROM preguntas WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $pregunta = mysqli_fetch_array($result);
    return $pregunta;
}

// Función para obtener el total de preguntas
function obtenerTotalPreguntas(){
    include("conexion.php");
    // Añadimos un alias AS total para identificar más fácilmente
    $query = "SELECT COUNT(*) AS total FROM preguntas";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);  
    return $row['total'];
}

// Función para obtener el total de preguntas por categoría
function totalPreguntasPorCategoria($tema){
    include("conexion.php");
    $query = "SELECT COUNT(*) AS total FROM preguntas WHERE tema = '$tema'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);  
    return $row['total'];
}

// Función para obtener las categorías y la cantidad de preguntas por categoría
function obtenerCategorias(){
    include("conexion.php");
    // Contamos la cantidad de cada categoría
    $query = "SELECT tema, COUNT(DISTINCT tema) FROM preguntas GROUP BY tema";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Función para obtener los IDs de preguntas por categoría
function obtenerIdsPreguntasPorCategoria($tema){
    include("conexion.php");
    $query = "SELECT id FROM preguntas WHERE tema = $tema";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Función para aumentar el contador de visitas en las estadísticas
function aumentarVisita(){
    include("conexion.php");
    // Selecciono el registro de la estadística
    $query = "SELECT * FROM estadisticas  WHERE id='1'";
    $result = mysqli_query($conn, $query);
    $estadistica = mysqli_fetch_assoc($result);
    $visitas = $estadistica['visitas'];
    $visitas = $visitas + 1;

    $query = "UPDATE estadisticas SET visitas = '$visitas' WHERE id='1'";
    $result = mysqli_query($conn, $query);
}

// Función para aumentar el contador de preguntas respondidas en las estadísticas
function aumentarRespondidas(){
    include("conexion.php");
    // Selecciono el registro de la estadística
    $query = "SELECT * FROM estadisticas  WHERE id='1'";
    $result = mysqli_query($conn, $query);
    $estadistica = mysqli_fetch_assoc($result);
    $respondidas = $estadistica['respondidas'];
    $respondidas = $respondidas + 1;

    $query = "UPDATE estadisticas SET respondidas = '$respondidas' WHERE id='1'";
    $result = mysqli_query($conn, $query);
}

// Función para aumentar el contador de juegos completados en las estadísticas
function aumentarCompletados(){
    include("conexion.php");
    // Selecciono el registro de la estadística
    $query = "SELECT * FROM estadisticas  WHERE id='1'";
    $result = mysqli_query($conn, $query);
    $estadistica = mysqli_fetch_assoc($result);
    $completados = $estadistica['completados'];
    $completados = $completados + 1;

    $query = "UPDATE estadisticas SET completados = '$completados' WHERE id='1'";
    $result = mysqli_query($conn, $query);
}

