<?php
session_start();

// Datos de los 8 productos (duplicados, en un sistema real se cargarían desde una fuente central)
// Es importante que estos datos coincidan con los de index.php
$products = [
    [
        "id" => 1,
        "name" => "Lavadora KROMSLINE 9KG",
        "description" => "Lavadora de gran capacidad (9KG) con eficiencia energética superior. Ideal para familias grandes, cuenta con múltiples programas y un panel intuitivo para un lavado impecable.",
        "image" => "producto1.jpg",
        "old_price" => 550.00,
        "price" => 450.00
    ],
    [
        "id" => 2,
        "name" => "Lavadora KROMSLINE 7KG Inox",
        "description" => "Lavadora compacta de 7KG con acabado en acero inoxidable. Ofrece programas eficientes, bajo consumo energético y un display LED para un control sencillo.",
        "image" => "producto2.jpg",
        "price" => 380.00
    ],
    [
        "id" => 3,
        "name" => "Horno KROMSLINE 70L Convencional",
        "description" => "Horno convencional de 70 litros con 5 funciones de cocción y clasificación energética Clase A. Perfecto para preparar tus platos favoritos con resultados profesionales.",
        "image" => "producto3.jpg",
        "old_price" => 180.00,
        "price" => 150.00
    ],
    [
        "id" => 4,
        "name" => "Aire Acondicionado KROMSLINE Split",
        "description" => "Potente aire acondicionado split de 3010 frigorías. Diseño compacto y elegante, ideal para climatizar tu hogar de forma rápida y eficiente.",
        "image" => "producto4.jpg",
        "price" => 280.00
    ],
    [
        "id" => 5,
        "name" => "Ventilador de Techo Aspas Retráctiles",
        "description" => "Moderno ventilador de techo de 107 cm con aspas retráctiles y motor DC de bajo consumo. Iluminación LED integrada, ideal para cualquier estancia.",
        "image" => "producto5.jpg",
        "price" => 120.00
    ],
    [
        "id" => 6,
        "name" => "Aire Acondicionado DAITSU Split",
        "description" => "Aire acondicionado split de alta eficiencia con tecnología Inverter. Ofrece un rendimiento óptimo tanto en frío como en calor, con control remoto inteligente.",
        "image" => "producto6.jpg",
        "old_price" => 500.00,
        "price" => 420.00
    ],
    [
        "id" => 7,
        "name" => "Ventilador de Techo ORBEGOZO 120CM",
        "description" => "Ventilador de techo de 120 cm con diseño clásico de aspas. Proporciona una brisa potente y constante, perfecto para refrescar grandes espacios.",
        "image" => "producto7.jpg",
        "price" => 85.00
    ],
    [
        "id" => 8,
        "name" => "Ventilador de Techo Abrila Reversible",
        "description" => "Ventilador de techo de 85 cm con aspas reversibles en blanco y haya. Combina funcionalidad y estilo, ideal para habitaciones de tamaño medio.",
        "image" => "producto8.jpg",
        "price" => 75.00
    ]
];


$product_id = $_GET['id'] ?? null;
$selected_product = null;

if ($product_id) {
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            $selected_product = $product;
            break;
        }
    }
}

if (!$selected_product) {
    $_SESSION['message'] = "Producto no encontrado.";
    $_SESSION['message_type'] = "error";
    header('Location: index.php');
    exit();
}

include 'includes/header.php';
?>

<main class="container">
    <div class="product-confirm-box">
        <h2>Confirmar Producto</h2>
        <div class="product-details-confirm">
            <img src="images/products/<?php echo $selected_product['image']; ?>" alt="<?php echo $selected_product['name']; ?>">
            <div class="info">
                <h3><?php echo $selected_product['name']; ?></h3>
                <p><?php echo $selected_product['description']; ?></p>
                <p class="price-confirm">Precio:
                    <?php
                    // Muestra el precio de oferta si existe y es menor que el precio original
                    if (isset($selected_product['old_price']) && $selected_product['old_price'] > $selected_product['price']):
                    ?>
                        <span class="old-price"><?php echo number_format($selected_product['old_price'], 2, ',', '.'); ?> €</span>
                        <span class="offer-price"><?php echo number_format($selected_product['price'], 2, ',', '.'); ?> €</span>
                    <?php else: ?>
                        <?php echo number_format($selected_product['price'], 2, ',', '.'); ?> €
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <form action="index.php" method="POST" class="add-quantity-form">
            <input type="hidden" name="action" value="add_to_cart_final">
            <input type="hidden" name="product_id" value="<?php echo $selected_product['id']; ?>">

            <div class="form-group-quantity">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="99" required>
            </div>

            <div class="form-actions-confirm">
                <button type="submit" class="button primary">AGREGAR AL CARRITO</button>
                <a href="index.php" class="button secondary">SEGUIR COMPRANDO</a>
                <a href="checkout.php" class="button tertiary">PROCEDER AL PAGO</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>