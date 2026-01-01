<?php
/**
 * Admin Edit User View
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Admin IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../components/Layout/nav-component.php'; ?>
    
    <main class="form-container">
        <h1>Editar Usuario</h1>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/TFG2DAW/src/backend/?action=admin_user_update">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required value="<?php echo htmlspecialchars($user['nombre']); ?>">
            </div>
            
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?php echo htmlspecialchars($user['apellidos']); ?>">
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Dejar en blanco para mantener la actual">
                <small>Solo rellena esto si quieres cambiar la contraseña</small>
            </div>
            
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
            </div>
            
            <div class="form-group">
                <label>Rol</label>
                <select name="rol_id">
                    <option value="2" <?php echo $user['rol_id'] == 2 ? 'selected' : ''; ?>>Usuario</option>
                    <option value="1" <?php echo $user['rol_id'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </form>
    </main>
    
    <?php include __DIR__ . '/../../../components/Layout/footer-component.php'; ?>
</body>
</html>
