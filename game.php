<?php
include('db/config.php');

if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    $sql = "SELECT * FROM games WHERE id = $game_id";
    $result = $conn->query($sql);
    $game = $result->fetch_assoc();

    $tags = [];
    $sql_tags = "SELECT tags.name FROM tags 
                 JOIN game_tags ON tags.id = game_tags.tag_id 
                 WHERE game_tags.game_id = $game_id";
    $result_tags = $conn->query($sql_tags);
    if ($result_tags->num_rows > 0) {
        while ($row = $result_tags->fetch_assoc()) {
            $tags[] = $row['name'];
        }
    }

    $sql_reviews = "SELECT * FROM reviews WHERE game_id = $game_id";
    $result_reviews = $conn->query($sql_reviews);

    $total_reviews = $result_reviews->num_rows;
    $positive_reviews = 0;
    $negative_reviews = 0;

    while ($row = $result_reviews->fetch_assoc()) {
        if ($row['recommendation']) {
            $positive_reviews++;
        } else {
            $negative_reviews++;
        }
    }

    $positive_percentage = ($total_reviews > 0) ? ($positive_reviews / $total_reviews) * 100 : 0;
    $negative_percentage = ($total_reviews > 0) ? ($negative_reviews / $total_reviews) * 100 : 0;

    $result_reviews->data_seek(0); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .review-summary {
            margin: 20px 0;
        }

        .review-bar {
            position: relative;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .review-bar-positive {
            position: absolute;
            height: 100%;
            background-color: #28a745; 
            left: 0;
            top: 0;
            transition: width 0.3s ease;
        }

        .review-bar-negative {
            position: absolute;
            height: 100%;
            background-color: #dc3545; 
            right: 0;
            top: 0;
            transition: width 0.3s ease;
        }

        .recommended {
            color: #28a745; 
        }

        .not-recommended {
            color: #dc3545; 
        }

        .username-heading {
            color: #06b45d;
        }

        .tags {
            margin-top: 20px;
        }

        .tag {
            display: inline-block;
            background-color: #08d684;
            color: #333;
            border-radius: 3px;
            padding: 5px 10px;
            margin: 5px;
            font-size: 14px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .tag:hover {
            background-color: #07c87e;
        }

        h2.review-heading {
            color: #ff0000; 
        }

        .positive-percentage {
            color: #28a745; 
        }
        .negative-percentage {
            color: #dc3545; 
        }
    </style>
</head>
<body>
    <?php include('templates/header.php'); ?>
    <div class="main-content">
        <h1><?php echo htmlspecialchars($game['title']); ?></h1>
        <div class="container">
            <div class="content-left">
            <div class="video">
                <?php if (strpos($game['video'], 'youtube.com/embed') !== false): ?>
                    <iframe src="<?php echo htmlspecialchars($game['video']); ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <video controls>
                        <source src="assets/images/<?php echo htmlspecialchars($game['video']); ?>" type="video/mp4">
                        Su navegador no soporta la etiqueta de vídeo.
                    </video>
                <?php endif; ?>
            </div>

            </div>

            <div class="content-right">
                <img src="assets/images/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <p><strong>DESARROLLADOR:</strong> <?php echo htmlspecialchars($game['developer']); ?></p>
                <p><strong>EDITOR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo htmlspecialchars($game['publisher']); ?></p>

                <div class="tags">
                    <h2>Tags</h2>
                    <?php foreach ($tags as $tag): ?>
                        <a href="games_by_tag.php?tag=<?php echo urlencode($tag); ?>" class="tag"><?php echo htmlspecialchars($tag); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="reviews-add-review">
            <div class="reviews">
                <h2 class="review-heading">Reviews</h2>

                <div class="review-summary">
                    <div class="review-bar">
                        <div class="review-bar-positive" style="width: <?php echo $positive_percentage; ?>%;"></div>
                        <div class="review-bar-negative" style="width: <?php echo $negative_percentage; ?>%;"></div>
                    </div>
                    <p><strong class="positive-percentage">Positivas:</strong> <?php echo number_format($positive_percentage, 1); ?>%</p>
                    <p><strong class="negative-percentage">Negativas:</strong> <?php echo number_format($negative_percentage, 1); ?>%</p>
                </div>

                <?php
                if ($result_reviews->num_rows > 0) {
                    while ($row = $result_reviews->fetch_assoc()) {
                        $recommendationClass = $row['recommendation'] ? 'recommended' : 'not-recommended';
                        $icon = $row['recommendation'] ? 'fa-thumbs-up' : 'fa-thumbs-down';
                        $text = $row['recommendation'] ? 'Recomendado' : 'No Recomendado';
                        
                        echo "<div class='review'>";
                        echo "<h3 class='username-heading'>" . htmlspecialchars($row['username']) . "</h3>";
                        echo "<p class='" . $recommendationClass . "'><i class='fas " . $icon . "'></i> " . $text . "</p>";
                        echo "<p>" . htmlspecialchars($row['review_text']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews yet.</p>";
                }
                ?>
            </div>

            <div class="add-review">
                <h2 class="review-heading">Add Review</h2>
                <form action="reviews/add_review.php" method="post">
                    <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game_id); ?>">
                    
                    <label for="username">Nombre:</label>
                    <input type="text" name="username" required>
                    
                    <label for="recommendation">¿Recomiendas este Juego?:</label>
                    <input type="radio" name="recommendation" value="1" required> Si
                    <input type="radio" name="recommendation" value="0" required> No
                    
                    <label for="review_text">Review:</label>
                    <textarea name="review_text" required></textarea>
                    
                    <input type="submit" value="Add Review">
                </form>
            </div>
        </div>
    </div>
    <?php include('templates/footer.php'); ?>
</body>
</html>
