class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.nombre = this.form.querySelector("#nombre");
        this.apellido = this.form.querySelector("#apellido");
        this.celular = this.form.querySelector("#celular");
        this.email = this.form.querySelector("#email");
        this.errorCelular = this.form.querySelector("#error-celular");

        this.soloLetras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        this.soloNumeros = /^[0-9]{10}$/;

        this.form.onsubmit = (e) => {
            if (!this.validar()) {
                e.preventDefault();
            }
        };
    }

    validar() {
        const nombre = this.nombre.value.trim();
        const apellido = this.apellido.value.trim();
        const celular = this.celular.value.trim();
        const email = this.email.value.trim();

        this.errorCelular.textContent = "";
        let valido = true;

        if (!this.soloLetras.test(nombre)) {
            alert("El nombre solo debe contener letras.");
            valido = false;
        }

        if (!this.soloLetras.test(apellido)) {
            alert("El apellido solo debe contener letras.");
            valido = false;
        }

        if (!this.soloNumeros.test(celular)) {
            this.errorCelular.textContent = "El número debe tener exactamente 10 dígitos numéricos.";
            valido = false;
        }

        if (
            !email.includes("@") ||
            !/[a-zA-Z]/.test(email) ||
            (email.match(/\d/g) || []).length > 6 ||
            !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        ) {
            alert("Por favor ingresa un correo válido con al menos una letra, el símbolo '@' y no más de 6 números.");
            valido = false;
        }

        if (valido) {
            alert("Formulario enviado exitosamente.");
        }

        return valido;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new FormValidator("form-contacto");
});
