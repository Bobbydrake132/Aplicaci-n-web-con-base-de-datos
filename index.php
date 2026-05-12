<?php
// 1. CONFIGURACIÓN DE CONEXIÓN
$host = 'tu_host_aqui'; // Ej: mysql.railway.internal
$db   = 'tu_nombre_db';
$user = 'tu_usuario';
$pass = 'tu_password';
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// 2. LÓGICA CRUD

// Agregar o Editar
if (isset($_POST['action']) && $_POST['action'] == 'save') {
    $nombre = $_POST['nombre'];
    $pais = $_POST['pais'];
    $anio = $_POST['anio'];
    $id = $_POST['id'];

    if (!empty($id)) {
        $stmt = $pdo->prepare("UPDATE campeones SET nombre=?, pais=?, anio=? WHERE id=?");
        $stmt->execute([$nombre, $pais, $anio, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO campeones (nombre, pais, anio) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $pais, $anio]);
    }
    header("Location: index.php");
}

// Eliminar
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM campeones WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
}

// Consulta Individual (para editar)
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM campeones WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Consulta General
$stmt = $pdo->query("SELECT * FROM campeones ORDER BY anio DESC");
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aventuras en MTB - Proyecto para UDG Virtual</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <header class="site-header">
            <img src="https://cdn-icons-png.flaticon.com/512/94/94203.png" alt="Logo" class="logo">
            <h1>Explora el Ciclismo de Montaña</h1>
        </header>

        <main class="content-body">
            <h2>Ultimos Campeones de XC</h2>
            
            <form action="index.php" method="POST" class="crud-form">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                
                <input type="text" name="nombre" placeholder="Nombre del Ciclista" value="<?= $editData['nombre'] ?? '' ?>" required>
                <input type="text" name="pais" placeholder="País" value="<?= $editData['pais'] ?? '' ?>" required>
                <input type="number" name="anio" placeholder="Año" value="<?= $editData['anio'] ?? '' ?>" required>
                
                <button type="submit" class="video-link">
                    <?= $editData ? 'Actualizar Registro' : 'Agregar Campeón' ?>
                </button>
                <?php if($editData): ?> 
                    <a href="index.php" style="display:block; color:white; margin-top:10px;">Cancelar edición</a> 
                <?php endif; ?>
            </form>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Año</th>
                            <th>Nombre</th>
                            <th>País</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $r): ?>
                        <tr>
                            <td><?= $r['anio'] ?></td>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td><?= htmlspecialchars($r['pais']) ?></td>
                            <td>
                                <a href="index.php?edit=<?= $r['id'] ?>" class="btn-action">Editar</a>
                                <a href="index.php?delete=<?= $r['id'] ?>" class="btn-action btn-del" onclick="return confirm('¿Eliminar?')">Borrar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="site-footer">
            <div class="info-personal">
                <p><strong>Curso:</strong> Conceptualización de servicios en la nube</p>
                <p><strong>Estudiante:</strong> Victor Alejandro Gonzalez de la Torre</p>
                <p><strong>Código:</strong> 224066142</p>
                <p><strong>Contacto:</strong> <a href="mailto:correo@ejemplo.com">vialgo9302@gmail.com</a></p>
            </div>
        </footer>
    </div>
</body>
</html>