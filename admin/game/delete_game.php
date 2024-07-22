<?php
include('../../db/config.php');

if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Eliminar las asociaciones de etiquetas
    $sql = "DELETE FROM game_tags WHERE game_id = $game_id";
    $conn->query($sql);

    // Eliminar el juego
    $sql = "DELETE FROM games WHERE id = $game_id";
    if ($conn->query($sql) === TRUE) {
        header('Location: list_games.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
