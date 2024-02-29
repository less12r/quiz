<?php
session_start();

//Si el usuario no esta logeado lo enviamos al login
if (!$_SESSION['usuarioLogeado']) {
    header("Location:login.php");
}

include("funciones.php");

//Obtengo todos los temas de la bd
$resultado_preguntas = obetenerTodasLasPreguntas();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="estilo.css">
    <title>Lista de preguntas</title>
</head>
<body>
    <div class="contenedor">
        <header>
            <h1>Listado de Preguntas</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?> <!-- Se incluye la barra de navegación -->
            <div class="panel">
                <h2>Listado de Preguntas</h2>
                <hr>
                <section id="listadoPreguntas">
                    <!-- Se recorren todas las preguntas obtenidas de la base de datos -->
                    <?php while ($row = mysqli_fetch_assoc($resultado_preguntas)) : ?>
                        <div class="contenedor-pregunta">
                            <header>
                                <span class="tema"><?php echo obtenerNombreTema($row['tema'])?></span> <!-- Se obtiene y muestra el nombre del tema -->
                                <div class="opciones">
                                    <!-- Ícono para editar la pregunta -->
                                    <i class="fa-solid fa-pen-to-square" onclick="editarPregunta(<?php echo $row['id']?>)"></i>
                                    <!-- Ícono para abrir el modal de eliminación de pregunta -->
                                    <i class="fa-solid fa-trash"" onclick="abrirModalEliminar(<?php echo $row['id']?>)"></i>
                                </div>
                            </header>
                            <p class="pregunta"><?php echo html_entity_decode($row['pregunta']);?></p> <!-- Se muestra la pregunta -->
                            <!-- Se muestran las opciones de respuesta -->
                            <div class="opcion">
                                <div class="caja <?php if($row['correcta']=='A'){ echo 'pintarVerde';}?>">A</div>
                                <span class="texto"><?php echo html_entity_decode($row['opcion_a']);?></span>
                            </div>
                            <div class="opcion">
                                <span class="caja <?php if($row['correcta']=='B'){ echo 'pintarVerde';}?>">B</span>
                                <span class="texto"><?php echo html_entity_decode($row['opcion_b']);?></span>
                            </div>
                            <div class="opcion">
                                <span class="caja <?php if($row['correcta']=='C'){ echo 'pintarVerde';}?>">C</span>
                                <span class="texto"><?php echo html_entity_decode($row['opcion_c']);?></span>
                            </div>
                        </div>
                    <?php endwhile ?>
                </section>
            </div>
        </div>
    </div>
    <!-- Modal para la eliminación de una pregunta -->
    <div id="modalPregunta" class="modal">
        <div class="modal-content">
            <p>¿Está seguro que desea eliminar la pregunta?</p>
            <button onclick="eliminarPregunta()" class="btn">Si</button>
            <button onclick="cerrarEliminar()" class="btn">Cancelar</button>
        </div>
    </div>
    <script src="script.js"></script>
    <script>paginaActiva(2);</script>   
</body>

</html>