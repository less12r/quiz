<?php
session_start();

// Si el usuario no está logeado lo enviamos al login
if (!$_SESSION['usuarioLogeado']) {
    header("Location: login.php");
}

include("funciones.php");

// Obtenemos el total de preguntas
$totalPreguntas = obtenerTotalPreguntas();

// Obtenemos las categorías
$categorias = obtenerCategorias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="estilo.css">
    <title>DASHBOARD</title>
</head>

<body>
    <div class="contenedor">
        <header>
            <h1>Dashboard</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?>
            <div class="panel">
                <h2>Dashboard</h2>
                <hr>
                <div id="dashboard">
                    <!-- Mostramos el total de preguntas -->
                    <div class="card gradiente3">
                        <span class="tema">Total</span>
                        <span class="cantidad"><?php echo $totalPreguntas?></span>
                        <span> Preguntas</span>
                    </div>
                    <!-- Mostramos las preguntas por categoría -->
                    <?php while ($cat = mysqli_fetch_assoc($categorias)):?>
                    <div class="card gradiente1">
                        <span class="tema"><?php echo obtenerNombreTema($cat['tema']);?></span>
                        <span class="cantidad"> <?php echo totalPreguntasPorCategoria($cat['tema']);?></span>
                        <span> Preguntas</span>
                    </div>
                    <?php endwhile ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Script personalizado -->
    <script src="script.js"></script>
    <!-- Marcamos la página activa -->
    <script>paginaActiva(0);</script>   
</body>
</html>



