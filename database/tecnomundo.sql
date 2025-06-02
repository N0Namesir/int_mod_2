use tecnoMundo;

CREATE TABLE if not exists usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Hasheada con password_hash()
    rol ENUM('admin', 'user') NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nombre_usuario, password, rol)
VALUES (
    'admin',
    '123',  -- Ejemplo de hash bcrypt
    'admin'
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
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    codigo_producto VARCHAR(20) NOT NULL,
    cantidad_vendida INT NOT NULL CHECK (cantidad_vendida > 0),
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta),
    FOREIGN KEY (codigo_producto) REFERENCES productos(codigo_producto)
);




INSERT INTO ventas (fecha_venta, cliente) 
VALUES (NOW(), 'Carlos López');



INSERT INTO detalle_venta (id_venta, codigo_producto, cantidad_vendida, precio_unitario) 
VALUES 
(1, 'P010', 2, 400.00),
(1, 'P022', 1, 20.00);

INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P006', 'Smartphone X', 'Smartphone de alta gama con pantalla AMOLED', 150.00, 250.00, 'MarcaF', 'Electrónica', 80);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P007', 'Tablet 10 Pulgadas', 'Tablet ideal para entretenimiento y trabajo', 100.00, 180.00, 'MarcaG', 'Electrónica', 60);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P008', 'Mouse Inalámbrico', 'Mouse ergonómico con conectividad Bluetooth', 10.00, 18.00, 'MarcaH', 'Accesorios', 120);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P009', 'Teclado Mecánico', 'Teclado retroiluminado con switches azules', 30.00, 50.00, 'MarcaI', 'Accesorios', 70);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P010', 'Monitor 24 Pulgadas', 'Monitor LED Full HD de 24 pulgadas', 120.00, 200.00, 'MarcaJ', 'Computadoras', 30);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P011', 'Impresora Multifunción', 'Impresora láser con funciones de escaneo y copiado', 80.00, 140.00, 'MarcaK', 'Electrónica', 15);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P012', 'Cámara de Seguridad', 'Cámara IP con visión nocturna', 60.00, 100.00, 'MarcaL', 'Electrónica', 25);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P013', 'Silla Gamer', 'Silla ergonómica con soporte lumbar', 70.00, 120.00, 'MarcaM', 'Muebles', 50);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P014', 'Escritorio de Oficina', 'Escritorio moderno con acabado en madera', 150.00, 250.00, 'MarcaN', 'Muebles', 20);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P015', 'Ventilador de Pie', 'Ventilador potente con control remoto', 30.00, 55.00, 'MarcaO', 'Electrodomésticos', 35);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P016', 'Aspiradora Robot', 'Aspiradora con sensores de detección de obstáculos', 200.00, 350.00, 'MarcaP', 'Electrodomésticos', 10);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P017', 'Bicicleta Estática', 'Bicicleta para entrenamientos en casa', 400.00, 600.00, 'MarcaQ', 'Deportes', 5);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P018', 'Cinta de Correr', 'Cinta de correr con múltiples velocidades', 500.00, 800.00, 'MarcaR', 'Deportes', 3);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P019', 'Pesas Ajustables', 'Set de pesas ajustables para entrenamiento', 50.00, 80.00, 'MarcaS', 'Deportes', 25);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P020', 'Banco de Pesas', 'Banco multifunción para ejercicios', 100.00, 150.00, 'MarcaT', 'Deportes', 15);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P021', 'Gafas de Sol UV', 'Gafas de sol con protección UV 400', 15.00, 25.00, 'MarcaU', 'Accesorios', 200);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P022', 'Bolso de Cuero', 'Bolso elegante de cuero genuino', 40.00, 70.00, 'MarcaV', 'Accesorios', 80);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P023', 'Sombrero de Paja', 'Sombrero ligero ideal para el verano', 10.00, 20.00, 'MarcaW', 'Accesorios', 90);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P024', 'Cinturón de Moda', 'Cinturón de diseño moderno', 12.00, 22.00, 'MarcaX', 'Accesorios', 110);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P025', 'Collar Elegante', 'Collar con detalles sofisticados', 30.00, 60.00, 'MarcaY', 'Joyas', 40);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P026', 'Pulsera de Plata', 'Pulsera de plata esterlina', 25.00, 50.00, 'MarcaZ', 'Joyas', 50);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P027', 'Anillo de Oro', 'Anillo de oro con diseño exclusivo', 80.00, 160.00, 'MarcaAA', 'Joyas', 30);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P028', 'Batería Portátil', 'Powerbank de alta capacidad', 20.00, 35.00, 'MarcaAB', 'Electrónica', 75);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P029', 'Cargador Rápido', 'Cargador compatible con múltiples dispositivos', 8.00, 15.00, 'MarcaAC', 'Electrónica', 100);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P030', 'Memoria USB 64GB', 'Pendrive USB 3.0 de 64GB', 5.00, 10.00, 'MarcaAD', 'Computadoras', 150);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P031', 'Disco Duro Externo 1TB', 'Disco duro portátil de 1TB', 50.00, 90.00, 'MarcaAE', 'Computadoras', 60);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P032', 'Router WiFi', 'Router inalámbrico de alta velocidad', 40.00, 70.00, 'MarcaAF', 'Electrónica', 70);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P033', 'Proyector HD', 'Proyector Full HD para cine en casa', 100.00, 180.00, 'MarcaAG', 'Electrónica', 20);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P034', 'Altavoz Inteligente', 'Altavoz con asistente de voz integrado', 60.00, 110.00, 'MarcaAH', 'Electrónica', 55);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P035', 'Cafetera Automática', 'Cafetera programable para café fresco', 70.00, 130.00, 'MarcaAI', 'Electrodomésticos', 20);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P036', 'Licuadora de Alta Velocidad', 'Licuadora con motor potente para batidos', 50.00, 85.00, 'MarcaAJ', 'Electrodomésticos', 35);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P037', 'Set de Ollas', 'Juego de ollas antiadherentes', 80.00, 140.00, 'MarcaAK', 'Cocina', 50);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P038', 'Sartén Antiadherente', 'Sartén resistente y de fácil limpieza', 25.00, 45.00, 'MarcaAL', 'Cocina', 80);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P039', 'Cuchillo de Chef', 'Cuchillo profesional para cocina', 15.00, 30.00, 'MarcaAM', 'Cocina', 100);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P040', 'Microondas Digital', 'Microondas rápido con múltiples funciones', 90.00, 160.00, 'MarcaAN', 'Electrodomésticos', 30);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P041', 'Refrigerador con Congelador', 'Refrigerador moderno con amplio espacio', 300.00, 500.00, 'MarcaAO', 'Electrodomésticos', 10);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P042', 'Lámpara LED', 'Lámpara de bajo consumo y alta luminosidad', 20.00, 35.00, 'MarcaAP', 'Iluminación', 120);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P043', 'Ventana Inteligente', 'Sistema de ventana con control digital', 150.00, 250.00, 'MarcaAQ', 'Construcción', 5);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P044', 'Set de Pinceles de Arte', 'Juego completo de pinceles para artistas', 15.00, 30.00, 'MarcaAR', 'Arte', 60);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P045', 'Lienzo para Pintura', 'Lienzo de alta calidad para obras de arte', 20.00, 40.00, 'MarcaAS', 'Arte', 80);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P046', 'Cámara DSLR', 'Cámara réflex digital para profesionales', 250.00, 450.00, 'MarcaAT', 'Fotografía', 25);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P047', 'Trípode Profesional', 'Trípode robusto para cámaras', 30.00, 55.00, 'MarcaAU', 'Fotografía', 40);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P048', 'Objetivo Gran Angular', 'Objetivo ideal para paisajes y fotografía grupal', 120.00, 220.00, 'MarcaAV', 'Fotografía', 30);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P049', 'Impresora 3D', 'Impresora 3D para prototipos y modelado', 400.00, 700.00, 'MarcaAW', 'Tecnología', 7);
INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES ('P050', 'Drone con Cámara', 'Drone avanzado con cámara 4K incorporada', 500.00, 900.00, 'MarcaAX', 'Tecnología', 12);