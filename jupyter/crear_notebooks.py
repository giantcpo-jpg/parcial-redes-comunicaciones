import nbformat as nbf
from pathlib import Path

carpeta = Path("/home/jovyan/work")

# ==========================================================
# NOTEBOOK 1: BIENVENIDA
# ==========================================================

bienvenida = nbf.v4.new_notebook()

bienvenida.cells = [
    nbf.v4.new_markdown_cell(
"""# UMNG - Plataforma de Comunicaciones

## Ingeniería Mecatrónica

Este entorno Jupyter hace parte de una arquitectura implementada con Docker Compose.

Servicios principales:

- Joomla: aplicación web.
- PostgreSQL: base de datos.
- Grafana: monitoreo.
- JupyterLab: análisis de datos.
- Nginx: proxy inverso.

Abra el notebook:

`01_Analisis_Plataforma_UMNG.ipynb`

y seleccione **Run > Run All Cells**.
"""
    )
]

bienvenida.metadata = {
    "kernelspec": {
        "display_name": "Python 3 (ipykernel)",
        "language": "python",
        "name": "python3"
    }
}

nbf.write(
    bienvenida,
    carpeta / "00_Bienvenida_UMNG.ipynb"
)


# ==========================================================
# NOTEBOOK 2: ANALISIS
# ==========================================================

nb = nbf.v4.new_notebook()

