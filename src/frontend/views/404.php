<?php
/**
 * 404 Not Found Page
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada - IberPiso</title>
    <link rel="stylesheet" href="/frontend/globals.css">
    <link rel="stylesheet" href="/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/frontend/components/Layout/footer-component.css">
    <style>
        .error-container { max-width: 600px; margin: 4rem auto; text-align: center; padding: 2rem; }
        .error-container h1 { font-size: 6rem; color: #2563eb; margin-bottom: 1rem; }
        .error-container h2 { font-size: 2rem; margin-bottom: 1rem; }
        .error-container p { margin-bottom: 2rem; color: #6b7280; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/Layout/nav-component.php'; ?>
    
    <div class="error-container">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <a href="/backend/" class="btn-primary">Volver al inicio</a>
    </div>
    
    <?php include __DIR__ . '/components/Layout/footer-component.php'; ?>
</body>
</html>
