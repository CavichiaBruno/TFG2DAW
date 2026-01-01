<?php
/**
 * Navigation Component
 * Reusable navigation bar with authentication status
 */
startSession();
?>
<nav class="nav">
  <div class="nav-logo">
    <img src="/TFG2DAW/src/img/IBERPISO_LOGO.jpg" alt="IberPiso Logo" />
    <h3>IberPiso</h3>
  </div>
  
  <ul class="navList">
    <li><a href="/TFG2DAW/src/backend/?action=properties&operacion=venta">Comprar</a></li>
    <li><a href="/TFG2DAW/src/backend/?action=properties&operacion=alquiler">Alquilar</a></li>
    <li><a href="/TFG2DAW/src/backend/?action=property_create">Vender</a></li>
    <li><a href="/TFG2DAW/src/backend/?action=blog">Blog</a></li>
  </ul>

  <div class="navRight">
    <?php if (isAuthenticated()): ?>
      <span class="user-greeting">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
      <a href="/TFG2DAW/src/backend/?action=profile">
        <button type="button" class="btn-profile">Mi Perfil</button>
      </a>
      <?php if (isAdmin()): ?>
        <a href="/TFG2DAW/src/backend/?action=admin_dashboard">
          <button type="button" class="btn-admin">Admin</button>
        </a>
      <?php endif; ?>
      <a href="/TFG2DAW/src/backend/?action=logout">
        <button type="button" class="btn-logout">Salir</button>
      </a>
    <?php else: ?>
      <a href="/TFG2DAW/src/backend/?action=login">
        <button type="button" class="btn-login">Iniciar Sesión</button>
      </a>
    <?php endif; ?>
  </div>
</nav>
