class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.nombre = this.form.querySelector("#nombre");
        this.apellido = this.form.querySelector("#apellido");
        this.celular = this.form.querySelector("#celular");
        this.email = this.form.querySelector("#email");
        this.politica = this.form.querySelector("#politica"); // ✅ NUEVO
        this.errorCelular = this.form.querySelector("#error-celular");

        this.soloLetras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        this.soloNumeros = /^[0-9]{10}$/;
        this.regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        this.form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const valido = await this.validar();

            if (valido) {
                this.form.reset();
            }
        });
    }

    async validar() {
        const nombre = this.nombre.value.trim();
        const apellido = this.apellido.value.trim();
        const celular = this.celular.value.trim();
        const email = this.email.value.trim();
        const politicaAceptada = this.politica.checked;

        this.errorCelular.textContent = "";

        if (!this.soloLetras.test(nombre)) {
            await Swal.fire({
                icon: 'error',
                title: 'Nombre inválido',
                text: 'El nombre solo debe contener letras.'
            });
            return false;
        }

        if (!this.soloLetras.test(apellido)) {
            await Swal.fire({
                icon: 'error',
                title: 'Apellido inválido',
                text: 'El apellido solo debe contener letras.'
            });
            return false;
        }

        if (!this.soloNumeros.test(celular)) {
            this.errorCelular.textContent = "El número debe tener exactamente 10 dígitos.";
            await Swal.fire({
                icon: 'error',
                title: 'Celular inválido',
                text: 'El número debe tener exactamente 10 dígitos.'
            });
            return false;
        }

        if (
            !this.regexEmail.test(email) ||
            !/[a-zA-Z]/.test(email) ||
            (email.match(/\d/g) || []).length > 6
        ) {
            await Swal.fire({
                icon: 'error',
                title: 'Correo inválido',
                text: 'Ingresa un correo válido con letras, "@", y no más de 6 números.'
            });
            return false;
        }

        //  Validación del checkbox
        if (!politicaAceptada) {
            await Swal.fire({
                icon: 'warning',
                title: 'Política de privacidad',
                text: 'Debes aceptar la política para continuar.'
            });
            return false;
        }

        await Swal.fire({
            icon: 'success',
            title: '¡Mensaje enviado!',
            text: 'Gracias por contactarnos, te responderemos pronto.'
        });

        return true;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new FormValidator("form-contacto");
});

window.addEventListener("pageshow", function () {
    const form = document.getElementById("form-contacto");
    if (form) {
        form.reset();
    }
});
