<?php
session_start();

//Si el usuario no esta logeado lo enviamos al login
if (!$_SESSION['usuarioLogeado']) {
    header("Location:login.php");
}

include("funciones.php");


//Se presióno el botón Nuevo Tema
if (isset($_GET['nuevoTema'])) {
    //tomamos los datos que vienen del formulario
    $tema = $_GET['nombreTema'];
    $mensaje = agregarNuevoTema($tema);
    header("Location: nuevapregunta.php");
}
/* ****************************************************** */
//GUARDAMOS LA PREGUNTA
if (isset($_POST['guardar'])) {
    //nos conectamos a la base de datos
    include("conexion.php");

    //tomamos los datos que vienen del formulario
    // elimina texto con formato de etiqueta html
    $pregunta = htmlspecialchars($_POST['pregunta']);
    $opcion_a = htmlspecialchars($_POST['opcion_a']);
    $opcion_b = htmlspecialchars($_POST['opcion_b']);
    $opcion_c = htmlspecialchars($_POST['opcion_c']);
    $id_tema = $_POST['tema'];
    $correcta = $_POST['correcta'];

    //Armamos el query para insertar en la tabla preguntas
    $query = "INSERT INTO preguntas (id, tema, pregunta, opcion_a, opcion_b, opcion_c, correcta)
    VALUES (NULL, '$id_tema','$pregunta', '$opcion_a','$opcion_b','$opcion_c','$correcta')";

    //insertamos en la tabla preguntas
    if (mysqli_query($conn, $query)) { //Se insertó correctamente
        $mensaje = "";
    } else {
        $mensaje = "No se pudo insertar en la BD" . mysqli_error($conn);
    }
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
    <script src="https://cdn.tiny.cloud/1/uvkdbothloxga9nbuar6a16ksibhka35mct1ci9kctdmjt05/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="estilo.css">
    <title>Nueva Pregunta</title>
</head>

<body>
    <div class="contenedor">
        <header>
            <h1>Nueva Pregunta</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?>
            <div class="panel">
                <h2> Ingrese la Pregunta</h2>
                <hr>
                <section id="nuevaPregunta">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="fila">
                            <label for="">Tema: </label>
                            <select name="tema" id="tema">
                                <?php while ($row = mysqli_fetch_assoc($resltado_temas)) : ?>
                                    <option value="<?php echo $row['id'] ?>">
                                        <?php echo $row['nombre'] ?>
                                    </option>
                                <?php endwhile ?>
                            </select>
                            <span class="agregarTema" onclick="agregarTema()">
                                <i class="fa-solid fa-circle-plus"></i></span>
                        </div>
                        <div class="fila">
                            <label for="">Pregunta:</label>
                            <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
                            <textarea id="pregunta" name="pregunta"></textarea>
                        </div>

                        <div class="opciones">
                            <div class="opcion">
                                <label for="">Opcion A</label>
                                <textarea id="opcion_a" name="opcion_a"></textarea>
                            </div>
                            <div class="opcion">
                                <label for="">Opcion B</label>
                                <textarea id="opcion_b" name="opcion_b"></textarea>
                            </div>
                            <div class="opcion">
                                <label for="">Opcion C</label>
                                <textarea id="opcion_c" name="opcion_c"></textarea>
                            </div>
                        </div>
                        <div class="opcion">
                            <label for="">Correcta</label>
                            <select name="correcta" id="" class="correcta">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>

                        <hr>
                        <input type="submit" value="Guardar Pregunta" name="guardar" class="btn-guardar">
                    </form>

                    <?php if (isset($_POST['guardar'])) : ?>
                        <span> <?php echo $mensaje ?></span>
                    <?php endif ?>
                </section>
            </div>
        </div>
    </div>
    <script>
        tinymce.init({
            selector: 'textarea#pregunta',
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
            selector: 'textarea#opcion_a',
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
            selector: 'textarea#opcion_b',
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
            selector: 'textarea#opcion_c',
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
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <label for="">Agregar Nuevo Tema</label>
                <input type="text" name="nombreTema" required>
                <input type="submit" name="nuevoTema" value="Guardar Tema" class="btn">
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        paginaActiva(1);
    </script>
</body>

</html>