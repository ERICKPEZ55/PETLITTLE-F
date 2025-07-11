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

    // cargar empleados desde la base de datos al iniciar
    fetch('../../controllers/listarEmpleado.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(emp => {
                const fila = document.createElement('tr');
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

    btnAgregar.addEventListener('click', () => {
        modalAgregar.style.display = 'block';
    });

    cerrarAgregar.addEventListener('click', () => {
        modalAgregar.style.display = 'none';
    });

    cerrarModal.addEventListener('click', () => {
        modalEditar.style.display = 'none';
    });

    function generarContrasena(longitud) {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
        let contrasena = '';
        for (let i = 0; i < longitud; i++) {
            contrasena += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return contrasena;
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

        if (nombre && rol && telefonoValido && correoValido) {
            fetch('../../controllers/guardarEmpleado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre,
                    apellido,
                    rol,
                    usuario,
                    telefono,
                    contrasena
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td class="td-nombre">${nombre}</td>
                        <td class="td-apellido">${apellido}</td>
                        <td class="td-rol">${rol}</td>
                        <td class="td-correo">${usuario}</td>
                        <td class="td-telefono">${telefono}</td>
                        <td>${contrasena}</td>
                        <td>
                            <button class="editarBtn">✏️</button>
                            <button class="eliminarBtn">🗑️</button>
                        </td>
                    `;
                    tablaUsuarios.appendChild(fila);

                    fila.querySelector('.editarBtn').addEventListener('click', () => abrirEdicion(fila));
                    fila.querySelector('.eliminarBtn').addEventListener('click', () => eliminarUsuario(data.id)); // asume que el backend devuelve el id

                    // Limpiar formulario
                    document.getElementById('nuevoNombre').value = '';
                    document.getElementById('nuevoApellido').value = '';
                    document.getElementById('rol').value = '';
                    document.getElementById('nuevoTelefono').value = '';
                    document.getElementById('nuevoCorreo').value = '';

                    modalAgregar.style.display = 'none';
                } else {
                    alert('Error al guardar en la base de datos.');
                }
            })
            .catch(err => {
                console.error('Error al guardar:', err);
                alert('Error de conexión al guardar: ' + err.message);
            });

        } else {
            if (!telefonoValido) {
                alert('El teléfono debe tener exactamente 10 dígitos numéricos.');
            } else if (!correoValido) {
                alert('Ingresa un correo válido.');
            } else {
                alert('Por favor completa todos los campos.');
            }
        }
    });

    function abrirEdicion(fila) {
        usuarioEditando = fila;
        document.getElementById('editNombre').value = fila.querySelector('.td-nombre').textContent;
        document.getElementById('editApellido').value = fila.querySelector('.td-apellido').textContent;
        document.getElementById('editTelefono').value = fila.querySelector('.td-telefono').textContent;

        modalEditar.style.display = 'block';
    }

    guardarCambios.addEventListener('click', () => {
        const nuevoNombre = document.getElementById('editNombre').value.trim();
        const nuevoApellido = document.getElementById('editApellido').value.trim();
        const nuevoTelefono = document.getElementById('editTelefono').value.trim();

        const telefonoValido = /^[0-9]{10}$/.test(nuevoTelefono);

        if (nuevoNombre && telefonoValido) {
            if (usuarioEditando) {
                usuarioEditando.querySelector('.td-nombre').textContent = nuevoNombre;
                usuarioEditando.querySelector('.td-apellido').textContent = nuevoApellido;
                usuarioEditando.querySelector('.td-telefono').textContent = nuevoTelefono;
                modalEditar.style.display = 'none';
            }
        } else {
            if (!telefonoValido) {
                alert('El teléfono debe tener exactamente 10 dígitos numéricos.');
            } else {
                alert('Por favor completa todos los campos.');
            }
        }
    });

    function eliminarUsuario(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            fetch(`../../controllers/eliminarEmpleado.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // recargar para actualizar la tabla
                } else {
                    alert("No se pudo eliminar el usuario.");
                }
            })
            .catch(err => {
                console.error('Error al eliminar:', err);
                alert("Error de conexión al eliminar.");
            });
        }
    }
});
