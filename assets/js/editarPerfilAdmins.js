// Al inicio de editarPerfilAdmin.js
console.log("JS de perfil de administrador cargado correctamente");

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formPerfil");
  const btnGuardar = document.getElementById("btnGuardar");
  const btnEliminar = document.getElementById("btnEliminar");

  btnGuardar.addEventListener("click", function (e) {
    e.preventDefault();

    const nombre = document.getElementById("nombres").value.trim();
    const apellido = document.getElementById("apellidos").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();

    // Validar campos vacíos
    if (!nombre || !apellido || !correo || !telefono) {
      return Swal.fire({
        icon: "warning",
        title: "Campos vacíos",
        text: "Por favor, completa todos los campos requeridos.",
      });
    }

    const regexLetras = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexTelefono = /^\d{7,15}$/;

    if (!regexLetras.test(nombre)) {
      return Swal.fire({
        icon: "error",
        title: "Nombre inválido",
        text: "El nombre solo debe contener letras y espacios.",
      });
    }

    if (!regexLetras.test(apellido)) {
      return Swal.fire({
        icon: "error",
        title: "Apellido inválido",
        text: "El apellido solo debe contener letras y espacios.",
      });
    }

    if (!regexCorreo.test(correo)) {
      return Swal.fire({
        icon: "error",
        title: "Correo inválido",
        text: "Ingresa un correo electrónico válido.",
      });
    }

    if (!regexTelefono.test(telefono)) {
      return Swal.fire({
        icon: "error",
        title: "Teléfono inválido",
        text: "Debe contener solo números (7 a 15 dígitos).",
      });
    }

    const datos = {
      nombre,
      apellido,
      correo,
      telefono
    };

    fetch('../../controllers/actualizarPerfilAdmin.php', {
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

  btnEliminar.addEventListener('click', function () {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción eliminará tu cuenta de administrador permanentemente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar cuenta',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#e94e4e',
      cancelButtonColor: '#3085d6'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('../../controllers/eliminarPerfilAdmin.php', {
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
