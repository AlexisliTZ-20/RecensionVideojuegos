<?php
include('../db/config.php');

if (isset($_GET['id'])) {
    $tag_id = $_GET['id'];

    // Eliminar las asociaciones de juegos
    $sql = "DELETE FROM game_tags WHERE tag_id = $tag_id";
    $conn->query($sql);

    // Eliminar la etiqueta
    $sql = "DELETE FROM tags WHERE id = $tag_id";
    if ($conn->query($sql) === TRUE) {
        header('Location: list_tags.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
