<?php
/**
 * Home Page
 * Landing page with hero and featured properties
 */

require_once __DIR__ . '/../../backend/models/PropertyModel.php';

$propertyModel = new PropertyModel($pdo);
$featuredProperties = $propertyModel->getFeatured(6);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IberPiso - Tu Portal Inmobiliario de Confianza</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/hero-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../components/Layout/nav-component.php'; ?>
    
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
    
    <?php include __DIR__ . '/../components/Layout/hero-component.php'; ?>
    
    <!-- Services/Value Prop -->
    <section class="landing-section" style="background: white; padding: 5rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 3rem;">
                <h2 style="font-size: 2.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Todo lo que necesitas para tu hogar</h2>
                <p style="font-size: 1.125rem; color: #64748b;">Simplificamos el proceso inmobiliario. Ya sea que busques comprar, vender o alquilar, estamos aquí para guiarte en cada paso.</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div style="padding: 2rem; border-radius: 1rem; background: #f8fafc; text-align: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🏡</div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Comprar</h3>
                    <p style="color: #64748b;">Encuentra el hogar de tus sueños entre miles de propiedades seleccionadas.</p>
                </div>
                <div style="padding: 2rem; border-radius: 1rem; background: #f8fafc; text-align: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🔑</div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Alquilar</h3>
                    <p style="color: #64748b;">Opciones flexibles y verificadas para que te mudes con total confianza.</p>
                </div>
                <div style="padding: 2rem; border-radius: 1rem; background: #f8fafc; text-align: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💰</div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Vender</h3>
                    <p style="color: #64748b;">Valora tu propiedad y véndela al mejor precio con nuestros expertos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="stats-section" style="background: #0f172a; padding: 4rem 0; color: white;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: flex; justify-content: space-around; flex-wrap: wrap; gap: 2rem; text-align: center;">
            <div>
                <div style="font-size: 3rem; font-weight: 800; color: #3b82f6;">500+</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">Propiedades Vendidas</div>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: 800; color: #3b82f6;">12k+</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">Clientes Felices</div>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: 800; color: #3b82f6;">15</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">Años de Experiencia</div>
            </div>
        </div>
    </section>

    <main class="properties-main" style="background: #f8fafc; padding: 5rem 0;">
        <div class="properties-container">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 class="properties-title" style="font-size: 2.5rem; margin-bottom: 1rem;">Propiedades Destacadas</h2>
                <p style="color: #64748b; font-size: 1.125rem;">Una selección exclusiva de las mejores oportunidades del mercado</p>
            </div>
            
            <div class="properties-grid">
                <?php if (!empty($featuredProperties)): ?>
                    <?php foreach ($featuredProperties as $property): ?>
                        <article class="property-card">
                            <div class="property-image">
                                <span class="property-badge <?php echo $property['operacion'] === 'alquiler' ? 'badge-rent' : ''; ?>">
                                    <?php echo ucfirst($property['operacion']); ?>
                                </span>
                                <?php
                                require_once __DIR__ . '/../../backend/models/ImageModel.php';
                                $imageModel = new ImageModel($pdo);
                                $imagen = $imageModel->getPrincipal($property['id']);
                                if ($imagen):
                                ?>
                                    <img src="<?php echo htmlspecialchars($imagen['ruta']); ?>" alt="<?php echo htmlspecialchars($property['titulo']); ?>">
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
                    <p style="text-align: center; width: 100%; grid-column: 1/-1;">No hay propiedades destacadas en este momento.</p>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="/TFG2DAW/src/backend/?action=properties" class="btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">Explorar Todo el Catálogo</a>
            </div>
        </div>
    </main>

    <!-- Testimonials/Clean CTA -->
    <section class="cta-section" style="background: white; padding: 6rem 0; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">¿Listo para encontrar tu próximo hogar?</h2>
            <p style="font-size: 1.25rem; color: #64748b; margin-bottom: 2.5rem;">Únete a miles de personas que ya han confiado en IberPiso para su próxima gran decisión.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/TFG2DAW/src/backend/?action=register" class="btn-primary" style="padding: 1rem 2rem;">Crear Cuenta Gratis</a>
                <a href="/TFG2DAW/src/backend/?action=contact" class="btn-secondary" style="padding: 1rem 2rem; background: white; border: 1px solid #e2e8f0; color: #0f172a;">Contactar Agente</a>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/../components/Layout/footer-component.php'; ?>
</body>
</html>
