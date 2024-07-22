<?php
include('../../db/config.php');

$game_id = $_GET['id'] ?? 0;
$game = [];

if ($game_id) {
    $sql = "SELECT * FROM games WHERE id = $game_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
    } else {
        die('Game not found.');
    }
}

// Obtener todas las etiquetas
$tags = [];
$sql_tags = "SELECT * FROM tags";
$result_tags = $conn->query($sql_tags);
if ($result_tags->num_rows > 0) {
    while ($row = $result_tags->fetch_assoc()) {
        $tags[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];
    $image = $_FILES['image']['name'];
    $video = $_FILES['video']['name'];
    $youtube_link = $_POST['youtube_link'];
    $selected_tags = $_POST['tags'] ?? [];

    // Subir la imagen 
    if (!empty($image)) {
        $target_dir = "../assets/images/";
        $target_file_image = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_image);
    } else {
        $image = $game['image'];
    }

    // Subir el video o usar el enlace de YouTube
    if (!empty($video)) {
        $target_file_video = $target_dir . basename($video);
        move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video);
        $video_link = $video;
    } else {
        $video_link = getYoutubeEmbedUrl($youtube_link);
    }

    // Actualizar el juego en la base de datos
    $sql = "UPDATE games SET title='$title', description='$description', developer='$developer', publisher='$publisher', image='$image', video='$video_link' WHERE id=$game_id";
    if ($conn->query($sql) === TRUE) {
        // Eliminar las etiquetas antiguas del juego
        $sql_tag_delete = "DELETE FROM game_tags WHERE game_id = $game_id";
        $conn->query($sql_tag_delete);

        // Insertar las nuevas etiquetas
        foreach ($selected_tags as $tag_id) {
            $sql_tag = "INSERT INTO game_tags (game_id, tag_id) VALUES ($game_id, $tag_id)";
            $conn->query($sql_tag);
        }

        header('Location: list_games.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();

function getYoutubeEmbedUrl($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : $url;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Edit Game</h1>
    <form action="edit_game.php?id=<?php echo $game_id; ?>" method="post" enctype="multipart/form-data">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($game['title']); ?>" required>
        
        <label for="description">Descripción:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($game['description']); ?></textarea>
        
        <label for="developer">Desarrollador:</label>
        <input type="text" name="developer" id="developer" value="<?php echo htmlspecialchars($game['developer']); ?>" required>
        
        <label for="publisher">Editor:</label>
        <input type="text" name="publisher" id="publisher" value="<?php echo htmlspecialchars($game['publisher']); ?>" required>
        
        <label for="image">Imagen Principal:</label>
        <input type="file" name="image" id="image" accept="image/*">
        <img src="../assets/images/<?php echo htmlspecialchars($game['image']); ?>" alt="Current Image" style="max-width: 200px;">
        
        <label for="video">Video:</label>
        <input type="file" name="video" id="video" accept="video/*">
        <video controls style="max-width: 200px;">
            <source src="../assets/images/<?php echo htmlspecialchars($game['video']); ?>" type="video/mp4">
            Su navegador no soporta el formato de vídeo.
        </video>
        
        <label for="youtube_link">YouTube Link:</label>
        <input type="text" name="youtube_link" id="youtube_link" placeholder="Or paste YouTube link here" value="<?php echo htmlspecialchars($game['video']); ?>">

            
                    
        </select>
        
        <input type="submit" value="Update Game">
    </form>

    <a href="list_games.php">Volver a la lista de juegos</a>
</body>
</html>
