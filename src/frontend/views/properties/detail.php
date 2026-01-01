<?php
/**
 * Property Detail View
 * Display single property with full details
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['titulo']); ?> - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="property-detail">
        <div class="property-header">
            <h1><?php echo htmlspecialchars($property['titulo']); ?></h1>
            <p class="property-location">
                📍 <?php echo htmlspecialchars($property['direccion'] ?? $property['ciudad']); ?>, 
                <?php echo htmlspecialchars($property['provincia'] ?? ''); ?>
            </p>
            <div class="property-main-price">
                <?php echo formatPrice($property['precio']); ?>
                <?php echo $property['operacion'] === 'alquiler' ? '/mes' : ''; ?>
            </div>
            <p><strong>Tipo:</strong> <?php echo ucfirst($property['tipo']); ?> en <?php echo ucfirst($property['operacion']); ?></p>
            <p><small>Visitas: <?php echo $property['visitas']; ?></small></p>
        </div>
        
        <!-- Images Gallery -->
        <?php if (!empty($images)): ?>
            <div class="property-images">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['ruta']); ?>" 
                         alt="<?php echo htmlspecialchars($property['titulo']); ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="property-details-grid">
            <div>
                <!-- Description -->
                <div class="property-section">
                    <h2>Descripción</h2>
                    <p><?php echo nl2br(htmlspecialchars($property['descripcion'] ?? 'Sin descripción')); ?></p>
                </div>
                
                <!-- Features -->
                <div class="property-section">
                    <h2>Características</h2>
                    <div class="property-features-list">
                        <?php if ($property['habitaciones']): ?>
                            <div class="feature-item">🛏️ <?php echo $property['habitaciones']; ?> Habitaciones</div>
                        <?php endif; ?>
                        <?php if ($property['banos']): ?>
                            <div class="feature-item">🚿 <?php echo $property['banos']; ?> Baños</div>
                        <?php endif; ?>
                        <?php if ($property['superficie']): ?>
                            <div class="feature-item">📐 <?php echo $property['superficie']; ?> m²</div>
                        <?php endif; ?>
                        <?php if ($property['codigo_postal']): ?>
                            <div class="feature-item">📮 CP: <?php echo htmlspecialchars($property['codigo_postal']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Owner actions -->
                <?php if (isAuthenticated() && ($_SESSION['user_id'] == $property['usuario_id'] || isAdmin())): ?>
                    <div class="admin-actions">
                        <a href="/TFG2DAW/src/backend/?action=property_edit&id=<?php echo $property['id']; ?>" class="btn-primary">Editar</a>
                        <form method="POST" action="/TFG2DAW/src/backend/?action=property_delete" style="display: inline;" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta propiedad?');">
                            <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Contact Form -->
            <div>
                <div class="contact-form">
                    <h3>Contactar</h3>
                    <form method="POST" action="/TFG2DAW/src/backend/?action=message_send">
                        <input type="hidden" name="propiedad_id" value="<?php echo $property['id']; ?>">
                        
                        <input type="text" name="nombre" placeholder="Tu nombre" required 
                               value="<?php echo isAuthenticated() ? htmlspecialchars($_SESSION['user_nombre']) : ''; ?>">
                        
                        <input type="email" name="email" placeholder="Tu email" required 
                               value="<?php echo isAuthenticated() ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                        
                        <input type="tel" name="telefono" placeholder="Tu teléfono">
                        
                        <input type="text" name="asunto" placeholder="Asunto" 
                               value="Consulta sobre: <?php echo htmlspecialchars($property['titulo']); ?>">
                        
                        <textarea name="mensaje" rows="5" placeholder="Tu mensaje" required></textarea>
                        
                        <button type="submit" class="btn-primary">Enviar mensaje</button>
                    </form>
                </div>
                
                <!-- Add to Favorites -->
                <?php if (isAuthenticated()): ?>
                    <button class="btn-favorite" onclick="toggleFavorite(<?php echo $property['id']; ?>)">
                        ⭐ Añadir a favoritos
                    </button>
                <?php endif; ?>
                
                <!-- Contact Info -->
                <div class="property-section" style="margin-top: 1rem;">
                    <h3>Información del anunciante</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($property['usuario_nombre']); ?></p>
                    <?php if ($property['usuario_email']): ?>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($property['usuario_email']); ?></p>
                    <?php endif; ?>
                    <?php if ($property['usuario_telefono']): ?>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($property['usuario_telefono']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
    
    <script>
        function toggleFavorite(propertyId) {
            fetch('/TFG2DAW/src/backend/?action=favorite_toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'propiedad_id=' + propertyId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.action === 'added' ? 'Añadido a favoritos' : 'Eliminado de favoritos');
                }
            });
        }
    </script>
</body>
</html>
