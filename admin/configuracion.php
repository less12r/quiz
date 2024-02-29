<?php
session_start();

//Si el usuario no esta logeado lo enviamos al login
if (!$_SESSION['usuarioLogeado']) {
    header("Location:login.php");
}

include("funciones.php");

$config = obtenerConfiguracion();

/******************************************************* */
//ACTUALIZAMOSS LA CONFIGURACION
if (isset($_POST['actualizar'])) {
    //nos conectamos a la base de datos
    include("conexion.php");

    //tomamos los datos que vienen del formulario
    $usuario = $_GET['usuario'];
    $password = $_GET['password'];
    $totalPreguntas = $_GET['totalPreguntas'];

    //Armamos el query para actualizar en la tabla configuracion
    $query = "UPDATE config SET usuario='$usuario', password='$password', totalPreguntas='$totalPreguntas' WHERE id='1'";

    //actualizamos en la tabla configuracion
    if (mysqli_query($conn, $query)) { //Se actualizo correctamente
        $mensaje = "La configuración se actualizo correctamente";
        header("Location: index.php");
    } else {
        $mensaje = "No se pudo actualizar en la BD" . mysqli_error($conn);
    }
}
//ELIMINAR PREGUNTAS
if (isset($_GET['eliminarPreguntas'])) {
    //nos conectamos a la base de datos
    include("conexion.php");
    //sentiencia para eliminar los datos de la tabla
    $query ="TRUNCATE TABLE preguntas";
    //eliminamos los datos de la tabla preguntas
    if (mysqli_query($conn, $query)) { //Se eliminó correctamente
        $mensaje = "Se eliminaron los datos de la tabla preguntas";
        header("Location: index.php");
    } else {
        $mensaje = "No se pudo eliminar en la BD" . mysqli_error($conn);
    }
}
//ELIMINAMOS LAS PREGUNTAS Y LAS CATEGORIAS
if (isset($_GET['eliminarTodo'])) {
    //nos conectamos a la base de datos
    include("conexion.php");
    //sentiencia para eliminar los datos de la tabla
    $query1 ="TRUNCATE TABLE preguntas";
    $query2 ="TRUNCATE TABLE temas";
    //eliminamos los datos de la tabla preguntas
    if (mysqli_query($conn, $query1)) { //Se eliminó correctamente
        if (mysqli_query($conn, $query2)) { //Se eliminó correctamente
            $mensaje = "Se eliminaron las preguntas y las categorias";
            header("Location: index.php");
        } else {
            $mensaje = "No se pudo eliminar las categorias en la BD" . mysqli_error($conn);
        }
    } else {
        $mensaje = "No se pudo eliminar en la BD" . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="estilo.css">
    <title>Configuracion</title>
</head>
<body>
    <div class="contenedor">
        <header>
            <h1>Configuración</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?> <!-- Incluye el archivo "nav.php" en esta posición -->
            <div class="panel">
                <h2>Configuración del Administrador</h2>
                <hr>
                <section id="configuracion">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                        <!-- Input para el nombre de usuario -->
                        <div class="fila">
                            <label for="">Usuario:</label>
                            <input type="text" name="usuario" id="" value="<?php echo $config['usuario'] ?>" required>
                        </div>
                        <!-- Input para la contraseña -->
                        <div class="fila">
                            <label for="">Password</label>
                            <input type="text" name="password" id="" value="<?php echo $config['password'] ?>" required>
                        </div>
                        <!-- Input para el total de preguntas por juego -->
                        <div class="fila">
                            <label for="">Total Preguntas por Lección</label>
                            <input type="number" name="totalPreguntas" id="" value="<?php echo $config['totalPreguntas'] ?>" required>
                        </div>
                        <hr>
                        <!-- Botón para actualizar la configuración -->
                        <input type="submit" value="Actualizar Configuracion" name="actualizar" class="btn-actualizar">
                    </form>
                </section>

                <h2>Preguntas y Temas</h2>
                <hr>
                <!-- Formulario para eliminar preguntas -->
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" class="form-eliminar">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <!-- Botón para eliminar solo las preguntas -->
                    <input type="submit" value="Eliminar Preguntas" name="eliminarPreguntas" class="btn-eliminar">
                    <!-- Botón para eliminar todas las preguntas y categorías -->
                    <input type="submit" value="Eliminar Preguntas y Temas" name="eliminarTodo" class="btn-eliminar">
                </form>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <script>paginaActiva(3);</script> <!-- Ejecuta la función paginaActiva(3) -->
</body>

</html>