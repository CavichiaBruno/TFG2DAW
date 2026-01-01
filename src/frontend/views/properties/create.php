<?php
/**
 * Create Property Form
 * Form for adding a new property
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Propiedad - IberPiso</title>
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/globals.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/nav-component.css">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/footer-component.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TFG2DAW/src/frontend/components/Layout/main-component.css">
</head>
<body>
    <?php include __DIR__ . '/../../components/Layout/nav-component.php'; ?>
    
    <main class="form-container">
        <h1>Publicar Propiedad</h1>
        <p>Completa el formulario para publicar tu propiedad</p>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/TFG2DAW/src/backend/?action=property_store" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Título *</label>
                    <input type="text" name="titulo" required 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['titulo'] ?? ''); ?>" 
                           placeholder="Ej: Piso céntrico con 3 habitaciones">
                </div>
                
                <div class="form-group full-width">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Describe tu propiedad..."><?php echo htmlspecialchars($_SESSION['old_input']['descripcion'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tipo *</label>
                    <select name="tipo" required>
                        <option value="piso">Piso</option>
                        <option value="casa">Casa</option>
                        <option value="terreno">Terreno</option>
                        <option value="local">Local</option>
                        <option value="oficina">Oficina</option>
                        <option value="garaje">Garaje</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Operación *</label>
                    <select name="operacion" required>
                        <option value="venta">Venta</option>
                        <option value="alquiler">Alquiler</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Precio (€) *</label>
                    <input type="number" name="precio" required min="0" step="0.01" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['precio'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Superficie (m²)</label>
                    <input type="number" name="superficie" min="0" step="0.01" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['superficie'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Habitaciones</label>
                    <input type="number" name="habitaciones" min="0" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['habitaciones'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Baños</label>
                    <input type="number" name="banos" min="0" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['banos'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Provincia</label>
                    <input type="text" name="provincia" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['provincia'] ?? ''); ?>" 
                           placeholder="Ej: Madrid">
                </div>
                
                <div class="form-group">
                    <label>Ciudad *</label>
                    <input type="text" name="ciudad" required 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['ciudad'] ?? ''); ?>" 
                           placeholder="Ej: Madrid">
                </div>
                
                <div class="form-group">
                    <label>Código Postal</label>
                    <input type="text" name="codigo_postal" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['codigo_postal'] ?? ''); ?>" 
                           placeholder="28001">
                </div>
                
                <div class="form-group full-width">
                    <label>Dirección</label>
                    <input type="text" name="direccion" 
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['direccion'] ?? ''); ?>" 
                           placeholder="Calle, número, piso">
                </div>
                
                <div class="form-group full-width">
                    <label>Imágenes</label>
                    <div class="file-upload-container">
                        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="file-input">
                        <label for="imageInput" class="file-label">
                            <span class="icon">📷</span>
                            <span>Haz clic para seleccionar fotos</span>
                        </label>
                    </div>
                    <div id="imagePreview" class="image-preview-grid"></div>
                </div>
                
                <?php if (isAdmin()): ?>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="destacada" value="1">
                            Marcar como destacada
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-primary" style="margin-top: 1.5rem;">Publicar Propiedad</button>
        </form>
    </main>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (this.files) {
                [...this.files].forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'preview-thumb';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
    
    <style>
        .file-upload-container {
            border: 2px dashed #cbd5e1;
            padding: 2rem;
            text-align: center;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .file-upload-container:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }
        .file-input {
            display: none;
        }
        .file-label {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .preview-thumb {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
    
    <?php unset($_SESSION['old_input']); ?>
    <?php include __DIR__ . '/../../components/Layout/footer-component.php'; ?>
</body>
</html>
