<?php
    // Incluir el archivo de conexión a la base de datos
    include("conexion.php");

    // Obtener el ID de la pregunta a eliminar desde la URL
    $id = $_GET['idPregunta'];

    // Crear la consulta para eliminar la pregunta con el ID especificado
    $query = "DELETE FROM preguntas WHERE id = '$id'";

    // Ejecutar la consulta
    mysqli_query($conn, $query);
?>
<!-- Redireccionar a la página de listado de preguntas después de eliminar -->
<script>
    window.location.href = 'listadopreguntas.php';
</script>
