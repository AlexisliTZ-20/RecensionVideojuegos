<?php
include('../db/config.php');

$sql = "SELECT * FROM tags";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Etiquetas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Lista de Etiquetas</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre de la Etiqueta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <a href="edit_tag.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="delete_tag.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Está seguro de eliminar esta etiqueta?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">Volver al Panel de Administrador</a>
</body>
</html>
