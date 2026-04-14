/**
 * ==========================================
 * ARCHIVO PRINCIPAL DE JAVASCRIPT (main.js)
 * ==========================================
 * Este archivo controla la interactividad de la página de Gestión de Personal.
 * Aquí hacemos que el buscador funcione en tiempo real y que el formulario
 * aparezca o desaparezca al hacer clic en el botón.
 */

// 'DOMContentLoaded' asegura que este código solo se ejecute DESPUÉS 
// de que todo el HTML (tablas, botones, textos) haya cargado completamente.
// Si no ponemos esto, Javascript podría intentar buscar la tabla antes de que exista.
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. LÓGICA DEL BUSCADOR EN TIEMPO REAL
    // ==========================================
    
    // Capturamos los elementos del HTML que vamos a manipular:
    // 1. El input de texto donde el usuario escribe (la lupa)
    const inputBusqueda = document.getElementById('buscadorTabla');
    
    // 2. Todas las filas (<tr>) de la tabla que tienen datos de trabajadores
    const filas = document.querySelectorAll('#tablaPrincipal tbody tr.fila-trabajador');
    
    // 3. El elemento (span) donde mostramos el número de registros encontrados
    const contadorV = document.getElementById('contadorV');

    // Primero verificamos que el buscador exista en esta página (para no causar errores en otras páginas)
    if(inputBusqueda) {
        
        // Escuchamos el evento 'keyup' (ocurre cada vez que el usuario teclea y suelta una tecla)
        inputBusqueda.addEventListener('keyup', function() {
            
            // Convertimos lo que el usuario escribió a minúsculas. 
            // Así, si busca "JUAN", encontrará "Juan", "juan", etc.
            const textoBuscado = this.value.toLowerCase();
            
            // Creamos un contador interno para llevar la cuenta de cuántas filas coinciden
            let filasVisibles = 0;

            // Recorremos una por una todas las filas de la tabla
            filas.forEach(fila => {
                
                // Extraemos TODO el texto que contiene esa fila y lo pasamos a minúsculas
                const contenidoFila = fila.innerText.toLowerCase();
                
                // Condición: Si el texto de la fila INCLUYE lo que el usuario escribió...
                if(contenidoFila.includes(textoBuscado)) {
                    // ... le quitamos la clase CSS que la oculta (se hace visible)
                    fila.classList.remove('oculto-por-busqueda');
                    // Y sumamos 1 a nuestro contador de encontrados
                    filasVisibles++;
                } else {
                    // Si NO coincide, le agregamos la clase CSS que la oculta de la pantalla
                    fila.classList.add('oculto-por-busqueda');
                }
            });
            
            // Al terminar de revisar todas las filas, actualizamos el número en pantalla
            if(contadorV) {
                contadorV.textContent = filasVisibles;
            }
        });
    }

});


// ==========================================
// 2. FUNCIÓN PARA ABRIR/CERRAR EL FORMULARIO
// ==========================================
// Esta función está conectada directamente al botón en tu HTML
// mediante el atributo: onclick="toggleRegistro()"
function toggleRegistro() {
    
    // Capturamos el panel del formulario y el botón que ejecutó el clic
    const seccion = document.getElementById('seccionRegistro');
    const btn = document.getElementById('mainActionButton');
    
    // La magia: classList.toggle funciona como un interruptor de luz.
    // Si la sección NO tiene la clase 'abierto', se la pone (y el CSS la hace visible).
    // Si la sección YA TIENE la clase 'abierto', se la quita (y el CSS la esconde).
    seccion.classList.toggle('abierto');

    // Ahora comprobamos cómo quedó el formulario para cambiar la apariencia del botón
    if (seccion.classList.contains('abierto')) {
        // SI EL FORMULARIO ESTÁ ABIERTO:
        // Cambiamos el icono a una 'X' y el texto
        btn.innerHTML = '<i class="fas fa-times"></i> Cerrar Registro';
        
        // Forzamos el fondo a un color gris para que parezca un botón de "Cancelar"
        btn.style.backgroundColor = 'var(--text-muted)'; 
        btn.style.boxShadow = 'none'; // Le quitamos la sombra para que se vea plano
        
    } else {
        // SI EL FORMULARIO SE CERRÓ:
        // Restauramos el icono del usuario con el '+' y el texto original
        btn.innerHTML = '<i class="fas fa-user-plus"></i> Nuevo Trabajador';
        
        // Al dejar el background vacío, el botón vuelve a usar su color 
        // verde predeterminado dictado por la clase .btn-primary-green de tu CSS
        btn.style.backgroundColor = ''; 
        btn.style.boxShadow = '';
    }
}