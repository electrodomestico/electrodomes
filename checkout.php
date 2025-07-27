<?php
session_start();

$cart_items = $_SESSION['cart'] ?? [];
if (empty($cart_items)) {
    $_SESSION['message'] = "Tu carrito está vacío. Añade productos para proceder al pago.";
    $_SESSION['message_type'] = "info";
    header('Location: cart.php'); // Redirige si el carrito está vacío
    exit();
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

// Lista de países (puedes añadir o quitar los que necesites)
$countries = [
    "US" => "Estados Unidos",
    "ES" => "España",
    "MX" => "México",
    "AR" => "Argentina",
    "CO" => "Colombia",
    "CL" => "Chile",
    "PE" => "Perú",
    "VE" => "Venezuela",
    "DO" => "República Dominicana", // Aquí está la RD
    "CA" => "Canadá",
    "GB" => "Reino Unido",
    "FR" => "Francia",
    "DE" => "Alemania",
    "IT" => "Italia",
    "PT" => "Portugal",
    "JP" => "Japón",
    "AU" => "Australia",
    "NZ" => "Nueva Zelanda",
    "BR" => "Brasil",
    "CR" => "Costa Rica",
    "EC" => "Ecuador",
    "GT" => "Guatemala",
    "HN" => "Honduras",
    "NI" => "Nicaragua",
    "PA" => "Panamá",
    "PY" => "Paraguay",
    "UY" => "Uruguay",
    "BO" => "Bolivia",
    "SV" => "El Salvador",
    "CU" => "Cuba",
    "PR" => "Puerto Rico",
    "CH" => "Suiza",
    "BE" => "Bélgica",
    "NL" => "Países Bajos",
    "SE" => "Suecia",
    "NO" => "Noruega",
    "DK" => "Dinamarca",
    "IE" => "Irlanda",
    "FI" => "Finlandia",
    "RU" => "Rusia",
    "CN" => "China",
    "IN" => "India",
    "ID" => "Indonesia",
    "KR" => "Corea del Sur",
    "TH" => "Tailandia",
    "VN" => "Vietnam",
    "PH" => "Filipinas",
    "MY" => "Malasia",
    "SG" => "Singapur",
    "ZA" => "Sudáfrica",
    "EG" => "Egipto",
    // Agrega más países si lo deseas
];

include 'includes/header.php';
?>

<main class="container">
    <h2>Proceder al Pago</h2>
    <div class="checkout-summary">
        <h3>Resumen del Pedido</h3>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li><?php echo $item['name']; ?> - <?php echo number_format($item['price'], 2, ',', '.'); ?> €</li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total a Pagar: <?php echo number_format($total_price, 2, ',', '.'); ?> €</strong></p>
    </div>

    <div class="payment-form-container">
        <form action="process_payment.php" method="POST" id="checkout-form">
            <h3>Datos de Envío</h3>
            <div class="form-group">
                <label for="country">País:</label>
                <select id="country" name="country" required>
                    <option value="">Selecciona un país</option>
                    <?php foreach ($countries as $code => $name): ?>
                        <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="address1">Dirección 1:</label>
                <input type="text" id="address1" name="address1" placeholder="Ej: Calle Principal 123" required>
            </div>
            <div class="form-group">
                <label for="address2">Dirección 2 (Opcional):</label>
                <input type="text" id="address2" name="address2" placeholder="Ej: Apto 4B, Edificio X">
            </div>
            <div class="form-group row">
                <div class="col">
                    <label for="city">Ciudad:</label>
                    <input type="text" id="city" name="city" placeholder="Ej: Santo Domingo" required>
                </div>
                <div class="col">
                    <label for="state">Estado/Provincia:</label>
                    <input type="text" id="state" name="state" placeholder="Ej: Distrito Nacional" required>
                </div>
            </div>
            <div class="form-group">
                <label for="zip_code">Código Postal/Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" placeholder="Ej: 10123" required>
            </div>

            <hr class="form-separator">

            <h3>Detalles de Pago</h3>
            <div class="form-group">
                <label for="card_name">Nombre del titular:</label>
                <input type="text" id="card_name" name="card_name" placeholder="Nombre en la tarjeta" required>
            </div>
            <div class="form-group">
                <label for="card_number">Número de tarjeta:</label>
                <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
            </div>
            <div class="form-group row">
                <div class="col">
                    <label for="expiry_date">Fecha de caducidad (MM/AA):</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/AA" maxlength="5" required>
                </div>
                <div class="col">
                    <label for="cvc">CVC:</label>
                    <input type="text" id="cvc" name="cvc" placeholder="XXX" maxlength="3" required>
                </div>
            </div>
            <button type="submit" class="button primary">PAGAR AHORA</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>