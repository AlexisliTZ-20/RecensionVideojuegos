<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas de Videojuegos</title>
    <link rel="stylesheet" href="../videojuegos/templates/styles/styleheader.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../videojuegos/templates/images/logo2.png" alt="Logo" class="logo">
        </div>
        <div class="particle-container"></div>
        <nav>
            <ul>
                <li><a href="../videojuegos/index.php">Inicio</a></li>
                <li><a href="../videojuegos/index.php"> </a></li>
            </ul>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particleContainer = document.querySelector('.particle-container');
            const numberOfParticles = 50; 

            for (let i = 0; i < numberOfParticles; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Posiciona las partículas aleatoriamente
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.width = `${Math.random() * 5 + 1}px`;
                particle.style.height = particle.style.width;

                particleContainer.appendChild(particle);
            }
        });
    </script>
</body>
</html>
