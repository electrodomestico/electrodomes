<?php
session_start();

// Lógica para eliminar del carrito
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    $index_to_remove = $_GET['index'];
    if (isset($_SESSION['cart'][$index_to_remove])) {
        array_splice($_SESSION['cart'], $index_to_remove, 1);
        $_SESSION['message'] = "Producto eliminado del carrito.";
        $_SESSION['message_type'] = "info";
    }
    header('Location: cart.php'); // Redirige para limpiar el GET y actualizar
    exit();
}

$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

include 'includes/header.php';
?>

<main class="container">
    <h2>Tu Carrito de Compras</h2>
    <?php if (empty($cart_items)): ?>
        <p class="empty-cart-message">Tu carrito está vacío. ¡Añade algunos productos!</p>
        <div class="cart-actions" style="justify-content: center;">
            <a href="index.php" class="button secondary">Volver a la tienda</a>
        </div>
    <?php else: ?>
        <div class="cart-list">
            <?php foreach ($cart_items as $index => $item): ?>
                <div class="cart-item">
                    <img src="images/products/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="item-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Precio: <?php echo number_format($item['price'], 2, ',', '.'); ?> €</p>
                    </div>
                    <a href="cart.php?action=remove&index=<?php echo $index; ?>" class="remove-item-btn">X</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-summary">
            <h3>Total: <?php echo number_format($total_price, 2, ',', '.'); ?> €</h3>
        </div>
        <div class="cart-actions">
            <a href="index.php" class="button secondary">Seguir Comprando</a>
            <a href="checkout.php" class="button primary">Proceder al Pago</a>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>