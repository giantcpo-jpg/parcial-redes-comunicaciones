\# Informe Técnico: Arquitectura y Análisis del Modelo OSI

\*\*Asignatura:\*\* Comunicaciones - Ingeniería Mecatrónica  

\*\*Institución:\*\* Universidad Militar Nueva Granada  



\---



\## Sección 1: Topología y Flujo de Información



\### Diagrama de Arquitectura

El sistema se compone de 5 contenedores orquestados mediante Docker Compose, divididos en dos redes independientes (`frontend\_net` y `backend\_net`):



\- \*\*Nginx (Proxy Inverso):\*\* Publicado en la red del Host (Puerto `80:80`) en `frontend\_net`.

\- \*\*Joomla (CMS):\*\* Conectado a `frontend\_net` (para recibir tráfico de Nginx) y `backend\_net` (para comunicarse con PostgreSQL).

\- \*\*Jupyter Notebook:\*\* Conectado a `frontend\_net` y `backend\_net` (para ejecutar consultas analíticas sobre la BD).

\- \*\*Grafana:\*\* Conectado a `frontend\_net` y `backend\_net` (para consultar métricas de la BD PostgreSQL).

\- \*\*PostgreSQL 16:\*\* Aislado estrictamente en `backend\_net` (puerto TCP 5432) sin exposición pública.



\### Mecanismo de Recolección de Métricas

Grafana se aprovisiona automáticamente declarando un \*Data Source\* dirigido hacia el servicio `database:5432` a través de la red `backend\_net`. Los paneles ejecutan consultas SQL dinámicas directamente sobre el esquema `public` en PostgreSQL, reflejando métricas de actividad en tiempo real.



\---



\## Sección 2: Análisis Detallado del Modelo OSI en la Solución



\### 1. Capa 7 (Aplicación)

\- \*\*Inyección de Cabeceras HTTP:\*\* Nginx inyecta `Host`, `X-Real-IP`, `X-Forwarded-For` y `X-Forwarded-Proto` para conservar la identidad del cliente hacia los servicios backend.

\- \*\*WebSockets:\*\* Implementación de las cabeceras `Upgrade` y `Connection "upgrade"` en Nginx para mantener el canal bidireccional entre el navegador y el Kernel de Python en Jupyter.

\- \*\*Protocolo PostgreSQL:\*\* Comunicación cliente/servidor sobre TCP entre Joomla/Jupyter/Grafana y la base de datos usando el driver PDO pgsql y `psycopg2`.



\### 2. Capa 4 (Transporte)

\- \*\*Puertos TCP Involucrados:\*\* `80` (Nginx), `5432` (PostgreSQL), `8888` (Jupyter), `3000` (Grafana), `80` (Joomla interno).

\- \*\*Conexiones Persistentes:\*\* Se utiliza TCP Keep-Alive y Connection Pooling para mantener abiertos los sockets de conexión entre Joomla/Grafana y PostgreSQL.



\### 3. Capa 3 (Red)

\- \*\*Aislamiento de Redes:\*\* Separación lógica de IP entre `frontend\_net` y `backend\_net`. El contenedor `database` carece de interfaz en `frontend\_net`, evitando accesos no autorizados.

\- \*\*DNS Embebido de Docker (127.0.0.11):\*\* Resolución de nombres en tiempo real. Los contenedores resuelven nombres de host como `database` o `joomla\_app` a sus direcciones IP privadas virtuales sin IP estáticas.



\### 4. Capa 2 (Enlace de Datos)

\- \*\*Interfaces Virtuales (veth\*):\*\* Cada contenedor posee un par de interfaces Ethernet virtuales que conectan el espacio de nombres (\*namespace\*) del contenedor con el bridge virtual del host (`br-\*`).

\- \*\*Resolución ARP:\*\* La comunicación dentro de `frontend\_net` y `backend\_net` resuelve direcciones MAC mediante peticiones ARP transmitidas en el puente virtual.



\---



\## Sección 3: Guía de Verificación



1\. Clonar el repositorio y copiar variables de entorno:

&#x20;  ```bash

&#x20;  cp .env.example .env

&#x20;  docker compose up -d

