<?php
include('../../db/config.php');

$sql = "SELECT * FROM games";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Juegos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Lista de Juegos</h1>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Desarrollador</th>
                <th>Editor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['developer']); ?></td>
                <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                <td>
                    <a href="edit_game.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_game.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this game?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">Volver al Panel de Administrador</a>
</body>
</html>
