console.log('trabajadores.js cargado');

window.addEventListener('DOMContentLoaded', () => {
    const btnAgregar = document.getElementById('btnAgregar');
    const modalAgregar = document.getElementById('modalAgregar');
    const cerrarAgregar = document.getElementById('cerrarAgregar');
    const guardarUsuario = document.getElementById('guardarUsuario');
    const tablaUsuarios = document.getElementById('tablaUsuarios');

    const modalEditar = document.getElementById('modalEditar');
    const cerrarModal = document.getElementById('cerrarModal');
    const guardarCambios = document.getElementById('guardarCambios');

    let usuarioEditando = null;

    // Cargar empleados desde la base de datos
    fetch('../../controllers/listarEmpleado.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(emp => {
                const fila = document.createElement('tr');
                fila.dataset.id = emp.id_empleado;
                fila.innerHTML = `
                    <td class="td-nombre">${emp.nombre}</td>
                    <td class="td-apellido">${emp.apellido}</td>
                    <td class="td-rol">${emp.rol}</td>
                    <td class="td-correo">${emp.usuario}</td>
                    <td class="td-telefono">${emp.telefono}</td>
                    <td>${emp.contrasena}</td>
                    <td>
                        <button class="editarBtn">✏️</button>
                        <button class="eliminarBtn">🗑️</button>
                    </td>
                `;
                tablaUsuarios.appendChild(fila);

                fila.querySelector('.editarBtn').addEventListener('click', () => abrirEdicion(fila));
                fila.querySelector('.eliminarBtn').addEventListener('click', () => eliminarUsuario(emp.id_empleado));
            });
        });

    btnAgregar.addEventListener('click', () => modalAgregar.style.display = 'block');
    cerrarAgregar.addEventListener('click', () => modalAgregar.style.display = 'none');
    cerrarModal.addEventListener('click', () => modalEditar.style.display = 'none');

    function generarContrasena(longitud) {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
        return Array.from({ length: longitud }, () =>
            caracteres.charAt(Math.floor(Math.random() * caracteres.length))
        ).join('');
    }

    guardarUsuario.addEventListener('click', () => {
        const nombre = document.getElementById('nuevoNombre').value.trim();
        const apellido = document.getElementById('nuevoApellido').value.trim();
        const rol = document.getElementById("rol").value;
        const telefono = document.getElementById('nuevoTelefono').value.trim();
        const usuario = document.getElementById('nuevoCorreo').value.trim();
        const contrasena = generarContrasena(6);

        const telefonoValido = /^[0-9]{10}$/.test(telefono);
        const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(usuario);

        if (!nombre || !apellido || !usuario || !telefono) {
            Swal.fire('Error', 'Todos los campos son obligatorios.', 'warning');
            return;
        }

        if (!telefonoValido) {
            Swal.fire('Error', 'El teléfono debe tener exactamente 10 dígitos.', 'warning');
            return;
        }

        if (!correoValido) {
            Swal.fire('Error', 'Correo electrónico inválido.', 'warning');
            return;
        }

        fetch('../../controllers/guardarEmpleado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, apellido, rol, usuario, telefono, contrasena })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Guardado!', 'Empleado agregado exitosamente.', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', 'No se pudo guardar el empleado.', 'error');
            }
        })
        .catch(err => {
            console.error('Error al guardar:', err);
            Swal.fire('Error', 'Error de conexión al guardar.', 'error');
        });
    });

    function abrirEdicion(fila) {
        usuarioEditando = fila;
        document.getElementById('editNombre').value = fila.querySelector('.td-nombre').textContent;
        document.getElementById('editApellido').value = fila.querySelector('.td-apellido').textContent;
        document.getElementById('editTelefono').value = fila.querySelector('.td-telefono').textContent;
        document.getElementById('editCorreo').value = fila.querySelector('.td-correo').textContent;

        modalEditar.style.display = 'block';
    }

    guardarCambios.addEventListener('click', () => {
        const nuevoNombre = document.getElementById('editNombre').value.trim();
        const nuevoApellido = document.getElementById('editApellido').value.trim();
        const nuevoTelefono = document.getElementById('editTelefono').value.trim();
        const nuevoCorreo = document.getElementById('editCorreo').value.trim();

        const idEmpleado = usuarioEditando?.dataset.id;

        if (!idEmpleado) {
            Swal.fire('Error', 'ID de usuario no encontrado.', 'error');
            return;
        }

        if (!nuevoNombre || !nuevoApellido || !nuevoTelefono || !nuevoCorreo) {
            Swal.fire('Error', 'Todos los campos deben estar completos.', 'warning');
            return;
        }

        if (!/^[0-9]{10}$/.test(nuevoTelefono)) {
            Swal.fire('Error', 'El teléfono debe tener 10 dígitos.', 'warning');
            return;
        }

        fetch('../../controllers/editarEmpleado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: idEmpleado,
                nombre: nuevoNombre,
                apellido: nuevoApellido,
                telefono: nuevoTelefono,
                usuario: nuevoCorreo
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'Usuario actualizado correctamente.', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', 'No se pudo actualizar el usuario.', 'error');
            }
        })
        .catch(err => {
            console.error("Error en la petición:", err);
            Swal.fire('Error', 'Error de red al guardar los cambios.', 'error');
        });
    });

    function eliminarUsuario(id) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/eliminarEmpleado.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', 'El usuario ha sido eliminado.', 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error al eliminar:', err);
                    Swal.fire('Error', 'Error de conexión al eliminar.', 'error');
                });
            }
        });
    }
});
