document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formPerfil");
  const btnGuardar = document.getElementById("btnGuardar");

  btnGuardar.addEventListener("click", (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const rol = document.getElementById("rol").value;
    const foto = document.getElementById("foto").files[0];

    // Validaciones
    if (nombre === "") {
      mostrarError("El nombre es obligatorio.");
      return;
    }

    if (!validarEmail(correo)) {
      mostrarError("El correo no es válido.");
      return;
    }

    if (!/^\d{10,}$/.test(telefono)) {
      mostrarError("El teléfono debe tener al menos 10 dígitos y contener solo números.");
      return;
    }

    if (rol !== "Administrador") {
      mostrarError("El rol seleccionado no es válido.");
      return;
    }

    // Si todo es válido
    guardarDatos({ nombre, correo, telefono, rol, foto });
  });

  function mostrarError(msg) {
    alert(msg); // Puedes cambiar esto por mostrar el error en el HTML
  }

  function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function guardarDatos(datos) {
    // Simulación de guardado
    console.log("Guardando datos...", datos);

    // Aquí podrías usar fetch() para guardar en un servidor o base de datos

    alert("¡Cambios guardados correctamente!");

    // Resetear el formulario si quieres
    // form.reset();
  }
});
