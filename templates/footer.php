<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas de Videojuegos</title>
    <link rel="stylesheet" href="../videojuegos/templates/styles/stylefooter.css">
</head>
<body>
    <footer>
        <div class="particle-container-footer">
        </div>
        <p>2024 Reseñas de Videojuegos</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particleContainerFooter = document.querySelector('.particle-container-footer');
            const numberOfParticlesFooter = 50; 

            for (let i = 0; i < numberOfParticlesFooter; i++) {
                const particleFooter = document.createElement('div');
                particleFooter.classList.add('particle-footer');
                
                particleFooter.style.left = `${Math.random() * 100}vw`;
                particleFooter.style.top = `${Math.random() * 100}%`;
                particleFooter.style.width = `${Math.random() * 5 + 1}px`; 
                particleFooter.style.height = particleFooter.style.width;

                particleContainerFooter.appendChild(particleFooter);
            }
        });
    </script>
</body>
</html>
