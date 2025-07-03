// Al inicio de editarPerfilEmpleado.js
console.log("JS de perfil de empleado cargado correctamente");

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formPerfil");
  const btnGuardar = document.getElementById("btnGuardar");
  const btnEliminar = document.getElementById("btnEliminar");

  btnGuardar.addEventListener("click", function (e) {
    e.preventDefault();

    const nombres = document.getElementById("nombres").value.trim();
    const apellidos = document.getElementById("apellidos").value.trim();
    const usuario = document.getElementById("usuario").value.trim(); // ← este reemplaza a 'correo'
    const telefono = document.getElementById("telefono").value.trim();

    if (!nombres || !apellidos || !usuario || !telefono) {
      Swal.fire({
        icon: "warning",
        title: "Campos vacíos",
        text: "Por favor, completa todos los campos requeridos.",
      });
      return;
    }

    const regexSoloLetras = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
    if (!regexSoloLetras.test(nombres)) {
      Swal.fire({
        icon: "error",
        title: "Nombre inválido",
        text: "El nombre solo debe contener letras y espacios.",
      });
      return;
    }

    if (!regexSoloLetras.test(apellidos)) {
      Swal.fire({
        icon: "error",
        title: "Apellido inválido",
        text: "El apellido solo debe contener letras y espacios.",
      });
      return;
    }

    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(usuario)) {
      Swal.fire({
        icon: "error",
        title: "Correo inválido",
        text: "Ingresa un correo electrónico válido.",
      });
      return;
    }

    const regexTelefono = /^\d{7,15}$/;
    if (!regexTelefono.test(telefono)) {
      Swal.fire({
        icon: "error",
        title: "Teléfono inválido",
        text: "El teléfono debe tener entre 7 y 15 dígitos numéricos.",
      });
      return;
    }

    // Enviar datos al backend
    const datos = {
      nombre: nombres,
      apellido: apellidos,
      usuario: usuario,
      telefono: telefono
    };

    fetch('../../controllers/actualizarPerfilEmpleado.php', {
      method: 'POST',
      body: new URLSearchParams(datos),
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'ok') {
        Swal.fire({
          icon: 'success',
          title: 'Perfil actualizado',
          text: 'Los cambios han sido guardados exitosamente.',
          confirmButtonColor: '#3085d6'
        });
      } else {
        Swal.fire('Error', data.message || 'No se pudo actualizar.', 'error');
      }
    })
    .catch(() => {
      Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
    });
  });

  // Eliminar cuenta
  btnEliminar.addEventListener('click', function () {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción eliminará tu cuenta de empleado permanentemente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar cuenta',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#e94e4e',
      cancelButtonColor: '#3085d6'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('../../controllers/eliminarPerfilEmpleado.php', {
          method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'ok') {
            Swal.fire({
              icon: 'success',
              title: 'Cuenta eliminada',
              text: 'Tu cuenta ha sido eliminada exitosamente.',
              confirmButtonColor: '#3085d6'
            }).then(() => {
              window.location.href = '../../models/login.php';
            });
          } else {
            Swal.fire('Error', 'No se pudo eliminar la cuenta.', 'error');
          }
        })
        .catch(() => {
          Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
        });
      }
    });
  });
});
