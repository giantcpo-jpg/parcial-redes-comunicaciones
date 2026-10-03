# Despliegue Multi-Contenedor: Joomla, PostgreSQL 16, Grafana, Jupyter y Nginx

**Asignatura:** Comunicaciones - Ingeniería Mecatrónica  
**Institución:** Universidad Militar Nueva Granada  

---

## Descripción del proyecto

Este proyecto implementa una arquitectura de servicios utilizando **Docker Compose**, integrando Joomla, PostgreSQL 16, Grafana, JupyterLab y Nginx.

La infraestructura fue diseñada para permitir un despliegue automatizado a partir de un repositorio limpio. Después de clonar el proyecto y crear el archivo de variables de entorno, Docker Compose se encarga de crear las redes, volúmenes, contenedores y dependencias necesarias.

Los servicios implementados son:

- **Joomla:** sistema de gestión de contenidos CMS.
- **PostgreSQL 16:** base de datos utilizada por Joomla y para análisis.
- **Grafana:** visualización y monitoreo de información almacenada en PostgreSQL.
- **JupyterLab:** entorno para análisis y consulta de datos.
- **Nginx:** Reverse Proxy y punto de entrada a los servicios web.

---

## Arquitectura

La arquitectura general del sistema es:

```text
                         HOST
                          |
                       Puerto 80
                          |
                    +-------------+
                    |    NGINX    |
                    |Reverse Proxy|
                    +-------------+
                          |
                     frontend_net
                 /         |         \
                /          |          \
           Joomla       Grafana     Jupyter
              |            |           |
              +------------+-----------+
                           |
                      backend_net
                           |
                    PostgreSQL 16
```

Nginx funciona como el único servicio que publica directamente un puerto hacia el host.

Los demás servicios se comunican mediante redes internas de Docker.

---

## Servicios

| Servicio | Función | Puerto interno | Acceso |
|---|---|---:|---|
| Nginx | Reverse Proxy | 80 | `http://localhost/` |
| Joomla | CMS | 80 | `http://localhost/` |
| Grafana | Monitoreo y dashboards | 3000 | `http://localhost/grafana/` |
| JupyterLab | Análisis de datos | 8888 | `http://localhost/jupyter/` |
| PostgreSQL 16 | Base de datos | 5432 | Solo acceso interno |

PostgreSQL no publica el puerto `5432` hacia el computador anfitrión.

---

# Requisitos

Antes de iniciar el proyecto se requiere tener instalado:

- Git
- Docker Desktop
- Docker Compose

Para comprobar que Docker está instalado correctamente:

```bash
docker --version
docker compose version
```

---

# Despliegue

## Windows - PowerShell

### 1. Clonar el repositorio

```powershell
git clone https://github.com/giantcpo-jpg/parcial-redes-comunicaciones.git
```

### 2. Entrar al proyecto

```powershell
cd parcial-redes-comunicaciones
```

### 3. Crear el archivo de variables de entorno

```powershell
Copy-Item .env.example .env
```

### 4. Levantar la infraestructura

```powershell
docker compose up -d
```

### 5. Verificar los contenedores

```powershell
docker compose ps
```

---

## Linux / macOS

### 1. Clonar el repositorio

```bash
git clone https://github.com/giantcpo-jpg/parcial-redes-comunicaciones.git
```

### 2. Entrar al proyecto

```bash
cd parcial-redes-comunicaciones
```

### 3. Crear el archivo de variables de entorno

```bash
cp .env.example .env
```

### 4. Levantar la infraestructura

```bash
docker compose up -d
```

### 5. Verificar los contenedores

```bash
docker compose ps
```

---

# Acceso a los servicios

Una vez iniciada la infraestructura se puede acceder a los servicios desde el navegador.

## Joomla

Portal principal:

```text
http://localhost/
```

Panel administrativo:

```text
http://localhost/administrator
```

## Grafana

```text
http://localhost/grafana/
```

## JupyterLab

```text
http://localhost/jupyter/
```

PostgreSQL no posee acceso público directo y se comunica únicamente mediante la red interna de Docker.

---

# Credenciales de demostración

## Joomla

```text
Usuario: admin
Contraseña: AdminUMNG2026!!
```

## Grafana

```text
Usuario: admin
Contraseña: admin
```

Estas credenciales corresponden exclusivamente al entorno académico de demostración.

En un despliegue de producción deben ser reemplazadas por credenciales seguras.

---

# Variables de entorno

El proyecto contiene el archivo:

```text
.env.example
```

Este archivo funciona como plantilla de configuración.

Antes de iniciar el sistema se crea:

```text
.env
```

En Windows:

```powershell
Copy-Item .env.example .env
```

En Linux o macOS:

```bash
cp .env.example .env
```

Las variables utilizadas son:

```env
POSTGRES_DB=joomladb
POSTGRES_USER=joomlauser
POSTGRES_PASSWORD=joomlapassword

GF_SECURITY_ADMIN_USER=admin
GF_SECURITY_ADMIN_PASSWORD=admin

JOOMLA_SITE_NAME=Sitio Institucional UMNG
JOOMLA_ADMIN_USER=Administrador UMNG
JOOMLA_ADMIN_USERNAME=admin
JOOMLA_ADMIN_PASSWORD=AdminUMNG2026!!
JOOMLA_ADMIN_EMAIL=admin@localhost.com
```

El archivo `.env` debe permanecer únicamente de forma local y no debe almacenarse en el repositorio.

---

# Instalación automática de Joomla

La instalación de Joomla se realiza automáticamente durante el primer despliegue.

El proceso es el siguiente:

