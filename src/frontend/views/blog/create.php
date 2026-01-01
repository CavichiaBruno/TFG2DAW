<?php
/**
 * Create Blog Post View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Artículo - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="form-container">
        <h1>Nuevo Artículo</h1>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/TFG2DAW/src/backend/?action=blog_store" enctype="multipart/form-data">
            <div class="form-group">
                <label>Título *</label>
                <input type="text" name="titulo" required placeholder="Título del artículo">
            </div>
            
            <div class="form-group">
                <label>Extracto (Resumen)</label>
                <textarea name="extracto" rows="3" placeholder="Breve resumen para el listado..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Contenido *</label>
                <textarea name="contenido" rows="15" required placeholder="Escribe aquí el contenido... (HTML permitido)"></textarea>
            </div>
            
            <div class="form-group">
                <label>Imagen Destacada</label>
                <input type="file" name="imagen" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="publicado" value="1" checked>
                    Publicar inmediatamente
                </label>
            </div>
            
            <button type="submit" class="btn-primary">Publicar</button>
        </form>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
