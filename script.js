function agregarProducto() {
    const contenedor = document.getElementById("productos");
    const index = document.querySelectorAll(".producto").length;

    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("producto");
    nuevoProducto.innerHTML = `
        <div>
            <label>Producto:</label>
            <select name="productos[${index}]" class="form-select" required>
                <option value="" disabled selected>Seleccione un producto</option>
                <option value="Merengon mixto">Merengon mixto</option>
                <option value="Merengon fresa">Merengon fresa</option>
                <option value="Merengon Guanabana">Merengon Guanabana</option>
                <option value="Merengon Oreo">Merengon Oreo</option>
                <option value="Genovesa de fresa 1/4">Genovesa de fresa 1/4</option>
                <option value="Genovesa de oreo 1/4">Genovesa de oreo 1/4</option>
                <!-- Agrega más opciones de productos aquí -->
            </select>
        </div>
         <div>
            <label>Cantidad:</label>
            <input type="number" name="cantidad[${index}]" class="form-control" min="1" value="1" required>
        </div>
        <div class="adiciones">
            <label>Adiciones:</label>
            <select name="adiciones[${index}][]" class="form-select" required>
                <option value="" disabled selected>Seleccione una adición</option>
                <option value="Salsa de arequipe">Salsa de arequipe</option>
                <option value="Salsa lechera">Salsa lechera</option>
                <option value="Salsa de fresa">Salsa de fresa</option>
                <option value="Salsa de chocolate">Salsa de chocolate</option>
                <option value="Chispas de chocolate">Chispas de chocolate</option>
                <!-- Agrega más opciones de adiciones aquí -->
            </select>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarAdicion(this, ${index})">Agregar Adición</button>
        <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Cancelar Producto</button>
    `;
    contenedor.appendChild(nuevoProducto);
}

function agregarAdicion(btn, index) {
    const adicionesDiv = btn.previousElementSibling;
    const nuevaAdicion = document.createElement("select");
    nuevaAdicion.name = `adiciones[${index}][]`;
    nuevaAdicion.classList.add("form-select");
    nuevaAdicion.required = true;
    nuevaAdicion.innerHTML = `
        <option value="" disabled selected>Seleccione una adición</option>
        <option value="Salsa de arequipe">Salsa de arequipe</option>
        <option value="Salsa lechera">Salsa lechera</option>
        <option value="Salsa de fresa">Salsa de fresa</option>
        <option value="Salsa de chocolate">Salsa de chocolate</option>
        <option value="Chispas de chocolate">Chispas de chocolate</option>
        <!-- Agrega más opciones de adiciones aquí -->
    `;
    adicionesDiv.appendChild(nuevaAdicion);
}


function eliminarProducto(btn) {
    const productoDiv = btn.parentElement; // Div que contiene el producto y sus adiciones
    productoDiv.remove(); // Eliminar el producto
}

// Función para eliminar una adición
function eliminarAdicion(btn) {
    const adicionDiv = btn.parentElement; // Div que contiene la adición
    adicionDiv.remove(); // Eliminar la adición
}


// Buscar usuario por número de teléfono
$(document).ready(function () {
    $('#telefono').on('keyup', function () {
        var telefono = $(this).val(); // Obtener el valor del teléfono ingresado

        // Verificar si el campo no está vacío y tiene al menos 10 caracteres
        if (telefono.length >= 10) {
            $.ajax({
                url: 'buscar_usuario.php', // Archivo PHP para buscar datos
                method: 'POST',           // Método de solicitud
                data: { telefono: telefono }, // Datos enviados al servidor
                dataType: 'json',         // Esperar una respuesta en formato JSON
                success: function (response) {
                    if (response.status === 'success') {
                        // Rellenar los campos con los datos obtenidos
                        $('#nombre').val(response.data.nombre || '');
                        $('#direccion').val(response.data.direccion || '');
                        $('#barrio').val(response.data.barrio || '');
                    } else {
                        // Limpiar los campos si el usuario no es encontrado
                        $('#nombre').val('');
                        $('#direccion').val('');
                        $('#barrio').val('');
                        alert('Usuario no encontrado.');
                    }
                },
                error: function (xhr, status, error) {
                    // Manejar errores en la solicitud AJAX
                    console.error('Error al buscar el usuario:', error);
                    alert('Hubo un error al buscar el usuario. Por favor, intenta de nuevo.');
                }
            });
        } else {
            // Limpiar los campos si el teléfono no cumple con la longitud mínima
            $('#nombre').val('');
            $('#direccion').val('');
            $('#barrio').val('');
        }
    });
});