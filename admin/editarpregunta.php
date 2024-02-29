<?php
session_start();

//Si el usuario no esta logeado lo enviamos al login
if (!$_SESSION['usuarioLogeado']) {
    header("Location:login.php");
}

include("funciones.php");

/******************************************************* */
//ACTUALIZAMOSS LA PREGUNTA
if (isset($_POST['actualizar'])) {
    //nos conectamos a la base de datos
    include("conexion.php");

    //tomamos los datos que vienen del formulario
    $id_pregunta = $_POST['idPregunta'];
    $id_tema = $_POST['tema'];
    $pregunta = htmlspecialchars($_POST['pregunta']);
    $opcion_a = htmlspecialchars($_POST['opcion_a']);
    $opcion_b = htmlspecialchars($_POST['opcion_b']);
    $opcion_c = htmlspecialchars($_POST['opcion_c']);
    $correcta = $_POST['correcta'];

    //Armamos el query para insertar en la tabla preguntas
    $query = "UPDATE preguntas SET tema='$id_tema', pregunta='$pregunta', opcion_a='$opcion_a', opcion_b='$opcion_b', opcion_c='$opcion_c', correcta = '$correcta' WHERE id='$id_pregunta'";

    //actualizamos en la tabla preguntas
    if (mysqli_query($conn, $query)) { //Se insertó correctamente
        $mensaje = "La pregunta se actulizo correctamente";
        header("Location: listadopreguntas.php");
    } else {
        $mensaje = "No se pudo insertar en la BD" . mysqli_error($conn);
    }
}

//Selecciono la pregunta que viene por GET
$id = $_GET['idPregunta'];
$pregunta = obtenerPreguntaPorId($id);

//Se presióno el botón Nuevo Tema
if (isset($_POST['nuevoTema'])) {
    //tomamos los datos que vienen del formulario
    $tema = $_POST['nombreTema'];
    $mensaje = agregarNuevoTema($tema);
    header("Location: nuevapregunta.php");
}

//Obtengo todos los temas de la bd
$resltado_temas = obetenerTodosLosTemas();


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Importación del editor de texto Tiny-->
    <script src="https://cdn.tiny.cloud/1/uvkdbothloxga9nbuar6a16ksibhka35mct1ci9kctdmjt05/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <link rel="stylesheet" href="estilo.css">
    <title>Modificar</title>
</head>

