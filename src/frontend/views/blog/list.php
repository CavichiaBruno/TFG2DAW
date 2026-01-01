<?php
/**
 * Blog List View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .blog-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .blog-card { background: white; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; transition: transform 0.2s; }
        .blog-card:hover { transform: translateY(-5px); }
        .blog-image { height: 200px; background: #f1f5f9; overflow: hidden; }
        .blog-image img { width: 100%; height: 100%; object-fit: cover; }
        .blog-content { padding: 1.5rem; }
        .blog-meta { font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem; }
        .blog-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: #1e293b; }
        .blog-excerpt { color: #475569; margin-bottom: 1.5rem; line-height: 1.6; }
        .read-more { color: #2563eb; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; }
        .read-more:hover { gap: 0.5rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="blog-container">
        <h1>Blog Inmobiliario</h1>
        <p>Noticias, consejos y tendencias del sector</p>
        
        <?php if (isAdmin()): ?>
            <div style="margin-top: 1rem;">
                <a href="/TFG2DAW/src/backend/?action=blog_create" class="btn-primary">Nuevo Artículo</a>
            </div>
        <?php endif; ?>
        
        <?php if (empty($posts)): ?>
            <p>No hay artículos publicados todavía.</p>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="blog-card">
                        <div class="blog-image">
                            <?php if ($post['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($post['imagen']); ?>" alt="<?php echo htmlspecialchars($post['titulo']); ?>">
                            <?php else: ?>
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 3rem;">📰</div>
                            <?php endif; ?>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <?php echo date('d M Y', strtotime($post['created_at'])); ?> • <?php echo htmlspecialchars($post['autor_nombre']); ?>
                            </div>
                            <h2 class="blog-title"><?php echo htmlspecialchars($post['titulo']); ?></h2>
                            <p class="blog-excerpt"><?php echo htmlspecialchars($post['extracto']); ?></p>
                            <a href="/TFG2DAW/src/backend/?action=blog_detail&slug=<?php echo $post['slug']; ?>" class="read-more">
                                Leer más →
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
