document.addEventListener('DOMContentLoaded', function() {
    // Función para alternar el estado de expansión de las tarjetas
    function alternarCurso(tarjeta) {
        const estaExpandida = tarjeta.classList.contains('expandida');
        
        // Cerrar todas las tarjetas primero
        document.querySelectorAll('.tarjeta-curso').forEach(t => {
            t.classList.remove('expandida');
        });
        
        // Si no estaba expandida, expandir esta tarjeta
        if (!estaExpandida) {
            tarjeta.classList.add('expandida');
        }
    }

    // Agregar event listeners a todas las tarjetas
    document.querySelectorAll('.tarjeta-curso').forEach(tarjeta => {
        tarjeta.addEventListener('click', function(e) {
            // Prevenir que el clic se propague si se hace en el botón de expansión
            if (!e.target.closest('.indicador-expansion')) {
                alternarCurso(this);
            }
        });
    });

    // También agregar event listener específico al indicador de expansión
    document.querySelectorAll('.indicador-expansion').forEach(indicador => {
        indicador.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevenir que el clic llegue a la tarjeta
            const tarjeta = this.closest('.tarjeta-curso');
            alternarCurso(tarjeta);
        });
    });

    // Cerrar tarjetas al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.tarjeta-curso')) {
            document.querySelectorAll('.tarjeta-curso').forEach(tarjeta => {
                tarjeta.classList.remove('expandida');
            });
        }
    });
});