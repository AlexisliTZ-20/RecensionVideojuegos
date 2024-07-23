<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Operación completada.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 1000;
        }
        .success-message a {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="success-message">
        <p><?php echo $message; ?></p>
        <p><a href="add_game.php">Añade otro juego</a></p>
        <p><a href="index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
