<?php
defined('_JEXEC') or die;
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template.css">
</head>

<body>

<header class="topbar">
    <div class="container nav">
        <div class="brand">
            <div class="brand-mark">UMNG</div>

            <div>
                <strong>Ingeniería Mecatrónica</strong>
                <span>Comunicaciones · Infraestructura Docker</span>
            </div>
        </div>

        <div class="system-status">
            <span class="status-dot"></span>
            SISTEMA OPERATIVO
        </div>
    </div>
</header>


<main>

<section class="hero">

    <div class="hero-grid container">

        <div class="hero-content">

            <div class="eyebrow">
                PROYECTO DE COMUNICACIONES
            </div>

            <h1>
                Infraestructura
                <span>Multi-Contenedor</span>
            </h1>

            <p class="hero-description">
                Plataforma desplegada mediante Docker Compose que integra
                gestión de contenidos, almacenamiento de datos,
                monitoreo, análisis y publicación mediante un
                Reverse Proxy centralizado.
            </p>

            <div class="hero-actions">

                <a class="button primary" href="/grafana/">
                    Abrir Grafana
                </a>

                <a class="button secondary" href="/jupyter/">
                    Abrir JupyterLab
                </a>

            </div>

            <div class="metrics">

                <div class="metric">
                    <strong>5</strong>
                    <span>Servicios</span>
                </div>

                <div class="metric">
                    <strong>2</strong>
                    <span>Redes Docker</span>
                </div>

                <div class="metric">
                    <strong>76</strong>
                    <span>Tablas Joomla</span>
                </div>

                <div class="metric">
                    <strong>80</strong>
                    <span>Puerto público</span>
                </div>

            </div>

        </div>


        <div class="architecture-card">

            <div class="card-title">
                ARQUITECTURA DEL SISTEMA
            </div>

            <div class="arch-host">
                HOST
                <small>localhost:80</small>
            </div>

            <div class="connector vertical"></div>

            <div class="arch-nginx">
                NGINX
                <small>Reverse Proxy</small>
            </div>

            <div class="connector vertical"></div>

            <div class="network-label">
                frontend_net
            </div>

            <div class="services-row">

                <div class="service-node">
                    <span>J</span>
                    Joomla
                </div>

                <div class="service-node">
                    <span>G</span>
                    Grafana
                </div>

                <div class="service-node">
                    <span>J</span>
                    Jupyter
                </div>

            </div>

            <div class="connector vertical"></div>

            <div class="network-label backend">
                backend_net
            </div>

            <div class="arch-database">
                PostgreSQL 16
                <small>database:5432</small>
            </div>

        </div>

    </div>

</section>


<section class="services">

    <div class="container">

        <div class="section-header">

            <div>
                <div class="eyebrow">
                    INFRAESTRUCTURA
                </div>

                <h2>Servicios desplegados</h2>
            </div>

            <p>
                Cada componente se ejecuta de manera independiente
                y se comunica mediante redes privadas de Docker.
            </p>

        </div>


        <div class="service-grid">

            <article class="service-card">

                <div class="service-icon">J</div>

                <div class="service-info">
                    <span class="service-type">CMS</span>
                    <h3>Joomla</h3>

                    <p>
                        Portal institucional y sistema de gestión
                        de contenidos del proyecto.
                    </p>

                    <div class="service-footer">
                        <span class="online">
                            ● ONLINE
                        </span>

                        <span>80/tcp</span>
                    </div>
                </div>

            </article>


            <article class="service-card">

                <div class="service-icon">P</div>

                <div class="service-info">
                    <span class="service-type">DATABASE</span>
                    <h3>PostgreSQL 16</h3>

                    <p>
                        Base de datos aislada en la red interna
                        utilizada por Joomla y los sistemas de análisis.
                    </p>

                    <div class="service-footer">
                        <span class="online">
                            ● HEALTHY
                        </span>

                        <span>5432/tcp</span>
                    </div>
                </div>

            </article>


            <article class="service-card">

                <div class="service-icon">G</div>

                <div class="service-info">
                    <span class="service-type">MONITORING</span>
                    <h3>Grafana</h3>

                    <p>
                        Dashboard de monitoreo para visualizar
                        información y métricas de PostgreSQL.
                    </p>

                    <div class="service-footer">
                        <span class="online">
                            ● ONLINE
                        </span>

                        <a href="/grafana/">Abrir →</a>
                    </div>
                </div>

            </article>


            <article class="service-card">

                <div class="service-icon">J</div>

                <div class="service-info">
                    <span class="service-type">ANALYTICS</span>
                    <h3>JupyterLab</h3>

                    <p>
                        Entorno interactivo utilizado para consultar,
                        procesar y analizar los datos del sistema.
                    </p>

                    <div class="service-footer">
                        <span class="online">
                            ● HEALTHY
                        </span>

                        <a href="/jupyter/">Abrir →</a>
                    </div>
                </div>

            </article>


            <article class="service-card">

                <div class="service-icon">N</div>

                <div class="service-info">
                    <span class="service-type">GATEWAY</span>
                    <h3>Nginx</h3>

                    <p>
                        Reverse Proxy responsable de centralizar
                        el acceso externo a los servicios web.
                    </p>

                    <div class="service-footer">
                        <span class="online">
                            ● ONLINE
                        </span>

                        <span>localhost:80</span>
                    </div>
                </div>

            </article>

        </div>

    </div>

</section>


<section class="features">

    <div class="container">

        <div class="section-header">

            <div>
                <div class="eyebrow">
                    IMPLEMENTACIÓN
                </div>

                <h2>Características principales</h2>
            </div>

        </div>


        <div class="feature-grid">

            <div class="feature">
                <div class="feature-number">01</div>

                <h3>Zero-Touch Deployment</h3>

                <p>
                    La infraestructura completa se construye mediante
                    un único comando de Docker Compose.
                </p>
            </div>


            <div class="feature">
                <div class="feature-number">02</div>

                <h3>Aislamiento de red</h3>

                <p>
                    PostgreSQL permanece sin exposición directa
                    hacia el computador anfitrión.
                </p>
            </div>


            <div class="feature">
                <div class="feature-number">03</div>

                <h3>Persistencia</h3>

                <p>
                    Los datos críticos se almacenan mediante
                    volúmenes administrados por Docker.
                </p>
            </div>


            <div class="feature">
                <div class="feature-number">04</div>

                <h3>Observabilidad</h3>

                <p>
                    Grafana y Jupyter permiten supervisar y analizar
                    la información generada por la infraestructura.
                </p>
            </div>

        </div>

    </div>

</section>


<section class="access">

    <div class="container access-box">

        <div>

            <div class="eyebrow">
                ACCESO RÁPIDO
            </div>

            <h2>Explorar la infraestructura</h2>

            <p>
                Acceso directo a las herramientas desplegadas
                mediante el Reverse Proxy.
            </p>

        </div>

        <div class="access-buttons">

            <a href="/administrator/" class="button secondary">
                Administrar Joomla
            </a>

            <a href="/grafana/" class="button secondary">
                Dashboard Grafana
            </a>

            <a href="/jupyter/" class="button primary">
                Abrir JupyterLab
            </a>

        </div>

    </div>

</section>


</main>


<footer>

    <div class="container footer-content">

        <div>
            <strong>Universidad Militar Nueva Granada</strong>
            <span>Ingeniería Mecatrónica · Comunicaciones</span>
        </div>

        <div>
            Docker Compose · Joomla · PostgreSQL · Grafana · Jupyter · Nginx
        </div>

    </div>

</footer>

</body>
</html>