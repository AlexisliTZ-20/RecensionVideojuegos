<?php
include('../../db/config.php');

function getYoutubeEmbedUrl($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : $url;
}

$message = '';
$isError = false;

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

    $target_dir = "../assets/images/";
    $target_file_image = $target_dir . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_image);

    if (!empty($video)) {
        $target_file_video = $target_dir . basename($video);
        move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video);
        $video_link = $video;
    } else {
        $video_link = getYoutubeEmbedUrl($youtube_link);
    }

    $sql = "INSERT INTO games (title, description, developer, publisher, image, video) 
            VALUES ('$title', '$description', '$developer', '$publisher', '$image', '$video_link')";

    if ($conn->query($sql) === TRUE) {
        $last_game_id = $conn->insert_id;

        foreach ($selected_tags as $tag_id) {
            $sql_tag = "INSERT INTO game_tags (game_id, tag_id) VALUES ($last_game_id, $tag_id)";
            $conn->query($sql_tag);
        }

        header("Location: ../../admin/success.php?message=Juego añadido correctamente");
        exit();
    } else {
        $message = 'Error: ' . $conn->error;
        $isError = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Juego</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .popup {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        .popup.error {
            background-color: #f44336;
        }

        .popup i {
            margin-right: 10px;
        }

        .preview-container {
            margin-top: 20px;
        }

        .preview-container img,
        .preview-container video {
            max-width: 100%;
            margin-bottom: 20px;
        }

        .form-hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Añadir Nuevo Juego</h1>
    <form id="game-form" action="add_game.php" method="post" enctype="multipart/form-data">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" required>
        
        <label for="description">Descripción:</label>
        <textarea name="description" id="description" required></textarea>
        
        <label for="developer">Desarrollador:</label>
        <input type="text" name="developer" id="developer" required>
        
        <label for="publisher">Editor:</label>
        <input type="text" name="publisher" id="publisher" required>
        
        <label for="image">Imagen Principal:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <label for="video">Video:</label>
        <input type="file" name="video" id="video" accept="video/*">
        
        <label for="youtube_link">Link de Youtube:</label>
        <input type="text" name="youtube_link" id="youtube_link" placeholder="Or paste YouTube link here">

        <label for="tags">Etiquetas:</label>
        <select name="tags[]" id="tags" multiple required>
            <?php foreach ($tags as $tag): ?>
                <option value="<?php echo $tag['id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <div id="selected-tags-container">
            <h3>Seleccionar Etiquetas:</h3>
            <div id="selected-tags"></div>
        </div>
        
        <input type="submit" value="Add Game">
    </form>

    <div class="preview-container">
        <img id="image-preview" style="display: none;" alt="Image Preview">
        <video id="video-preview" style="display: none;" controls></video>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagsSelect = document.getElementById('tags');
        const selectedTagsContainer = document.getElementById('selected-tags');

        function updateSelectedTags() {
            const selectedOptions = Array.from(tagsSelect.selectedOptions);
            selectedTagsContainer.innerHTML = '';
            selectedOptions.forEach(option => {
                const tag = document.createElement('div');
                tag.className = 'tag';
                tag.dataset.value = option.value;
                tag.innerHTML = `${option.textContent} <button type="button" class="remove-tag" data-value="${option.value}">x</button>`;
                selectedTagsContainer.appendChild(tag);
            });
        }

        updateSelectedTags();

        tagsSelect.addEventListener('change', updateSelectedTags);

        selectedTagsContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-tag')) {
                const value = event.target.dataset.value;
                const option = tagsSelect.querySelector(`option[value="${value}"]`);
                if (option) {
                    option.selected = false;
                }
                updateSelectedTags();
            }
        });
    });

    function showPopup(message, isError = false) {
        var popup = document.createElement('div');
        popup.classList.add('popup');
        if (isError) {
            popup.classList.add('error');
            popup.innerHTML = '<i class="fas fa-times-circle"></i>' + message;
        } else {
            popup.innerHTML = '<i class="fas fa-check-circle"></i>' + message;
        }

        document.body.appendChild(popup);

        requestAnimationFrame(function() {
            popup.style.display = 'block';
            popup.style.opacity = '1';
        });

        setTimeout(function() {
            popup.style.opacity = '0';
            setTimeout(function() {
                document.body.removeChild(popup);
            }, 500);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($message): ?>
            showPopup("<?php echo $message; ?>", <?php echo $isError ? 'true' : 'false'; ?>);
            document.getElementById('game-form').classList.add('form-hidden'); // Ocultar el formulario
        <?php endif; ?>
    });

    document.getElementById('image').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('image-preview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('video').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const videoPreview = document.getElementById('video-preview');
            videoPreview.src = URL.createObjectURL(file);
            videoPreview.style.display = 'block';
        }
    });

    document.getElementById('youtube_link').addEventListener('input', function() {
        const url = this.value;
        const videoPreview = document.getElementById('video-preview');
        videoPreview.src = url ? `https://www.youtube.com/embed/${url.split('v=')[1]}` : '';
        videoPreview.style.display = url ? 'block' : 'none';
    });
    </script>
</body>
</html>
