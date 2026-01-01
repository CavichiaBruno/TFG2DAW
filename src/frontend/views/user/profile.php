<?php
/**
 * User Profile View
 * Display user profile with properties and favorites
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .profile-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .profile-header { background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .profile-section { background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .profile-section h2 { margin-bottom: 1.5rem; border-bottom: 2px solid #2563eb; padding-bottom: 0.5rem; }
        .profile-info { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .info-item { padding: 0.75rem; background: #f9fafb; border-radius: 4px; }
        .info-item label { font-weight: 600; color: #4b5563; display: block; margin-bottom: 0.25rem; }
        .properties-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .property-card { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; transition: transform 0.2s; }
        .property-card:hover { transform: translateY(-4px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .property-image { height: 200px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
        .property-info { padding: 1rem; }
        .btn-edit { background: #059669; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; margin-right: 0.5rem; }
        .btn-delete { background: #dc2626; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
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
    
    <main class="profile-container">
        <div class="profile-header">
            <h1>Mi Perfil</h1>
            <div class="profile-info">
                <div class="info-item">
                    <label>Nombre:</label>
                    <span><?php echo htmlspecialchars($user['nombre']); ?> <?php echo htmlspecialchars($user['apellidos'] ?? ''); ?></span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-item">
                    <label>Teléfono:</label>
                    <span><?php echo htmlspecialchars($user['telefono'] ?? 'No especificado'); ?></span>
                </div>
                <div class="info-item">
                    <label>Rol:</label>
                    <span><?php echo ucfirst($user['rol_nombre']); ?></span>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <a href="/TFG2DAW/src/backend/?action=profile_edit" class="btn-primary">Editar Perfil</a>
            </div>
        </div>
        
        <div class="profile-section">
            <h2>Mis Propiedades (<?php echo count($properties); ?>)</h2>
            <?php if (!empty($properties)): ?>
                <div class="properties-grid">
                    <?php foreach ($properties as $property): ?>
                        <div class="property-card">
                            <div class="property-image">🏠</div>
                            <div class="property-info">
                                <h3><?php echo htmlspecialchars($property['titulo']); ?></h3>
                                <p><strong>Precio:</strong> <?php echo formatPrice($property['precio']); ?></p>
                                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($property['ciudad']); ?></p>
                                <p><strong>Estado:</strong> <?php echo $property['activa'] ? 'Activa' : 'Inactiva'; ?></p>
                                <div style="margin-top: 1rem;">
                                    <a href="/TFG2DAW/src/backend/?action=property_detail&id=<?php echo $property['id']; ?>">
                                        <button class="btn-primary">Ver</button>
                                    </a>
                                    <a href="/TFG2DAW/src/backend/?action=property_edit&id=<?php echo $property['id']; ?>">
                                        <button class="btn-edit">Editar</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tienes propiedades publicadas.</p>
                <a href="/TFG2DAW/src/backend/?action=property_create" class="btn-primary">Publicar Propiedad</a>
            <?php endif; ?>
        </div>
        
        <div class="profile-section">
            <h2>Mis Favoritos (<?php echo count($favorites); ?>)</h2>
            <?php if (!empty($favorites)): ?>
                <div class="properties-grid">
                    <?php foreach ($favorites as $favorite): ?>
                        <div class="property-card">
                            <div class="property-image">🏠</div>
                            <div class="property-info">
                                <h3><?php echo htmlspecialchars($favorite['titulo']); ?></h3>
                                <p><strong>Precio:</strong> <?php echo formatPrice($favorite['precio']); ?></p>
                                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($favorite['ciudad']); ?></p>
                                <div style="margin-top: 1rem;">
                                    <a href="/TFG2DAW/src/backend/?action=property_detail&id=<?php echo $favorite['id']; ?>">
                                        <button class="btn-primary">Ver Detalle</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tienes propiedades en favoritos.</p>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
