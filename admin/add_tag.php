<?php
include('../db/config.php');

$message = '';
$isError = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $sql = "INSERT INTO tags (name) VALUES ('$name')";

    if ($conn->query($sql) === TRUE) {
        $message = 'New tag added successfully';
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
    <title>Añadir Etiqueta</title>
    <link rel="stylesheet" href="css/style.css">
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
    </style>
</head>
<body>
    <h1>Añadir Nueva Etiqueta</h1>
    <form action="add_tag.php" method="post">
        <label for="name">Nombre de la Etiqueta:</label>
        <input type="text" name="name" id="name" required>
        
        <input type="submit" value="Añadir Etiqueta">
    </form>

    <script>
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
            <?php endif; ?>
        });
    </script>
</body>
</html>
