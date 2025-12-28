# Usar imagen base de Nginx
FROM nginx:alpine

# Copiar los archivos estáticos al directorio de Nginx
COPY . /usr/share/nginx/html

# Exponer el puerto 80
EXPOSE 80

# Nginx se inicia automáticamente, no necesitamos CMD adicional
# El CMD por defecto de la imagen nginx:alpine ya inicia el servidor
