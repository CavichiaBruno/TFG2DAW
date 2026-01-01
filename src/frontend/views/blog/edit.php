<?php
/**
 * Edit Blog Post View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="form-container">
        <h1>Editar Artículo</h1>
        
        <form method="POST" action="/TFG2DAW/src/backend/?action=blog_update" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
            
            <div class="form-group">
                <label>Título *</label>
                <input type="text" name="titulo" required value="<?php echo htmlspecialchars($post['titulo']); ?>">
            </div>
            
            <div class="form-group">
                <label>Extracto (Resumen)</label>
                <textarea name="extracto" rows="3"><?php echo htmlspecialchars($post['extracto']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Contenido *</label>
                <textarea name="contenido" rows="15" required><?php echo htmlspecialchars($post['contenido']); ?></textarea>
            </div>
            
            <?php if ($post['imagen']): ?>
                <div class="form-group">
                    <label>Imagen Actual</label>
                    <img src="<?php echo htmlspecialchars($post['imagen']); ?>" alt="Actual" style="max-height: 150px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>Cambiar Imagen</label>
                <input type="file" name="imagen" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="publicado" value="1" <?php echo $post['publicado'] ? 'checked' : ''; ?>>
                    Publicado
                </label>
            </div>
            
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn-primary">Actualizar</button>
                <button type="submit" formaction="/TFG2DAW/src/backend/?action=blog_delete" class="btn-danger" 
                        onclick="return confirm('¿Eliminar artículo permanentemente?');">Eliminar</button>
            </div>
        </form>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
