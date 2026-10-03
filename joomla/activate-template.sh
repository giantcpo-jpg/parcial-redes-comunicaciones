#!/bin/sh
set -e

echo "================================================"
echo "Inicializador de plantilla Joomla - UMNG Portal"
echo "================================================"

export PGPASSWORD="$POSTGRES_PASSWORD"

echo "Esperando la instalación de Joomla..."

while true; do

    TABLE_NAME=$(psql \
        -h database \
        -U "$POSTGRES_USER" \
        -d "$POSTGRES_DB" \
        -At \
        -c "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = 'public'
            AND table_name LIKE '%_template_styles'
            LIMIT 1;" 2>/dev/null || true)

    if [ -n "$TABLE_NAME" ]; then
        echo "Tabla de estilos encontrada: $TABLE_NAME"
        break
    fi

    echo "Joomla aún no está listo..."
    sleep 2
done


echo "Esperando instalación de UMNG Portal..."

while true; do

    TEMPLATE_EXISTS=$(psql \
        -h database \
        -U "$POSTGRES_USER" \
        -d "$POSTGRES_DB" \
        -At \
        -c "SELECT COUNT(*)
            FROM \"$TABLE_NAME\"
            WHERE template = 'umngportal'
            AND client_id = 0;" 2>/dev/null || echo "0")

    if [ "$TEMPLATE_EXISTS" = "1" ]; then
        break
    fi

    echo "La plantilla aún no está instalada..."
    sleep 2
done


echo "Activando UMNG Portal..."

psql \
    -h database \
    -U "$POSTGRES_USER" \
    -d "$POSTGRES_DB" \
    -c "
        UPDATE \"$TABLE_NAME\"
        SET home = '0'
        WHERE client_id = 0;

        UPDATE \"$TABLE_NAME\"
        SET home = '1'
        WHERE template = 'umngportal'
        AND client_id = 0;
    "


echo "================================================"
echo "UMNG Portal activado correctamente."
echo "================================================"