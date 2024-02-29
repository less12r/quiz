<?php
    // Inicia una sesión
    session_start();
    
    // Destruye la sesión actual
    session_destroy();
    
    // Redirige al usuario a la página de inicio de sesión (login.php)
    header("Location: login.php");
?>
