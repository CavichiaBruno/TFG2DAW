<?php
/**
 * Layout Template
 * Base template with header, nav, footer
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'IberPiso - Portal Inmobiliario'; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/frontend/globals.css">
    <link rel="stylesheet" href="/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
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
    
    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>
    
    <?php include __DIR__ . '/../components/Layout/footer-component.php'; ?>
    
    <!-- JavaScript -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
