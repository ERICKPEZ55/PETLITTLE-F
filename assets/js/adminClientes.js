document.addEventListener('DOMContentLoaded', function() {
    const btnVolver = document.getElementById('btnVolver');
    if (btnVolver) {
        btnVolver.addEventListener('click', function(event) {
            event.preventDefault();
            window.history.back();
        });
    }

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const clientName = row.children[1].textContent; 
            
            if (confirm(`¿Estás seguro de que quieres eliminar a ${clientName} como cliente? Esta acción no se puede deshacer.`)) {
                alert(`${clientName} ha sido eliminado (simulado).`);
                row.remove();
            }
        });
    });
});