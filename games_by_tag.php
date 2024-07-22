<?php
include('db/config.php');

if (isset($_GET['tag'])) {
    $tag = urldecode($_GET['tag']);

    $sql = "SELECT g.* FROM games g
            JOIN game_tags gt ON g.id = gt.game_id
            JOIN tags t ON gt.tag_id = t.id
            WHERE t.name = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tag);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juegos con Etiqueta: <?php echo htmlspecialchars($tag); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            
        }

        .game {
            background-color: #272727;
            border-radius: 8px;
            margin: 10px;
            overflow: hidden;
            width: 300px; 
            transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .game img {
            width: 100%;
            height: auto;
            display: block;
        }

        .game-content {
            padding: 15px;
            color: #272727; 
        }

        .game-title {
            font-size: 1.5em;
            color: #fff; 
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .game-description {
            font-size: 0.7em;
            color: #00ff99; 
            margin: 0;
        }

        .game:hover {
            box-shadow: #00ff99; 
            transform: scale(1); 
        }

        .header-padding {
            padding-top: 60px; 
        }
    </style>
</head>



<body>
    <?php include('templates/header.php'); ?>
    <div class="header-padding">
        <h1>Juegos con Etiqueta: <?php echo htmlspecialchars($tag); ?></h1>
        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($game = $result->fetch_assoc()): ?>
                    <div class="game">
                        <a href="game.php?id=<?php echo $game['id']; ?>">
                            <img src="assets/images/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                            <div class="game-content">
                                <h2 class="game-title"><?php echo htmlspecialchars($game['title']); ?></h2>
                                <p class="game-description"><?php echo htmlspecialchars($game['description']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>o se encontraron juegos con esta etiqueta.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include('templates/footer.php'); ?>
</body>
