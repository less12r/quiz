<?php
// Iniciamos la sesión
session_start();

// Incluimos el archivo funciones.php que contiene las funciones necesarias
include("../admin/funciones.php");

// Aumentamos el contador de visitas
aumentarVisita();

// Obtenemos las categorías de preguntas
$categorias = obtenerCategorias();

// Si se ha seleccionado una categoría, almacenamos el ID en la sesión y redirigimos a la página de juego
if (isset($_GET['idCategoria'])) {
    session_start();
    $_SESSION['usuario'] = "usuario";
    $_SESSION['idCategoria'] = $_GET['idCategoria'];
    header("Location: jugar.php");
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
    <title>LESSONS</title>

</head>

<body>
    <div class="container" id="cantainer">

        <!-- Encabezado -->
        <header class="header">
            <a href="../index.html" style="color:white; margin-left: 10px; text-decoration:none">Back</a>
        </header>

        <!-- Contenido principal -->
        <div class="left">
            <div class="logo">
                LESSONS
            </div>
            <h2>TEST YOUR KNOWLEDGE</h2>
        </div>
        <div class="right">
            <h3>Choose a theme</h3>
            <div class="categorias">
                <!-- Listamos las categorías -->
                <?php while ($cat = mysqli_fetch_assoc($categorias)) : ?>
                    <div class="categoria">
                        <!-- Formulario para enviar el ID de la categoría seleccionada -->
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="<?php echo $cat['tema'] ?>">
                            <input type="hidden" name="idCategoria" value="<?php echo $cat['tema'] ?>">
                            <a href="javascript:{}" onclick="document.getElementById(<?php echo $cat['tema'] ?>).submit(); return false;">
                                <?php echo obtenerNombreTema($cat['tema']) ?>
                            </a>
                        </form>
                    </div>
                <?php endwhile ?>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="footer">
        <p>© 2024 Lesly Soto</p>
    </footer>

</body>

</html>
