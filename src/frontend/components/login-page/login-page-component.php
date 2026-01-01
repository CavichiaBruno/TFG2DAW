<?php
/**
 * Login Page Component
 * Converted to PHP with backend integration
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css" />
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/login-page/login-page-component.css" />
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
</head>
<body>
    <main class="layout">
        <section class="left">
            <dotlottie-wc
                src="https://lottie.host/1c4c91de-b109-4f56-a0dc-54d2436cc887/0vnavJi8Pr.lottie"
                autoplay
                loop
            ></dotlottie-wc>
        </section>

        <section class="right">
            <form class="login" method="POST" action="/TFG2DAW/src/backend/?action=login_post">
                <div class="logo">
                    <span class="logo-iber">IBER</span><span class="logo-piso">PISO</span>
                </div>

                <div class="login-header">
                    <h1>Bienvenido</h1>
                    <p class="login-subtitle">Accede a tu portal inmobiliario</p>
                </div>

                <?php if (hasFlash('success')): ?>
                    <div class="flash-message flash-success" style="background: #ecfdf5; color: #047857; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #a7f3d0;">
                        <?php echo htmlspecialchars(getFlash('success')); ?>
                    </div>
                <?php endif; ?>

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
                    <label>Email</label>
                    <input type="email" name="email" placeholder="tu@email.com" required />
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required />
                </div>

                <button type="submit">Iniciar sesión</button>

                <p class="signup-link">
                    ¿No tienes cuenta?
                    <a href="/TFG2DAW/src/backend/?action=register">Regístrate</a>
                </p>
            </form>
        </section>
    </main>
</body>
</html>
