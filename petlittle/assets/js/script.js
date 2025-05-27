const usuarios = [];
let notificaciones = 0;
const tablaUsuarios = document.getElementById("tablaUsuarios");
const notiCount = document.getElementById("notiCount");

// Función para renderizar usuarios
function renderizarUsuarios() {
    tablaUsuarios.innerHTML = "";
    usuarios.forEach((user, index) => {
        tablaUsuarios.innerHTML += `
            <tr>
                <td>${user.nombre}</td>
                <td>${user.usuario}</td>
                <td>${user.telefono}</td>
                <td>${user.contraseña}</td>
                <td>
                    <span class="editar" onclick="editarUsuario(${index})">✏️</span>
                    <span class="borrar" onclick="eliminarUsuario(${index})">🗑️</span>
                </td>
            </tr>
        `;
    });
}

// Función para generar correo y contraseña aleatorios
function generarCorreo(nombre) {
    return nombre.toLowerCase().replace(/ /g, ".") + "@gmail.com";
}

function generarContraseña() {
    return Math.random().toString(36).slice(-8);
}

// Mostrar modal de agregar usuario
document.getElementById("btnAgregar").addEventListener("click", () => {
    document.getElementById("modalAgregar").style.display = "flex";
});

// Guardar nuevo usuario
document.getElementById("guardarUsuario").addEventListener("click", () => {
    const nombre = document.getElementById("nuevoNombre").value;
    const telefono = document.getElementById("nuevoTelefono").value;

    if (nombre && telefono) {
        const usuario = {
            nombre,
            usuario: generarCorreo(nombre),
            telefono,
            contraseña: generarContraseña()
        };

        usuarios.push(usuario);
        notificaciones++;
        notiCount.textContent = notificaciones;
        renderizarUsuarios();

        // Cerrar modal
        document.getElementById("modalAgregar").style.display = "none";
        document.getElementById("nuevoNombre").value = "";
        document.getElementById("nuevoTelefono").value = "";
    } else {
        alert("Por favor, ingresa nombre y teléfono.");
    }
});

// Cerrar modal de agregar usuario
document.getElementById("cerrarAgregar").addEventListener("click", () => {
    document.getElementById("modalAgregar").style.display = "none";
});

// Editar usuario
let usuarioEditando;
function editarUsuario(index) {
    usuarioEditando = index;
    document.getElementById("editNombre").value = usuarios[index].nombre;
    document.getElementById("editTelefono").value = usuarios[index].telefono;
    document.getElementById("modalEditar").style.display = "flex";
}

// Guardar cambios de edición
document.getElementById("guardarCambios").addEventListener("click", () => {
    usuarios[usuarioEditando].nombre = document.getElementById("editNombre").value;
    usuarios[usuarioEditando].telefono = document.getElementById("editTelefono").value;

    document.getElementById("modalEditar").style.display = "none";
    renderizarUsuarios();
});

// Cerrar modal de edición
document.getElementById("cerrarModal").addEventListener("click", () => {
    document.getElementById("modalEditar").style.display = "none";
});

// Eliminar usuario
function eliminarUsuario(index) {
    usuarios.splice(index, 1);
    renderizarUsuarios();
}

// Cargar usuarios iniciales
renderizarUsuarios();
