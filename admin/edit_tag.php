<?php
include('../db/config.php');

$tag_id = $_GET['id'] ?? 0;
$tag = [];

if ($tag_id) {
    $sql = "SELECT * FROM tags WHERE id = $tag_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $tag = $result->fetch_assoc();
    } else {
        die('Tag not found.');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Actualizar la etiqueta en la base de datos
    $sql = "UPDATE tags SET name='$name' WHERE id=$tag_id";
    if ($conn->query($sql) === TRUE) {
        header('Location: list_tags.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tag</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Edit Tag</h1>
    <form action="edit_tag.php?id=<?php echo $tag_id; ?>" method="post">
        <label for="name">Tag Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($tag['name']); ?>" required>
        
        <input type="submit" value="Update Tag">
    </form>

    <a href="list_tags.php">Volver a la lista de etiquetas</a>
</body>
</html>