nb.cells = [

    nbf.v4.new_markdown_cell(
"""# UMNG - Análisis de Plataforma

## Joomla + PostgreSQL + Docker

Este notebook consulta directamente la base de datos PostgreSQL utilizada por Joomla.

Los datos mostrados son obtenidos en tiempo real desde la infraestructura Docker.
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 1. Conexión con PostgreSQL"""
    ),

    nbf.v4.new_code_cell(
"""import os
import pandas as pd
import matplotlib.pyplot as plt
import sqlalchemy as sa
from sqlalchemy.engine import URL

host = os.getenv("POSTGRES_HOST", "database")
puerto = int(os.getenv("POSTGRES_PORT", "5432"))
bd = os.getenv("POSTGRES_DB", "joomladb")
usuario = os.getenv("POSTGRES_USER", "joomlauser")
password = os.getenv("POSTGRES_PASSWORD", "joomlapassword")

url = URL.create(
    "postgresql+psycopg2",
    username=usuario,
    password=password,
    host=host,
    port=puerto,
    database=bd
)

engine = sa.create_engine(url)

with engine.connect() as conexion:
    version = conexion.execute(
        sa.text("SELECT version();")
    ).scalar()

print("Conexion exitosa a PostgreSQL")
print("Base de datos:", bd)
print("Servidor:", host)
print(version.split(",")[0])
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 2. Detección automática de Joomla"""
    ),

    nbf.v4.new_code_cell(
"""tablas = pd.read_sql(
    '''
    SELECT table_name
    FROM information_schema.tables
    WHERE table_schema = 'public'
    ORDER BY table_name;
    ''',
    engine
)

lista_tablas = set(tablas["table_name"])

prefijo = None

for tabla in lista_tablas:
    if tabla.endswith("_extensions"):

        candidato = tabla[:-len("extensions")]

        necesarias = {
            candidato + "users",
            candidato + "content",
            candidato + "session",
            candidato + "extensions"
        }

        if necesarias.issubset(lista_tablas):
            prefijo = candidato
            break

if prefijo is None:
    raise Exception("No se pudo detectar Joomla")

print("Joomla detectado")
print("Prefijo:", prefijo)
print("Total de tablas:", len(tablas))
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 3. Resumen general"""
    ),

    nbf.v4.new_code_cell(
"""def valor(sql):
    with engine.connect() as conexion:
        return conexion.execute(sa.text(sql)).scalar()

datos = {
    "Indicador": [
        "Estado PostgreSQL",
        "Tablas",
        "Usuarios Joomla",
        "Articulos publicados",
        "Extensiones",
        "Sesiones ultimos 15 min",
        "Tamano de la BD"
    ],

    "Valor": [
        "ONLINE",

        valor(
            "SELECT COUNT(*) "
            "FROM information_schema.tables "
            "WHERE table_schema='public';"
        ),

        valor(
            f'SELECT COUNT(*) FROM "{prefijo}users";'
        ),

        valor(
            f'SELECT COUNT(*) FROM "{prefijo}content" '
            f'WHERE state=1;'
        ),

        valor(
            f'SELECT COUNT(*) FROM "{prefijo}extensions";'
        ),

        valor(
            f'SELECT COUNT(*) FROM "{prefijo}session" '
            f'WHERE time >= EXTRACT(EPOCH FROM NOW())::bigint - 900;'
        ),

        valor(
            "SELECT pg_size_pretty("
            "pg_database_size(current_database()));"
        )
    ]
}

resumen = pd.DataFrame(datos)

resumen
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 4. Distribución de extensiones Joomla"""
    ),

    nbf.v4.new_code_cell(
"""extensiones = pd.read_sql(
    f'''
    SELECT
        type AS tipo,
        COUNT(*) AS cantidad
    FROM "{prefijo}extensions"
    GROUP BY type
    ORDER BY cantidad DESC;
    ''',
    engine
)

display(extensiones)

plt.figure(figsize=(10,5))

plt.bar(
    extensiones["tipo"],
    extensiones["cantidad"]
)

plt.title("Distribucion de extensiones Joomla")
plt.xlabel("Tipo")
plt.ylabel("Cantidad")
plt.xticks(rotation=45)
plt.grid(axis="y", alpha=0.3)
plt.tight_layout()
plt.show()
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 5. Tablas con mayor tamaño"""
    ),

    nbf.v4.new_code_cell(
"""tamano = pd.read_sql(
    '''
    SELECT
        relname AS tabla,
        ROUND(
            pg_total_relation_size(relid)
            / 1024.0
            / 1024.0,
            3
        ) AS mb
    FROM pg_catalog.pg_statio_user_tables
    ORDER BY pg_total_relation_size(relid) DESC
    LIMIT 10;
    ''',
    engine
)

display(tamano)

grafica = tamano.sort_values("mb")

plt.figure(figsize=(10,6))

plt.barh(
    grafica["tabla"],
    grafica["mb"]
)

plt.title("Top 10 tablas por tamaño")
plt.xlabel("MB")
plt.ylabel("Tabla")
plt.tight_layout()
plt.show()
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 6. Usuarios Joomla"""
    ),

    nbf.v4.new_code_cell(
"""usuarios = pd.read_sql(
    f'''
    SELECT
        id,
        name AS nombre,
        username AS usuario,
        "registerDate" AS fecha_registro
    FROM "{prefijo}users"
    ORDER BY id;
    ''',
    engine
)

usuarios
"""
    ),

    nbf.v4.new_markdown_cell(
"""## 7. Actividad Joomla"""
    ),

    nbf.v4.new_code_cell(
"""tabla_logs = prefijo + "action_logs"

if tabla_logs in lista_tablas:

    actividad = pd.read_sql(
        f'''
        SELECT
            log_date AS fecha,
            extension,
            user_id AS usuario,
            ip_address AS ip
        FROM "{tabla_logs}"
        ORDER BY log_date DESC
        LIMIT 20;
        ''',
        engine
    )

    if actividad.empty:
        print("Todavia no existen eventos registrados.")
    else:
        display(actividad)

else:
    print("La tabla action_logs no existe.")
"""
    ),

    nbf.v4.new_markdown_cell(
"""# Conclusión

Jupyter permite analizar directamente los datos almacenados por Joomla en PostgreSQL.

Grafana se utiliza para monitoreo visual continuo, mientras que Jupyter permite ejecutar consultas, procesar información con pandas y realizar análisis adicionales mediante Python.

Todos los servicios se comunican mediante las redes internas definidas en Docker Compose.
"""
    )
]

nb.metadata = {
    "kernelspec": {
        "display_name": "Python 3 (ipykernel)",
        "language": "python",
        "name": "python3"
    }
}

nbf.write(
    nb,
    carpeta / "01_Analisis_Plataforma_UMNG.ipynb"
)

print("========================================")
print("NOTEBOOKS CREADOS CORRECTAMENTE")
print("========================================")
print("00_Bienvenida_UMNG.ipynb")
print("01_Analisis_Plataforma_UMNG.ipynb")