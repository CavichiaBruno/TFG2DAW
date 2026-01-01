# CONFIGURACIÓN APACHE - XAMPP

## Pasos para usar Apache (RECOMENDADO)

### 1. Copiar el proyecto a htdocs

```
# Copia TODA la carpeta del proyecto:
C:\Users\Bruno\Desktop\Proyectos\TFG2DAW
    ↓
C:\xampp\htdocs\iberpiso
```

**Resultado esperado:**
```
C:\xampp\htdocs\iberpiso\
├── src\
│   ├── backend\
│   ├── frontend\
│   └── img\
├── README.MD
└── .htaccess
```

### 2. Configuración de Apache

**Opción A: Con .htaccess (Ya está creado)**

El archivo `.htaccess` ya redirige todo a `src/backend/index.php`

**Opción B: Configuración manual en httpd.conf (MEJOR)**

Edita `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/iberpiso/src/backend"
    ServerName iberpiso.local
    
    <Directory "C:/xampp/htdocs/iberpiso/src/backend">
        AllowOverride All
        Require all granted
        
        # Rewrite para servir archivos estáticos
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    </Directory>
    
    # Alias para archivos estáticos
    Alias /frontend "C:/xampp/htdocs/iberpiso/src/frontend"
    <Directory "C:/xampp/htdocs/iberpiso/src/frontend">
        Require all granted
    </Directory>
    
    Alias /img "C:/xampp/htdocs/iberpiso/src/img"
    <Directory "C:/xampp/htdocs/iberpiso/src/img">
        Require all granted
    </Directory>
    
    Alias /public "C:/xampp/htdocs/iberpiso/src/public"
    <Directory "C:/xampp/htdocs/iberpiso/src/public">
        Require all granted
    </Directory>
</VirtualHost>
```

Luego edita `C:\Windows\System32\drivers\etc\hosts` (como Administrador):
```
127.0.0.1    iberpiso.local
```

### 3. Levantar servicios

**Desde XAMPP Control Panel:**
- ✅ Start Apache
- ✅ Start MySQL

### 4. Importar base de datos

1. Abre http://localhost/phpmyadmin
2. Crea base de datos `iberpiso`
3. Importa `src/backend/database/init.sql`

### 5. Acceder

**Opción A: Con virtual host**
```
http://iberpiso.local
```

**Opción B: Sin virtual host (directo)**
```
http://localhost/iberpiso/src/backend/
```

---

## SOLUCIÓN RÁPIDA (Sin configurar nada)

1. Detén el servidor PHP (`Ctrl+C` en la terminal)

2. Copia el proyecto:
   ```powershell
   xcopy /E /I /Y "C:\Users\Bruno\Desktop\Proyectos\TFG2DAW" "C:\xampp\htdocs\iberpiso"
   ```

3. Desde XAMPP Start Apache y MySQL

4. Ve a: `http://localhost/iberpiso/src/backend/`

**¡Ya está!** Los estilos cargarán automáticamente.

---

## ¿Por qué no funciona el servidor de desarrollo?

El servidor de PHP (`php -S`) está pensado solo para desarrollo simple. No maneja bien:
- Rutas relativas complejas
- Archivos estáticos fuera del directorio raíz
- Alias de directorios

Apache sí maneja todo esto perfectamente.
