document.addEventListener("DOMContentLoaded", function () {
    fetch('../../controllers/listarUsuariosMascotas.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = "";

            data.forEach(usuario => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${usuario.id_usuario}</td>
                    <td>${usuario.nombre_completo}</td>
                    <td>${usuario.correo}</td>
                    <td class="column-telefono">${usuario.telefono}</td>
                    <td>${usuario.cantidad_mascotas} (${usuario.nombres_mascotas || '-'})</td>
                    <td>
                        <button class="btn-action btn-delete" data-id="${usuario.id_usuario}">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Eliminar con SweetAlert2
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Este usuario y sus mascotas serán eliminados permanentemente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('../../controllers/eliminarUsuarioAdmin.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `id_usuario=${id}`
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: 'El usuario fue eliminado exitosamente.',
                                        confirmButtonColor: '#3085d6'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.error || 'No se pudo eliminar el usuario.'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de red',
                                    text: 'No se pudo conectar con el servidor.'
                                });
                            });
                        }
                    });
                });
            });
        })
        .catch(err => {
            console.error("Error al cargar usuarios:", err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los usuarios.'
            });
        });
});
