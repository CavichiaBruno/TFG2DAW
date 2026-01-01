<?php
/**
 * Contact Page
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <style>
        .contact-container { max-width: 800px; margin: 2rem auto; padding: 2rem; }
        .contact-form { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; }
        .form-group textarea { min-height: 150px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/components/Layout/nav-component.php'; ?>
    
    <?php if (hasFlash('success')): ?>
        <div class="flash-message flash-success">
            <?php echo htmlspecialchars(getFlash('success')); ?>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlash('error')): ?>
        <div class="flash-message flash-error">
            <?php echo htmlspecialchars(getFlash('error')); ?>
        </div>
    <?php endif; ?>
    
    <main class="contact-container">
        <h1>Contacto</h1>
        <p>¿Tienes alguna pregunta? Envíanos un mensaje y te responderemos lo antes posible.</p>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-form">
            <form method="POST" action="/backend/?action=message_send">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required 
                           value="<?php echo isAuthenticated() ? htmlspecialchars($_SESSION['user_nombre']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required 
                           value="<?php echo isAuthenticated() ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono">
                </div>
                
                <div class="form-group">
                    <label>Asunto</label>
                    <input type="text" name="asunto">
                </div>
                
                <div class="form-group">
                    <label>Mensaje *</label>
                    <textarea name="mensaje" required></textarea>
                </div>
                
                <button type="submit" class="btn-primary">Enviar mensaje</button>
            </form>
        </div>
        
        <div style="margin-top: 2rem;">
            <h3>Información de contacto</h3>
            <p>📧 Email: info@iberpiso.com</p>
            <p>📍 Ubicación: Zaragoza, España</p>
        </div>
    </main>
    
    <?php include __DIR__ . '/components/Layout/footer-component.php'; ?>
</body>
</html>
