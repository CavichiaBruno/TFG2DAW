<?php
/**
 * Admin Dashboard View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #2563eb; }
        .stat-label { color: #64748b; font-weight: 500; }
        .dashboard-section { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); margin-bottom: 2rem; border: 1px solid #e2e8f0; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f8fafc; color: #475569; font-weight: 600; }
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="dashboard-container">
        <h1>Panel de Administración</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['properties']; ?></div>
                <div class="stat-label">Propiedades Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Usuarios Registrados</div>
                <a href="/TFG2DAW/src/backend/?action=admin_users" style="font-size: 0.8em; color: #2563eb; text-decoration: none; margin-top: 0.5rem; display: inline-block;">Gestionar Usuarios →</a>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['posts']; ?></div>
                <div class="stat-label">Artículos en Blog</div>
            </div>
        </div>
        
        <!-- Recent Properties -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Propiedades Recientes</h2>
                <a href="/TFG2DAW/src/backend/?action=property_create" class="btn-primary">Nueva Propiedad</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentProperties as $prop): ?>
                            <tr>
                                <td>
                                    <a href="/TFG2DAW/src/backend/?action=property_detail&id=<?php echo $prop['id']; ?>" class="text-blue-600 hover:underline">
                                        <?php echo htmlspecialchars($prop['titulo']); ?>
                                    </a>
                                </td>
                                <td><?php echo formatPrice($prop['precio']); ?></td>
                                <td><?php echo ucfirst($prop['tipo']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $prop['activa'] ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $prop['activa'] ? 'Activa' : 'Inactiva'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/TFG2DAW/src/backend/?action=property_edit&id=<?php echo $prop['id']; ?>" class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Blog Posts -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Blog</h2>
                <a href="/TFG2DAW/src/backend/?action=blog_create" class="btn-primary">Nuevo Artículo</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentPosts)): ?>
                            <tr><td colspan="4">No hay artículos.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentPosts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['titulo']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $post['publicado'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $post['publicado'] ? 'Publicado' : 'Borrador'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <a href="/TFG2DAW/src/backend/?action=blog_edit&id=<?php echo $post['id']; ?>" class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