```text
docker compose up -d
        |
        v
PostgreSQL inicia
        |
        v
Docker verifica que PostgreSQL esté saludable
        |
        v
Joomla inicia
        |
        v
Joomla se conecta con PostgreSQL
        |
        v
Se ejecuta la instalación automática
        |
        v
Se crean las tablas de Joomla
        |
        v
Joomla queda disponible mediante Nginx
```

No es necesario utilizar el instalador web de Joomla.

En una instalación limpia actualmente se generan **76 tablas** en PostgreSQL.

Estas tablas pueden verificarse mediante:

```powershell
docker compose exec database psql -U joomlauser -d joomladb -c "\dt"
```

---

# Redes Docker

El proyecto utiliza dos redes independientes.

## frontend_net

Se utiliza para la comunicación de los servicios web.

Los servicios conectados son:

```text
Nginx
Joomla
Grafana
Jupyter
```

## backend_net

Se utiliza para la comunicación interna con los servicios de backend.

Los servicios que requieren acceso a PostgreSQL se comunican mediante esta red.

PostgreSQL no expone directamente su puerto al computador anfitrión.

---

# Persistencia de datos

El proyecto utiliza volúmenes Docker para conservar la información.

Los volúmenes definidos son:

```text
pg_data
grafana_data
joomla_data
```

Sus funciones son:

```text
pg_data       -> Datos almacenados en PostgreSQL
grafana_data  -> Configuración y datos de Grafana
joomla_data   -> Archivos y configuración de Joomla
```

Si se ejecuta:

```bash
docker compose down
```

los contenedores se eliminan, pero los datos almacenados en los volúmenes permanecen.

---

# Reinicio completamente limpio

Para eliminar contenedores, redes y volúmenes:

```bash
docker compose down -v
```

Después se puede reconstruir completamente la infraestructura ejecutando:

```bash
docker compose up -d
```

> **Advertencia:** `docker compose down -v` elimina los datos almacenados en los volúmenes del proyecto.

Este comando es útil para comprobar que el despliegue realmente puede realizarse desde cero.

---

# Verificación del despliegue

Para comprobar el estado de los servicios:

```bash
docker compose ps
```

Se deben visualizar los siguientes contenedores:

```text
postgres_db
joomla_app
grafana_app
jupyter_notebook
nginx_proxy
```

PostgreSQL debe alcanzar el estado:

```text
healthy
```

JupyterLab también debe alcanzar el estado:

```text
healthy
```

Nginx debe mostrar el puerto:

```text
0.0.0.0:80->80/tcp
```

---

# Verificación de PostgreSQL

Para comprobar las tablas creadas por Joomla:

```powershell
docker compose exec database psql -U joomlauser -d joomladb -c "\dt"
```

Durante la validación del proyecto se obtuvieron:

```text
76 rows
```

confirmando que Joomla realizó correctamente la creación automática de su estructura de base de datos.

---

# Logs y diagnóstico

Docker Compose permite revisar individualmente los registros de cada servicio.

## Joomla

```bash
docker compose logs joomla
```

## PostgreSQL

```bash
docker compose logs database
```

## Grafana

```bash
docker compose logs grafana
```

## JupyterLab

```bash
docker compose logs jupyter
```

## Nginx

```bash
docker compose logs nginx
```

Para observar todos los logs:

```bash
docker compose logs
```

Para observarlos en tiempo real:

```bash
docker compose logs -f
```

---

# Validación del archivo Docker Compose

Antes de iniciar la infraestructura se puede comprobar la configuración mediante:

```bash
docker compose config
```

Si no se presentan errores, Docker Compose reconoce correctamente la definición de los servicios, redes, volúmenes y variables de entorno.

---

# Zero-Touch Deployment

El proyecto implementa un esquema de despliegue automatizado.

Una vez instalados Git y Docker, el usuario únicamente necesita clonar el repositorio, crear el archivo `.env` y ejecutar:

```bash
docker compose up -d
```

A partir de este punto Docker Compose se encarga automáticamente de:

```text
Crear las redes Docker
        |
Crear los volúmenes
        |
Iniciar PostgreSQL
        |
Verificar la disponibilidad de PostgreSQL
        |
Instalar Joomla
        |
Crear las tablas de Joomla
        |
Iniciar Grafana
        |
Iniciar JupyterLab
        |
Iniciar Nginx
        |
Habilitar el acceso mediante localhost
```

Esto reduce la intervención manual y permite reproducir la infraestructura en otro computador a partir del repositorio.

---

# Prueba realizada desde un clon limpio

Para validar la reproducibilidad del proyecto se realizó una prueba partiendo de un clon nuevo del repositorio.

El procedimiento utilizado fue:

```text
Clonar el repositorio desde GitHub
        |
Crear .env a partir de .env.example
        |
Eliminar posibles volúmenes anteriores
        |
Ejecutar docker compose up -d
        |
Crear una nueva instancia PostgreSQL
        |
Instalar Joomla automáticamente
        |
Crear 76 tablas
        |
Iniciar Grafana
        |
Iniciar JupyterLab
        |
Iniciar Nginx
        |
Comprobar los servicios desde el navegador
```

Los servicios Joomla, PostgreSQL, Grafana, JupyterLab y Nginx iniciaron correctamente.

---

# Detener el sistema

Para detener los servicios conservando los datos:

```bash
docker compose down
```

Para eliminar también los volúmenes y realizar posteriormente una instalación completamente limpia:

```bash
docker compose down -v
```

---

# Estructura principal del proyecto

```text
parcial-redes-comunicaciones/
│
├── grafana/
│   └── provisioning/
│
├── jupyter/
│   └── notebooks/
│
├── .env.example
├── .gitignore
├── docker-compose.yml
├── nginx.conf
└── README.md
```

---

# Autores

Proyecto académico desarrollado para la asignatura **Comunicaciones** del programa de **Ingeniería Mecatrónica** de la **Universidad Militar Nueva Granada**.