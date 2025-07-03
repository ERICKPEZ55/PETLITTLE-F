<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Agenda del Veterinario</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/stylesCitas.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="logo-container">
                <img src="../../assets/img/logo negativo.png" alt="Logo" class="logo">
            </div>
        </div>
        <div class="titulo-header">
            <h1>Agenda del Veterinario</h1>
        </div>
        <a href="javascript:history.back()" class="btn-volver">&larr; Volver</a>
    </header>

    <main class="agenda-container">
        <div class="calendar-header">
            <button id="btnAnterior">&larr;</button>
            <h2 id="fechaTexto">Lunes, 27 Mayo 2025</h2>
            <button id="btnSiguiente">&rarr;</button>
        </div>

        <div class="time-grid">
            </div>
    </main>

    <script src="../../assets/js/agendaCitas.js"></script>
</body>
</html>