\# Despliegue Multi-Contenedor: Joomla, PostgreSQL 16, Grafana, Jupyter y Nginx



\*\*Asignatura:\*\* Comunicaciones - Ingeniería Mecatrónica  

\*\*Institución:\*\* Universidad Militar Nueva Granada  



Este proyecto implementa una arquitectura de microservicios orquestada con \*\*Docker Compose\*\*, cumpliendo con un despliegue desatendido (\*Zero-Touch Deployment\*), aislamiento estricto de redes en Capa 2/Capa 3 y aprovisionamiento declarativo de métricas y análisis de datos.



\---



\## 🔗 Enlaces Directos de Acceso (Servidos por Nginx Reverse Proxy)



Una vez iniciada la infraestructura, accede a los servicios a través del puerto 80:



\* \*\*Portal Joomla (CMS):\*\* \[http://localhost/](http://localhost/)

\* \*\*Grafana (Dashboards \& Métricas):\*\* \[http://localhost/grafana/](http://localhost/grafana/)

\* \*\*Jupyter Lab (Análisis de Datos):\*\* \[http://localhost/jupyter/](http://localhost/jupyter/)



> \*\*Nota:\*\* La base de datos \*\*PostgreSQL 16\*\* opera de manera interna en `database:5432` y está aislada en la red privada `backend\_net` sin exposición pública de puertos.



\---



\## 🚀 Instrucciones de Despliegue Rápido



Para desplegar la infraestructura completa en una máquina limpia, ejecuta la siguiente secuencia de comandos en la terminal:



```bash

\# 1. Clonar el repositorio

git clone <URL\_DE\_TU\_REPOSITORIO>

cd parcial-redes-comunicaciones



\# 2. Copiar la plantilla de variables de entorno

cp .env.example .env



\# 3. Desplegar el clúster de contenedores

docker compose up -d

