<?php
/**
 * Blog Detail View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['titulo']); ?> - Blog IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .blog-detail-container { max-width: 800px; margin: 3rem auto; padding: 0 1rem; }
        .article-header { text-align: center; margin-bottom: 3rem; }
        .article-title { font-size: 2.5rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem; line-height: 1.2; }
        .article-meta { color: #64748b; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 1rem; }
        .article-image { width: 100%; height: 400px; object-fit: cover; border-radius: 1rem; margin-bottom: 3rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .article-content { font-size: 1.125rem; line-height: 1.8; color: #334155; }
        .article-content p { margin-bottom: 1.5rem; }
        .article-content h2 { font-size: 1.75rem; color: #1e293b; margin: 2rem 0 1rem; }
        .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: #64748b; text-decoration: none; margin-bottom: 2rem; font-weight: 500; transition: color 0.2s; }
        .back-link:hover { color: #2563eb; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="blog-detail-container">
        <a href="/TFG2DAW/src/backend/?action=blog" class="back-link">← Volver al blog</a>
        
        <header class="article-header">
            <h1 class="article-title"><?php echo htmlspecialchars($post['titulo']); ?></h1>
            <div class="article-meta">
                <span>📅 <?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                <span>✍️ <?php echo htmlspecialchars($post['autor_nombre']); ?></span>
            </div>
        </header>
        
        <?php if ($post['imagen']): ?>
            <img src="<?php echo htmlspecialchars($post['imagen']); ?>" alt="<?php echo htmlspecialchars($post['titulo']); ?>" class="article-image">
        <?php endif; ?>
        
        <div class="article-content">
            <?php echo nl2br($post['contenido']); // Allow HTML or nl2br depending on requirement. Assuming safe content or sanitized. ?>
        </div>
        
        <?php if (isAdmin()): ?>
            <div style="margin-top: 3rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                <a href="/TFG2DAW/src/backend/?action=blog_edit&id=<?php echo $post['id']; ?>" class="btn-secondary">Editar Artículo</a>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
