<?php
/**
 * Register Page Component
 * Converted to PHP with backend integration
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css" />
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/register-page/register-page-component.css" />
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
</head>
<body>
    <main class="layout">
        <a href="/TFG2DAW/src/backend/?action=login" class="back-button">Volver</a>
        
        <section>
            <form class="register" method="POST" action="/TFG2DAW/src/backend/?action=register_post">
                <div class="logo">
                    <span class="logo-iber">IBER</span><span class="logo-piso">PISO</span>
                </div>

                <h1>Crear cuenta</h1>
                <p class="register-subtitle">Únete a nuestra comunidad inmobiliaria</p>

                <?php if (hasFlash('error')): ?>
                    <div class="flash-message flash-error" style="background: #fef2f2; color: #dc2626; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #fecaca;">
                        <?php echo htmlspecialchars(getFlash('error')); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p class="error"><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                        <?php unset($_SESSION['errors']); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Tu nombre" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['nombre'] ?? ''); ?>" required />
                </div>

                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" placeholder="Tus apellidos" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['apellidos'] ?? ''); ?>" />
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="tu@email.com" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['email'] ?? ''); ?>" required />
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="600123456" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['telefono'] ?? ''); ?>" />
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required />
                </div>

                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_confirm" placeholder="••••••••" required />
                </div>

                <button type="submit">Registrarse</button>

                <p class="login-link">
                    ¿Ya tienes cuenta?
                    <a href="/TFG2DAW/src/backend/?action=login">Iniciar sesión</a>
                </p>
            </form>
        </section>
    </main>
    <?php unset($_SESSION['old_input']); ?>
</body>
</html>
