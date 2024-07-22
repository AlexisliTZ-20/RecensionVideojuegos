<?php
include('../db/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_id = $_POST['game_id'];
    $username = $_POST['username'];
    $recommendation = $_POST['recommendation'];
    $review_text = $_POST['review_text'];

    $sql = "INSERT INTO reviews (game_id, username, recommendation, review_text) VALUES ('$game_id', '$username', '$recommendation', '$review_text')";
    if ($conn->query($sql) === TRUE) {
        echo "New review added successfully";
        header("Location: ../game.php?id=$game_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
