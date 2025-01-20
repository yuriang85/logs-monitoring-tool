#!/bin/bash

# Configuración
SMB_SERVER="//DATASERVER/Logs"
SMB_USER="metunas.co.cu\\administrator"
SMB_PASSWORD='uzumymw2846*'
LOCAL_PATH="/var/www/metunas.co.cu/Logs/"
REMOTE_PATH="*.csv"

# Comprobamos si el directorio local existe
if [ ! -d "$LOCAL_PATH" ]; then
    echo "El directorio local $LOCAL_PATH no existe. Creándolo..."
    mkdir -p "$LOCAL_PATH"
fi

echo "Conectando al recurso compartido SMB y descargando archivos..."
# Ejecutar smbget para descargar los archivos
smbget -U "$SMB_USER"%"$SMB_PASSWORD" "$SMB_SERVER/$REMOTE_PATH" -P -O "$LOCAL_PATH"

# Verificar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "Archivos sincronizados correctamente desde $SMB_SERVER a $LOCAL_PATH"
else
    echo "Error al sincronizar archivos. Verifica las credenciales y la conectividad."
    exit 1
fi

# Verificar si los archivos fueron descargados al directorio local
echo "Archivos descargados en $LOCAL_PATH:"
ls -l $LOCAL_PATH
