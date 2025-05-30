// Validaciones
function validarNombre(nombre) {
    return /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/.test(nombre);
}

function validarRol(rol) {
    return /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/.test(rol);
}

function validarTelefono(telefono) {
    return /^\d{1,10}$/.test(telefono);
}

// Mostrar modal agregar
document.getElementById("btnAgregar").addEventListener("click", () => {
    document.getElementById("modalAgregar").style.display = "flex";
});

// Guardar nuevo usuario
document.getElementById("guardarUsuario").addEventListener("click", () => {
    const nombre = document.getElementById("nuevoNombre").value.trim();
    const rol = document.getElementById("rol").value.trim();
    const telefono = document.getElementById("nuevoTelefono").value.trim();

    // Limpiar mensajes
    document.getElementById("errorNombre").textContent = "";
    document.getElementById("errorRol").textContent = "";
    document.getElementById("errorTelefono").textContent = "";

    let valido = true;

    if (!validarNombre(nombre)) {
        document.getElementById("errorNombre").textContent = "Solo letras y espacios.";
        valido = false;
    }

    if (!validarRol(rol)) {
        document.getElementById("errorRol").textContent = "Solo letras y espacios.";
        valido = false;
    }

    if (!validarTelefono(telefono)) {
        document.getElementById("errorTelefono").textContent = "Máximo 10 dígitos numéricos.";
        valido = false;
    }

    if (!valido) return;

    const usuario = {
        nombre,
        rol,
        usuario: generarCorreo(nombre),
        telefono,
        contraseña: generarContraseña()
    };

    usuarios.push(usuario);
    notificaciones++;
    notiCount.textContent = notificaciones;
    renderizarUsuarios();

    document.getElementById("modalAgregar").style.display = "none";
    document.getElementById("rol").value = "";
    document.getElementById("nuevoNombre").value = "";
    document.getElementById("nuevoTelefono").value = "";
});

// Editar usuario
let usuarioEditando;
function editarUsuario(index) {
    usuarioEditando = index;
    document.getElementById("editRol").value = usuarios[index].rol;
    document.getElementById("editNombre").value = usuarios[index].nombre;
    document.getElementById("editTelefono").value = usuarios[index].telefono;

    // Limpiar errores al abrir modal
    document.getElementById("errorNombreEdit").textContent = "";
    document.getElementById("errorRolEdit").textContent = "";
    document.getElementById("errorTelefonoEdit").textContent = "";

    document.getElementById("modalEditar").style.display = "flex";
}

// Guardar cambios de edición
document.getElementById("guardarCambios").addEventListener("click", () => {
    const nombre = document.getElementById("editNombre").value.trim();
    const rol = document.getElementById("editRol").value.trim();
    const telefono = document.getElementById("editTelefono").value.trim();

    document.getElementById("errorNombreEdit").textContent = "";
    document.getElementById("errorRolEdit").textContent = "";
    document.getElementById("errorTelefonoEdit").textContent = "";

    let valido = true;

    if (!validarNombre(nombre)) {
        document.getElementById("errorNombreEdit").textContent = "Solo letras y espacios.";
        valido = false;
    }

    if (!validarRol(rol)) {
        document.getElementById("errorRolEdit").textContent = "Solo letras y espacios.";
        valido = false;
    }

    if (!validarTelefono(telefono)) {
        document.getElementById("errorTelefonoEdit").textContent = "Máximo 10 dígitos numéricos.";
        valido = false;
    }

    if (!valido) return;

    usuarios[usuarioEditando].nombre = nombre;
    usuarios[usuarioEditando].rol = rol;
    usuarios[usuarioEditando].telefono = telefono;

    document.getElementById("modalEditar").style.display = "none";
    renderizarUsuarios();
});

