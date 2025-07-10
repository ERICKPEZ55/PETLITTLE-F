console.log('gestion.js cargado');

window.addEventListener('DOMContentLoaded', () => {
    fetch('../../controllers/listarMascotas.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(mascota => {
                agregarMascota(mascota);
            });
        })
        .catch(error => {
            console.error('Error al cargar mascotas:', error);
        });
});

function agregarMascota(mascota) {
    // Usa una imagen por defecto si no tiene imagen
    const imagenMascota = mascota.imagen && mascota.imagen.trim() !== ''
        ? mascota.imagen
        : '../../assets/img/mascotas/default.png'; // asegúrate de tener esta imagen

    const nuevaCard = document.createElement('div');
    nuevaCard.classList.add('mascota-card');
    nuevaCard.innerHTML = `
        <img src="${imagenMascota}" alt="${mascota.nombre_mascota}">
        <div class="info-container">
            <h3>${mascota.nombre_mascota}</h3>
            <p class="raza"><i class="fas fa-paw"></i> Raza: ${mascota.raza}</p>
            <p class="genero"><i class="fas fa-venus-mars"></i> Género: ${mascota.sexo}</p>
            <p class="dueño"><i class="fas fa-user"></i> Dueño: ${mascota.nombre_dueño}</p>
        </div>
        <div class="acciones">
            <button class="btn-ver-perfil">Ver Perfil</button>
        </div>
    `;

    nuevaCard.querySelector('.btn-ver-perfil').addEventListener('click', () => {
        window.location.href = `/PetLittle/views/usuario/perfilMascotaUsuario.php?id_mascota=${mascota.id_mascota}`;
    });

    document.getElementById("mascotas").appendChild(nuevaCard);
}