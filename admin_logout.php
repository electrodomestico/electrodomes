<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

$_SESSION['message'] = "Has cerrado sesión como administrador.";
$_SESSION['message_type'] = "info";
header('Location: admin_login.php'); // Redirige al login de administrador
exit();
?>