<?php
session_start(); // Inicia la sesión para el carrito

// Inicializa el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Datos de los 8 productos de electrodomésticos
// He incluido ejemplos con "old_price" para que veas cómo se verían las ofertas
$products = [
    [
        "id" => 1,
        "name" => "Lavadora KROMSLINE 9KG",
        "description" => "Lavadora de gran capacidad (9KG) con eficiencia energética superior. Ideal para familias grandes, cuenta con múltiples programas y un panel intuitivo para un lavado impecable.",
        "image" => "producto1.jpg", // Asegúrate de que esta imagen exista en images/products/
        "old_price" => 550.00, // Precio anterior para oferta
        "price" => 450.00      // Precio actual de oferta
    ],
    [
        "id" => 2,
        "name" => "Lavadora KROMSLINE 7KG Inox",
        "description" => "Lavadora compacta de 7KG con acabado en acero inoxidable. Ofrece programas eficientes, bajo consumo energético y un display LED para un control sencillo.",
        "image" => "producto2.jpg",
        "price" => 380.00      // Precio normal (sin oferta)
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

// Lógica para AÑADIR UN PRODUCTO al carrito (maneja cantidad)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart_final' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        $_SESSION['message'] = "La cantidad debe ser al menos 1.";
        $_SESSION['message_type'] = "error";
        header('Location: add_to_cart_confirm.php?id=' . $product_id);
        exit();
    }

    $found_product = null;
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            $found_product = $product;
            break;
        }
    }

    if ($found_product) {
        // Añadir el producto con la cantidad especificada
        for ($i = 0; $i < $quantity; $i++) {
            $_SESSION['cart'][] = $found_product;
        }
        $_SESSION['message'] = "¡{$quantity}x {$found_product['name']} añadido(s) al carrito!";
        $_SESSION['message_type'] = "success";
        header('Location: cart.php'); // Redirige al carrito después de añadir
        exit();
    } else {
        $_SESSION['message'] = "Producto no encontrado.";
        $_SESSION['message_type'] = "error";
        header('Location: index.php'); // Volver a la página principal si el producto no existe
        exit();
    }
}

// Incluye el encabezado
include 'includes/header.php';
?>

<main class="container">
    <h2>Productos Destacados</h2>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p class="description"><?php echo $product['description']; ?></p>
                <p class="price">
                    <?php
                    // Muestra el precio de oferta si existe y es menor que el precio original
                    if (isset($product['old_price']) && $product['old_price'] > $product['price']):
                    ?>
                        <span class="old-price"><?php echo number_format($product['old_price'], 2, ',', '.'); ?> €</span>
                        <span class="offer-price"><?php echo number_format($product['price'], 2, ',', '.'); ?> €</span>
                    <?php else: ?>
                        <?php echo number_format($product['price'], 2, ',', '.'); ?> €
                    <?php endif; ?>
                </p>
                <a href="add_to_cart_confirm.php?id=<?php echo $product['id']; ?>" class="add-to-cart-btn">AÑADIR AL CARRITO</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
// Incluye el pie de página
include 'includes/footer.php';
?>