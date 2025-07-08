<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mascotas</title>
    <link rel="stylesheet" href="../../assets/css/gestionM.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
        </div>
        <h1 class="titulo-header">Gestión de Mascotas</h1>
    </header>

    <div class="container">
        <div class="mascotas-grid" id="mascotas"></div>
         <div class="contenedor-boton">
        <a href="../../controllers/exportarMascotas.php" class="btn-excel" download>
            <img src="https://img.icons8.com/color/24/000000/ms-excel.png" alt="Excel Icon" />
            Descargar Excel
        </a>
    </div>
    </div>

    <div style="text-align: center;">
        <button class="navigation back-button" onclick="history.back()">← Volver</button>
    </div>


<script src="../../assets/js/gestion.js"></script>
</body>
</html>