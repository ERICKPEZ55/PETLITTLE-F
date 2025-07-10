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

    // Función para llenar un select con especialidades
    function cargarEspecialidadesEnSelect(selectId, especialidadesData, especialidadSeleccionada = null) {
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">Seleccione la especialidad</option>';

        especialidadesData.forEach(esp => {
            const option = document.createElement('option');
            option.value = esp.id_especialidad;
            option.textContent = esp.nombre;
            if (especialidadSeleccionada && esp.nombre === especialidadSeleccionada) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    // Cargar especialidades para el modal de agregar
    fetch('../../controllers/obtenerEspecialidad.php')
        .then(response => response.json())
        .then(data => {
            cargarEspecialidadesEnSelect('especialidad', data);
        })
        .catch(error => {
            console.error('Error al cargar especialidades:', error);
        });

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
                <td class="td-especialidad">${emp.especialidad}</td> <!-- CORREGIDO -->
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
        const id_especialidad = document.getElementById("especialidad").value;
        const telefono = document.getElementById('nuevoTelefono').value.trim();
        const usuario = document.getElementById('nuevoCorreo').value.trim();
        const contrasena = generarContrasena(6);

        const telefonoValido = /^[0-9]{10}$/.test(telefono);
        const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(usuario);

        if (!nombre || !apellido || !id_especialidad || !usuario || !telefono) {
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
            body: JSON.stringify({
                nombre,
                apellido,
                rol,
                especialidad: id_especialidad,
                usuario,
                telefono,
                contrasena
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Guardado!', 'Empleado agregado exitosamente.', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', `No se pudo guardar el empleado. ${data.error}`, 'error');
            }
        })
        .catch(err => {
            console.error('Error al guardar:', err);
            Swal.fire('Error', 'Error de conexión al guardar.', 'error');
        });
    });

    function abrirEdicion(fila) {
        usuarioEditando = fila;

        const nombre = fila.querySelector('.td-nombre').textContent;
        const apellido = fila.querySelector('.td-apellido').textContent;
        const especialidad = fila.querySelector('.td-especialidad').textContent;
        const telefono = fila.querySelector('.td-telefono').textContent;
        const correo = fila.querySelector('.td-correo').textContent;

        document.getElementById('editNombre').value = nombre;
        document.getElementById('editApellido').value = apellido;
        document.getElementById('editTelefono').value = telefono;
        document.getElementById('editCorreo').value = correo;

        // Cargar especialidades en el select del modal de edición y seleccionar la actual
        fetch('../../controllers/obtenerEspecialidad.php')
            .then(response => response.json())
            .then(data => {
                cargarEspecialidadesEnSelect('editEspecialidad', data, especialidad);
            })
            .catch(error => {
                console.error('Error al cargar especialidades para edición:', error);
            });

        modalEditar.style.display = 'block';
    }

    guardarCambios.addEventListener('click', () => {
        const nuevoNombre = document.getElementById('editNombre').value.trim();
        const nuevoApellido = document.getElementById('editApellido').value.trim();
        const nuevaEspecialidad = document.getElementById('editEspecialidad').value;
        const nuevoTelefono = document.getElementById('editTelefono').value.trim();
        const nuevoCorreo = document.getElementById('editCorreo').value.trim();

        const idEmpleado = usuarioEditando?.dataset.id;

        if (!idEmpleado) {
            Swal.fire('Error', 'ID de usuario no encontrado.', 'error');
            return;
        }

        if (!nuevoNombre || !nuevoApellido || !nuevaEspecialidad || !nuevoTelefono || !nuevoCorreo) {
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
                id_especialidad: nuevaEspecialidad,
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

document.addEventListener("DOMContentLoaded", function () {
  const buscador = document.getElementById("buscar");
  const tabla = document.getElementById("tablaUsuarios");

  buscador.addEventListener("input", function () {
    const filtro = buscador.value.toLowerCase();
    const filas = tabla.getElementsByTagName("tr");

    for (let fila of filas) {
      const textoFila = fila.innerText.toLowerCase();
      fila.style.display = textoFila.includes(filtro) ? "" : "none";
    }
  });
});
