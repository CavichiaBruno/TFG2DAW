<?php
/**
 * Properties List View
 * Display all properties with filters
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propiedades - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <!-- Flash Messages -->
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
    
    <main class="properties-main">
        <div class="properties-container">
            <!-- Filters -->
            <div class="filters-section">
                <h2>Buscar Propiedades</h2>
                <form method="GET" action="/TFG2DAW/src/backend/">
                    <input type="hidden" name="action" value="properties">
                    
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label>Operación</label>
                            <select name="operacion">
                                <option value="">Todas</option>
                                <option value="venta" <?php echo ($_GET['operacion'] ?? '') === 'venta' ? 'selected' : ''; ?>>Venta</option>
                                <option value="alquiler" <?php echo ($_GET['operacion'] ?? '') === 'alquiler' ? 'selected' : ''; ?>>Alquiler</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Tipo</label>
                            <select name="tipo">
                                <option value="">Todos</option>
                                <option value="piso" <?php echo ($_GET['tipo'] ?? '') === 'piso' ? 'selected' : ''; ?>>Piso</option>
                                <option value="casa" <?php echo ($_GET['tipo'] ?? '') === 'casa' ? 'selected' : ''; ?>>Casa</option>
                                <option value="terreno" <?php echo ($_GET['tipo'] ?? '') === 'terreno' ? 'selected' : ''; ?>>Terreno</option>
                                <option value="local" <?php echo ($_GET['tipo'] ?? '') === 'local' ? 'selected' : ''; ?>>Local</option>
                                <option value="oficina" <?php echo ($_GET['tipo'] ?? '') === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Ciudad</label>
                            <input type="text" name="ciudad" placeholder="Ej: Madrid" 
                                   value="<?php echo htmlspecialchars($_GET['ciudad'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label>Precio mínimo</label>
                            <input type="number" name="precio_min" placeholder="0" 
                                   value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label>Precio máximo</label>
                            <input type="number" name="precio_max" placeholder="1000000" 
                                   value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label>Habitaciones mín.</label>
                            <input type="number" name="habitaciones" min="1" 
                                   value="<?php echo htmlspecialchars($_GET['habitaciones'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary">Buscar</button>
                </form>
            </div>
            
            <h2 class="properties-title">
                <?php echo $total; ?> Propiedades Encontradas
                <?php if ($pagination['currentPage'] > 1): ?>
                    - Página <?php echo $pagination['currentPage']; ?> de <?php echo $pagination['totalPages']; ?>
                <?php endif; ?>
            </h2>
            
            <div class="properties-grid">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $property): ?>
                        <article class="property-card">
                            <div class="property-image">
                                <span class="property-badge <?php echo $property['operacion'] === 'alquiler' ? 'badge-rent' : ''; ?>">
                                    <?php echo ucfirst($property['operacion']); ?>
                                </span>
                                <?php if ($property['imagen_principal']): ?>
                                    <img src="<?php echo htmlspecialchars($property['imagen_principal']['ruta']); ?>" 
                                         alt="<?php echo htmlspecialchars($property['titulo']); ?>">
                                <?php else: ?>
                                    <div class="image-placeholder">🏠</div>
                                <?php endif; ?>
                            </div>
                            <div class="property-info">
                                <div class="property-price">
                                    <?php echo formatPrice($property['precio']); ?>
                                    <?php echo $property['operacion'] === 'alquiler' ? '/mes' : ''; ?>
                                </div>
                                <h3 class="property-title"><?php echo htmlspecialchars($property['titulo']); ?></h3>
                                <p class="property-location">
                                    📍 <?php echo htmlspecialchars($property['ciudad']); ?>
                                    <?php if ($property['provincia']): ?>, <?php echo htmlspecialchars($property['provincia']); ?><?php endif; ?>
                                </p>
                                <div class="property-features">
                                    <?php if ($property['habitaciones']): ?>
                                        <span>🛏️ <?php echo $property['habitaciones']; ?> hab.</span>
                                    <?php endif; ?>
                                    <?php if ($property['banos']): ?>
                                        <span>🚿 <?php echo $property['banos']; ?> baños</span>
                                    <?php endif; ?>
                                    <?php if ($property['superficie']): ?>
                                        <span>📐 <?php echo $property['superficie']; ?> m²</span>
                                    <?php endif; ?>
                                </div>
                                <a href="/TFG2DAW/src/backend/?action=property_detail&id=<?php echo $property['id']; ?>" class="property-link">
                                    Ver detalles →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron propiedades con los criterios seleccionados.</p>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="pagination">
                    <?php if ($pagination['hasPrev']): ?>
                        <a href="?action=properties&page=<?php echo $pagination['currentPage'] - 1; ?><?php echo http_build_query(array_diff_key($_GET, ['action' => '', 'page' => ''])); ?>" 
                           class="btn-secondary">← Anterior</a>
                    <?php endif; ?>
                    
                    <span>Página <?php echo $pagination['currentPage']; ?> de <?php echo $pagination['totalPages']; ?></span>
                    
                    <?php if ($pagination['hasNext']): ?>
                        <a href="?action=properties&page=<?php echo $pagination['currentPage'] + 1; ?><?php echo http_build_query(array_diff_key($_GET, ['action' => '', 'page' => ''])); ?>" 
                           class="btn-secondary">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
