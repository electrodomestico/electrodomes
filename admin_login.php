<?php
session_start();
include 'includes/admin_auth.php'; // Incluye la lógica de autenticación

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_panel.php'); // Redirige al panel si es exitoso
        exit();
    } else {
        $message = "Usuario o contraseña incorrectos.";
        $message_type = "error";
    }
}

// Mostrar mensajes de flash si existen (ej. desde requireAdminLogin())
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador - Mi Electrotienda</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos específicos para el login */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Asegura que el footer esté abajo */
        }
        main {
            flex-grow: 1; /* Permite que el contenido principal ocupe el espacio restante */
            display: flex;
            align-items: center; /* Centra verticalmente */
            justify-content: center; /* Centra horizontalmente */
            padding: 20px; /* Añade padding para móviles */
        }
        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%; /* Para que ocupe el ancho disponible en móviles */
            text-align: center;
        }
        .login-container h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2em;
        }
        .login-container .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 22px); /* Ajusta padding y borde */
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.1em;
            box-sizing: border-box;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #e60023;
            outline: none;
            box-shadow: 0 0 0 2px rgba(230, 0, 35, 0.2);
        }
        .login-container .button {
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .login-logo {
            margin-bottom: 20px;
        }
        .login-logo img {
            max-width: 150px; /* Ajusta el tamaño de tu logo si lo pones */
            height: auto;
            border-radius: 50%; /* Si quieres que sea circular */
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="navbar container">
            <a href="index.php" class="logo">MI TIENDA</a>
            <ul class="nav-links">
                <li><a href="index.php">PRODUCTOS</a></li>
                <li><a href="cart.php">CARRITO (<?php echo count($_SESSION['cart'] ?? []); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="login-container">
            <div class="login-logo">
                <img src="images/admin_logo.jpg" alt="Admin Logo Placeholder">
            </div>
            <h2>Acceso de Administrador</h2>
            <?php if ($message): ?>
                <div class="flash-message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="admin_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="button primary">Entrar</button>
            </form>
        </div>
    </main>

    <footer class="main-footer">
        <p>&copy; <?php echo date("Y"); ?> Mi Electrotienda. Todos los derechos reservados.</p>
    </footer>
</body>
</html>