<body>
    <div class="contenedor">
        <header>
            <h1>Modificar</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?> <!-- Incluye el archivo "nav.php" en esta posición -->
            <div class="panel">
                <h2>Modificar Pregunta</h2>
                <hr>
                <section id="nuevaPregunta">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <!-- Campo oculto para almacenar el ID de la pregunta -->
                        <input type="hidden" name="idPregunta" value="<?php echo $pregunta['id'] ?>">
                        <!-- Selección del tema de la pregunta -->
                        <div class="fila">
                            <label for="">Tema: </label>
                            <select name="tema" id="tema">
                                <!-- Loop para mostrar las opciones de temas -->
                                <?php while ($row = mysqli_fetch_assoc($resltado_temas)) : ?>
                                    <!-- Si el tema es el mismo que el de la pregunta, se marca como seleccionado -->
                                    <?php if ($row['id'] == $pregunta['tema']) : ?>
                                        <option value="<?php echo $row['id'] ?>" selected>
                                            <?php echo $row['nombre'] ?>
                                        </option>
                                    <?php else : ?>
                                        <option value="<?php echo $row['id'] ?>">
                                            <?php echo $row['nombre'] ?>
                                        </option>
                                    <?php endif ?>
                                <?php endwhile ?>
                            </select>
                        </div>
                        <!-- Campo para ingresar la pregunta -->
                        <div class="fila">
                            <label for="">Pregunta:</label>
                            <textarea name="pregunta" id="preg" cols="30" rows="10"><?php echo $pregunta['pregunta'] ?></textarea>
                        </div>
                        <!-- Opciones de respuesta -->
                        <div class="opciones">
                            <div class="opcion">
                                <label for="">Opcion A</label>
                                <textarea name="opcion_a" id="opciona"> <?php echo $pregunta['opcion_a'] ?> </textarea>
                            </div>
                            <div class="opcion">
                                <label for="">Opcion B</label>
                                <textarea name="opcion_b" id="opcionb"><?php echo $pregunta['opcion_b'] ?> </textarea>
                            </div>
                            <div class="opcion">
                                <label for="">Opcion C</label>
                                <textarea name="opcion_c" id="opcionc"> <?php echo $pregunta['opcion_c'] ?> </textarea>
                            </div>
                        </div>
                        <!-- Selección de la respuesta correcta -->
                        <div class="opcion">
                            <label for="">Correcta</label>
                            <select name="correcta" id="" class="correcta">
                                <!-- Opciones para seleccionar la respuesta correcta -->
                                <option value="A" <?php if ($pregunta['correcta'] == 'A') {
                                                        echo "selected";
                                                    } ?>>A</option>
                                <option value="B" <?php if ($pregunta['correcta'] == 'B') {
                                                        echo "selected";
                                                    } ?>>B</option>
                                <option value="C" <?php if ($pregunta['correcta'] == 'C') {
                                                        echo "selected";
                                                    } ?>>C</option>
                            </select>
                        </div>
                        <hr>
                        <!-- Botón para actualizar la pregunta -->
                        <input type="submit" value="Actualizar Pregunta" name="actualizar" class="btn-guardar">
                    </form>
                </section>
            </div>
        </div>
    </div>


    <script>
    tinymce.init({
        selector: 'textarea#preg',
        plugins: 'image media code',
        toolbar: 'undo redo | link image media | code',
        /* Habilitar campo de título en el diálogo de imagen */
        image_title: true,
        media_title: true,
        /* Habilitar cargas automáticas de imágenes representadas por blob o URI de datos */
        automatic_uploads: true,
        /*
          URL de nuestro controlador de carga (para más detalles consultar: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          aquí agregamos un selector de archivos personalizado solo para el diálogo de imagen
        */
        file_picker_types: 'media,image',
        /* y aquí está nuestro selector de imágenes personalizado */
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*,audio/*');

            /*
              Nota: En los navegadores modernos input[type="file"] funciona sin
              siquiera agregarlo al DOM, pero eso podría no ser el caso en algunos navegadores más antiguos
              o extraños como IE, así que es posible que desee agregarlo al DOM
              por si acaso, y ocultarlo visualmente. Y no olvide eliminarlo
              una vez que no lo necesite más.
            */

            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function() {
                    /*
                      Nota: Ahora necesitamos registrar el blob en el caché de blobs de la imagen de TinyMCE
                      registry. En la próxima versión, esta parte esperamos que no sea
                      necesario, ya que estamos buscando manejarlo internamente.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* llamar al callback y llenar el campo de título con el nombre del archivo */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    tinymce.init({
        selector: 'textarea#opciona',
        plugins: 'image media code',
        toolbar: 'undo redo | link image media | code',
        /* Habilitar campo de título en el diálogo de imagen */
        image_title: true,
        media_title: true,
        /* Habilitar cargas automáticas de imágenes representadas por blob o URI de datos */
        automatic_uploads: true,
        /*
          URL de nuestro controlador de carga (para más detalles consultar: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          aquí agregamos un selector de archivos personalizado solo para el diálogo de imagen
        */
        file_picker_types: 'media,image',
        /* y aquí está nuestro selector de imágenes personalizado */
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*,audio/*');

            /*
              Nota: En los navegadores modernos input[type="file"] funciona sin
              siquiera agregarlo al DOM, pero eso podría no ser el caso en algunos navegadores más antiguos
              o extraños como IE, así que es posible que desee agregarlo al DOM
              por si acaso, y ocultarlo visualmente. Y no olvide eliminarlo
              una vez que no lo necesite más.
            */

            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function() {
                    /*
                      Nota: Ahora necesitamos registrar el blob en el caché de blobs de la imagen de TinyMCE
                      registry. En la próxima versión, esta parte esperamos que no sea
                      necesario, ya que estamos buscando manejarlo internamente.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* llamar al callback y llenar el campo de título con el nombre del archivo */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    tinymce.init({
        selector: 'textarea#opcionb',
        plugins: 'image media code',
        toolbar: 'undo redo | link image media | code',
        /* Habilitar campo de título en el diálogo de imagen */
        image_title: true,
        media_title: true,
        /* Habilitar cargas automáticas de imágenes representadas por blob o URI de datos */
        automatic_uploads: true,
        /*
          URL de nuestro controlador de carga (para más detalles consultar: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          aquí agregamos un selector de archivos personalizado solo para el diálogo de imagen
        */
        file_picker_types: 'media,image',
        /* y aquí está nuestro selector de imágenes personalizado */
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*,audio/*');

            /*
              Nota: En los navegadores modernos input[type="file"] funciona sin
              siquiera agregarlo al DOM, pero eso podría no ser el caso en algunos navegadores más antiguos
              o extraños como IE, así que es posible que desee agregarlo al DOM
              por si acaso, y ocultarlo visualmente. Y no olvide eliminarlo
              una vez que no lo necesite más.
            */

            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function() {
                    /*
                      Nota: Ahora necesitamos registrar el blob en el caché de blobs de la imagen de TinyMCE
                      registry. En la próxima versión, esta parte esperamos que no sea
                      necesario, ya que estamos buscando manejarlo internamente.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* llamar al callback y llenar el campo de título con el nombre del archivo */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    tinymce.init({
        selector: 'textarea#opcionc',
        plugins: 'image media code',
        toolbar: 'undo redo | link image media | code',
        /* Habilitar campo de título en el diálogo de imagen */
        image_title: true,
        media_title: true,
        /* Habilitar cargas automáticas de imágenes representadas por blob o URI de datos */
        automatic_uploads: true,
        /*
          URL de nuestro controlador de carga (para más detalles consultar: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          aquí agregamos un selector de archivos personalizado solo para el diálogo de imagen
        */
        file_picker_types: 'media,image',
        /* y aquí está nuestro selector de imágenes personalizado */
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*,audio/*');

            /*
              Nota: En los navegadores modernos input[type="file"] funciona sin
              siquiera agregarlo al DOM, pero eso podría no ser el caso en algunos navegadores más antiguos
              o extraños como IE, así que es posible que desee agregarlo al DOM
              por si acaso, y ocultarlo visualmente. Y no olvide eliminarlo
              una vez que no lo necesite más.
            */

            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function() {
                    /*
                      Nota: Ahora necesitamos registrar el blob en el caché de blobs de la imagen de TinyMCE
                      registry. En la próxima versión, esta parte esperamos que no sea
                      necesario, ya que estamos buscando manejarlo internamente.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* llamar al callback y llenar el campo de título con el nombre del archivo */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>





    <!-- Ventana Modal para nuevo Tema -->
    <div id="modalTema" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" onclick="cerrarTema()">&times;</span>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <label for="">Agregar Nuevo Tema</label>
                <input type="text" name="nombreTema" required>
                <input type="submit" name="nuevoTema" value="Guardar Tema" class="btn">
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>