<header>
    <link rel="stylesheet" href="public/styles/header.css">
    <!-- Barra de navegación Bootstrap con fixed-top -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary px-3">
        <a class="navbar-brand fw-bold" href="index.php">TecnoMundo</a>

        <!-- Botón para móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido del nav -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="public/productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="public/ventas">Ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="public/compras">Compras</a>
                </li>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="public/admin/h_registro.php">registrar</a>
                </li>
            </ul>

            <!-- Barra de búsqueda -->
            <form class="d-flex" action="buscar.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Buscar..." aria-label="Buscar">
                <button class="btn btn-outline-light" type="submit">🔍</button>
            </form>
        </div>
    </nav>
</header>