function agregarProducto() {
    const contenedor = document.getElementById("productos");
    const index = document.querySelectorAll(".producto").length; // Índice del nuevo producto

    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("producto");
    nuevoProducto.style.display = "flex";
    nuevoProducto.style.gap = "10px";
    nuevoProducto.style.alignItems = "center";
    nuevoProducto.style.flexWrap = "wrap";

    nuevoProducto.innerHTML = `
        <div>
            <label>Producto:</label>
            <select class="form-select" name="productos[]" required>
                <option value="Merengon mixto">Merengon mixto</option>
                <option value="Merengon fresa">Merengon fresa</option>
                <option value="Merengon Guanabana">Merengon Guanabana</option>
                <option value="Merengon fresa y durazno">Merengon fresa y durazno</option>
                <option value="Merengon fresa guanabana">Merengon fresa guanabana</option>
                <option value="Merengon Durazno guanabana">Merengon Durazno guanabana</option>
                <option value="Merengon Oreo">Merengon Oreo</option>
                <option value="Merengon M&M">Merengon M&M</option>
                <option value="Merengon Milo">Merengon Milo</option>
                <option value= Fresas con crema">Fresas con crema</option>
                <option value="porciontorta">Porcion de torta</option>
            </select>
        </div>
        <div class="adiciones" style="display: flex; gap: 10px; align-items: center;">
            <label>Adiciones:</label>
            <select class="form-select" name="adiciones[${index}][]" >
                <option value=""></option>
                <option value="Arequipe">Arequipe</option>
                <option value="Lechera">Lechera</option>
                <option value="Fresa">Fresa</option>
                <option value="Chocolate">Chocolate</option>
                <option value="Chispas de chocolate">Chispas de chocolate</option>
                <option value="Crema Chantilly">Crema Chantilly</option>
                <option value="M&M">M&M</option>
                <option value="Milo">Milo</option>
                
            </select>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarAdicion(this)">Agregar Adición</button>
        <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Cancelar Producto</button>

    `;
    contenedor.appendChild(nuevoProducto);
}

// Función para agregar una nueva adición al producto actual
function agregarAdicion(btn) {
    const adicionesDiv = btn.previousElementSibling; // Div que contiene las adiciones
    const index = Array.from(adicionesDiv.parentNode.parentNode.children).indexOf(adicionesDiv.parentNode); // Índice del producto

    // Crear un nuevo select para adiciones
    const nuevaAdicion = document.createElement("select");
    nuevaAdicion.classList.add("form-select");
    nuevaAdicion.name = `adiciones[${index}][]`;
    nuevaAdicion.required = false;
    nuevaAdicion.innerHTML = `
        <option value=""></option>
        <option value="Arequipe">Arequipe</option>
        <option value="Lechera">Lechera</option>
        <option value="Fresa">Fresa</option>
        <option value="Chocolate">Chocolate</option>
        <option value="Chispas de chocolate">Chispas de chocolate</option>
        <option value="Crema Chantilly">Crema Chantilly</option>
        <option value="M&M">M&M</option>
        <option value="Milo">Milo</option>
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
                        method: 'POST',
                        data: { telefono: telefono },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                // Rellenar los campos con los datos obtenidos
                                $('#nombre').val(response.data.nombre);
                                $('#direccion').val(response.data.direccion);
                                $('#barrio').val(response.data.barrio);
                            } else {
                                // Limpiar los campos si el usuario no es encontrado
                                $('#nombre').val('');
                                $('#direccion').val('');
                                $('#barrio').val('');
                            }
                        },
                        error: function () {
                            alert('Error al buscar el usuario.');
                        }
                    });
                } else {
                    // Limpiar los campos si no se cumple la longitud mínima
                    $('#nombre').val('');
                    $('#direccion').val('');
                    $('#barrio').val('');
                }
            });
        });