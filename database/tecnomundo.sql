use tecnoMundo;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'vendedor') DEFAULT 'admin',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productos (
    codigo_producto VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_costo DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    marca VARCHAR(50),
    categoria VARCHAR(50),
    stock_actual INT NOT NULL
);


CREATE TABLE IF NOT EXISTS compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    fecha_compra DATETIME NOT NULL,
    proveedor VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS detalle_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT NOT NULL,
    codigo_producto VARCHAR(20) NOT NULL,
    cantidad_comprada INT NOT NULL,
    precio_compra DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_compra) REFERENCES compras(id_compra),
    FOREIGN KEY (codigo_producto) REFERENCES productos(codigo_producto)
);

CREATE TABLE IF NOT EXISTS ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATETIME NOT NULL,
    cliente VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS detalle_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    codigo_producto VARCHAR(20) NOT NULL,
    cantidad INT NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta),
    FOREIGN KEY (codigo_producto) REFERENCES productos(codigo_producto)
);