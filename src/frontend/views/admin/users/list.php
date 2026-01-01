<?php
/**
 * Admin User List View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-table-container { max-width: 1200px; margin: 3rem auto; padding: 0 1rem; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .data-table { width: 100%; border-collapse: collapse; background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f8fafc; color: #475569; font-weight: 600; }
        .role-badge { padding: 0.25rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background: #e2e8f0; color: #475569; }
        .role-admin { background: #fee2e2; color: #991b1b; }
        .action-buttons { display: flex; gap: 0.5rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../components/Layout/nav-component.php'; ?>
    
    <main class="admin-table-container">
        <div class="admin-header">
            <h1>Gestión de Usuarios</h1>
            <a href="/TFG2DAW/src/backend/?action=admin_user_create" class="btn-primary">Crear Usuario</a>
        </div>
        
        <?php if (hasFlash('success')): ?>
            <div class="flash-success" style="margin-bottom: 1rem; padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem;">
                <?php echo getFlash('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (hasFlash('error')): ?>
            <div class="flash-error" style="margin-bottom: 1rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem;">
                <?php echo getFlash('error'); ?>
            </div>
        <?php endif; ?>
        
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="role-badge <?php echo $user['rol_nombre'] === 'admin' ? 'role-admin' : ''; ?>">
                                    <?php echo ucfirst($user['rol_nombre']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td class="action-buttons">
                                <a href="/TFG2DAW/src/backend/?action=admin_user_edit&id=<?php echo $user['id']; ?>" class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Editar</a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" action="/TFG2DAW/src/backend/?action=admin_user_delete" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination logic here if needed -->
    </main>
    
    <?php include __DIR__ . '/../../../components/Layout/footer-component.php'; ?>
</body>
</html>
