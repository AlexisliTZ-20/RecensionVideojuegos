<?php
include('db/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Games</title>
    <link rel="stylesheet" href="assets/css/indexstyles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h1>Video Games</h1>
    <div class="games">
        <?php
        $sql = "SELECT * FROM games";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='game'>";
                $imagePath = "assets/images/" . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');
                if (file_exists($imagePath)) {
                    echo "<a href='game.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "<img src='$imagePath' alt='" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "</a>";
                } else {
                    echo "<p>Image not found: $imagePath</p>";
                }
                echo "<h2><a href='game.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</a></h2>";
                echo "<div class='info-popup'>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No games found.</p>";
        }

        $conn->close();
        ?>
    </div>
    <?php include('templates/footer.php'); ?>
</body>
</html>
