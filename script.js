function agregarProducto() {
    const contenedor = document.getElementById("productos");
    const index = document.querySelectorAll(".producto").length;

    // Crear un nuevo div para el producto
    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("producto");
    nuevoProducto.innerHTML = `
        <div>
            <label>Producto:</label>
            <select name="productos[${index}]" class="form-select" required>
                 <option value="" disabled selected>Seleccione un producto</option>
       <option value=""></option>
                <option value="Merengon mixto">Merengon mixto</option>
                <option value="Merengon fresa">Merengon fresa</option>
                <option value="Merengon Guanabana">Merengon Guanabana</option>
                <option value="Merengon fresa y durazno">Merengon fresa y durazno</option>
                <option value="Merengon fresa guanabana">Merengon fresa guanabana</option>
                <option value="Merengon Durazno guanabana">Merengon Durazno guanabana</option>
                <option value="Merengon Oreo">Merengon Oreo</option>
                <option value="Merengon M&M">Merengon M&M</option>
                <option value="Merengon Milo">Merengon Milo</option>
                <option value="Merengon Fresas con crema">Fresas con crema</option>
                <option value="Porcion torta">Porcion de torta</option>
                <option value="Genovesa fresa y dur 1/4 $65.000">Genovesa fresa y dur 1/4 $65.000</option>
                <option value="Genovesa fresa y dur 1/2 $85.000">Genovesa fresa y dur 1/2 $85.000</option>
                <option value="Genovesa fresa 1/4 $65.000">Genovesa fresa 1/4 $65.000 </option>
                <option value="Genovesa fresa 1/2 $85.000">Genovesa fresa 1/2 $85.000</option>
                <option value="Genovesa durazno 1/4 $68.000">Genovesa durazno 1/4 $68.000 </option>
                <option value="Genovesa durazno 1/2  $88.000">Genovesa durazno 1/2  $88.000</option>
                <option value="Genovesa mora 1/4 $80.000">Genovesa mora 1/4 $80.000</option>
                <option value="Genovesa Milo 1/4 $60.000">Genovesa Milo 1/4 $60.000</option>
                <option value="Genovesa Milo 1/2 $80.000">Genovesa Milo 1/2 $80.000</option>
                <option value="Genovesa oreo 1/4 $60.000">Genovesa oreo 1/4 $60.000 </option>
                <option value="Genovesa oreo 1/2$80.000">Genovesa oreo 1/2 $80.000 </option>
                <option value="Genovesa caramelo areq 1/4 $60.000">Genovesa caramelo areq 1/4 $60.000 </option>
                <option value="Genovesa caramelo areq 1/2 $80.000">Genovesa caramelo areq 1/2 $80.000</option>
                <option value="Genovesa maracuyá 1/4 $60.000">Genovesa maracuyá 1/4 $60.000 </option>
                <option value="Genovesa maracuyá 1/2 $80.000">Genovesa maracuyá 1/2 $80.000 </option>
                <option value="Torta red velvet 1/4 $60.000">Torta red velvet 1/4 $60.000</option>
                <option value="Torta red velvet 1/2 $80.000">Torta red velvet 1/2 $80.000 </option>
                <option value="Rollo de fresa $80.000">Rollo de fresa $80.000 </option>

            </select>
                <!-- Agrega más opciones aquí -->
            </select>

            
        </div>

          <div style="margin-bottom: 10px;">
            <label>Cantidad:</label>
            <input type="number" name="cantidad[${index}]" class="form-control" min="1" value="1" required style="width: 60px; font-size: 17px; padding: 2px; height: auto;" >
        </div>

        <div class="adiciones" id="adiciones_${index}">
            <label>Adiciones:</label>
            <select name="adiciones[${index}][]" class="form-select" required>
             
                <option value="" disabled selected>Seleccione una adición</option>
                 <option value="ninguna">ninguna</option>
                   <option value="Salsa de arequipe">Salsa de arequipe</option>
        <option value="Salsa lechera">Salsa lechera</option>
        <option value="Salsa de fresa">Salsa de fresa</option>
        <option value="Salsa de chocolate">Salsa de chocolate</option>
        <option value="Chispas de chocolate">Chispas de chocolate</option>
        <option value="Crema Chantilly">Crema Chantilly</option>
        <option value="M&M">M&M</option>
        <option value="Milo">Milo</option>
        <option value="fruta fresa">Fruta fresa</option>
        <option value="fruta durazno">Fruta durazno</option>
        <option value="fruta guanabana">Fruta guanabana</option>
        <option value="Galleta oreo">Galleta oreo</option>
                <!-- Agrega más opciones aquí -->
            </select>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarAdicion(this, ${index})">Agregar Adición</button>
        <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Cancelar Producto</button>
    `;
    contenedor.appendChild(nuevoProducto);
}


function agregarAdicion(btn, index) {
    // Seleccionar el contenedor de adiciones por su ID único
    const adicionesDiv = document.getElementById(`adiciones_${index}`);
    if (!adicionesDiv) {
        console.error(`No se encontró el contenedor de adiciones para el índice ${index}`);
        return;
    }

    // Crear un nuevo selector de adiciones
    const nuevaAdicion = document.createElement("select");
    nuevaAdicion.name = `adiciones[${index}][]`;
    nuevaAdicion.classList.add("form-select");
    nuevaAdicion.required = true;
    nuevaAdicion.innerHTML = `
        <option value="" disabled selected>Seleccione una adición</option>
         <option value="ninguna">ninguna</option>
           <option value="Salsa de arequipe">Salsa de arequipe</option>
        <option value="Salsa lechera">Salsa lechera</option>
        <option value="Salsa de fresa">Salsa de fresa</option>
        <option value="Salsa de chocolate">Salsa de chocolate</option>
        <option value="Chispas de chocolate">Chispas de chocolate</option>
        <option value="Crema Chantilly">Crema Chantilly</option>
        <option value="M&M">M&M</option>
        <option value="Milo">Milo</option>
        <option value="fruta fresa">Fruta fresa</option>
        <option value="fruta durazno">Fruta durazno</option>
        <option value="fruta guanabana">Fruta guanabana</option>
        <option value="Galleta oreo">Galleta oreo</option>
        <!-- Agrega más opciones aquí -->
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