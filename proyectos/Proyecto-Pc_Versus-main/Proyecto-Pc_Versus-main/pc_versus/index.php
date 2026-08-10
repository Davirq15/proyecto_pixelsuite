<?php
// Inicia la sesión para poder acceder a los datos del usuario logueado.
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PC VERSUS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
</head>

<body class="main">

<header class="topbar">
    <a class="brand" href="index.php">PC VERSUS</a>

    <div class="topbar-actions">
        <?php if(!isset($_SESSION["usuario"])): ?>
            <a class="btn-login secondary" href="register.php">Crear cuenta</a>
            <a class="btn-login" href="login.php">Iniciar sesión</a>
        <?php else: ?>
            <span class="user-badge">
                Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>
            </span>
            <a class="btn-login secondary" href="logout.php">Cerrar sesión</a>
        <?php endif; ?>
    </div>
</header>

<main class="hero-layout">
    <section class="hero-copy">
        <p class="eyebrow">Comparador interactivo de hardware</p>
        <h1>Arma el versus definitivo entre tus componentes favoritos.</h1>
        <p class="hero-text">
            Compara CPU, GPU y RAM en una interfaz clara, rápida y con estilo gamer.
            Ideal para practicar, aprender hardware o decidir tu próxima compra.
        </p>

        <div class="hero-actions">
            <a class="boton-iniciar" href="comparador.php">Entrar al comparador</a>

            <?php if(!isset($_SESSION["usuario"])): ?>
                <a class="text-link" href="register.php">Empieza creando tu perfil</a>
            <?php endif; ?>
        </div>

        <div class="stats-grid">
            <article class="stat-card">
                <strong>3</strong>
                <span>Categorías listas para comparar</span>
            </article>

            <article class="stat-card">
                <strong>UI</strong>
                <span>Diseño renovado y adaptable a celular</span>
            </article>

            <article class="stat-card">
                <strong>FAST</strong>
                <span>Selecciona dos piezas y ve el ganador al instante</span>
            </article>
        </div>
    </section>

    <section class="hero-visual">
        <div class="visual-card">
            <div class="visual-glow"></div>
            <img src="css/img/versus.png" class="logo" alt="Logo de PC Versus">

            <div class="feature-list">
                <div>
                    <span class="feature-tag">CPU</span>
                    <p>Potencia de procesamiento</p>
                </div>

                <div>
                    <span class="feature-tag">GPU</span>
                    <p>Rendimiento gráfico</p>
                </div>

                <div>
                    <span class="feature-tag">RAM</span>
                    <p>Velocidad y multitarea</p>
                </div>
            </div>
        </div>
    </section>
</main>

<section class="info-strip">
    <article>
        <h2>Visual claro</h2>
        <p>Todo está acomodado para que el usuario entienda rápido qué comparar y cómo avanzar.</p>
    </article>

    <article>
        <h2>Look gamer</h2>
        <p>Fondos, brillos y tipografías propias para que el proyecto se sienta más pro.</p>
    </article>

    <article>
        <h2>Mejor experiencia</h2>
        <p>Botones consistentes, espaciado limpio y mejor lectura tanto en desktop como en móvil.</p>
    </article>
</section>

<footer class="footer">
    <div class="footer-name">
        <h3>PC VERSUS</h3>
        <p>Comparador de PC con estilo arcade-tech.</p>
    </div>

    <div class="footer-links">
        <a href="comparador.php">Comparador</a>
        <a href="login.php">Acceder</a>
        <a href="register.php">Registro</a>
    </div>

    <p>&copy; 2026 PC Versus - Todos los derechos reservados</p>
</footer>

</body>
</html>
