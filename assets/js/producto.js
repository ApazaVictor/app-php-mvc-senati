//OBTENER RODUCTO JS
//assets/js/producto.js

document.addEventListener('DOMContentLoaded',function(){
    //alert('Victor');
    obtenerProducto();
})

async function obtenerProducto() {
    try {
        const respuesta = await fetch('productos/obtener-todo');
        const resultado = await respuesta.json();
        
        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        const productos = resultado.data;
        console.log(productos);

        const tbody = document.getElementById('productsTableBody');
        tbody.innerHTML = '';
        
        productos.forEach(product => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${product.id_producto}</td>
                <td>
                    ${product.imagen 
                        ? `<img src="assets/uploads/${product.imagen}" 
                            alt="${product.nombre}" 
                            class="img-thumbnail" 
                            style="max-width: 50px; max-height: 50px;">`
                        : '<span class="text-muted">Sin imagen</span>'}
                </td>
                <td>${product.nombre}</td>
                <td>${product.descripcion || '<span class="text-muted">Sin descripción</span>'}</td>
                <td>$${parseFloat(product.precio).toFixed(2)}</td>
                <td>${product.stock}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="mostrarDataEditar(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${product.id_producto})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        showAlert('error', 'Error al cargar los productos: ' + error.message);
    }
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alertDiv);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

async function guardarProducto(){
    try {
        const formData = new FormData();
        const nombre = document.getElementById('name').value;
        const descripcion = document.getElementById('description').value;
        const precio = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        const imagenFile = document.getElementById('image').files[0];

        formData.append('nombre', nombre);
        formData.append('descripcion', descripcion);
        formData.append('precio', precio);
        formData.append('stock', stock);

        if (imagenFile) {
            formData.append('imagen', imagenFile);
        }

        // const url = editingProductId ? 'products/update' : 'products/create';
        // if (editingProductId) {
        //     formData.append('id', editingProductId);
        // }

        const response = await fetch('productos/guardar-producto', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'error') {
            throw new Error(result.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', result.message);

        // Recargar la lista de productos
        obtenerProducto();

        // Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}

async function actualizarProducto(){
    try {
        const formData = new FormData();
        const id = document.getElementById('productId').value;
        const nombre = document.getElementById('name').value;
        const descripcion = document.getElementById('description').value;
        const precio = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        const imagenFile = document.getElementById('image').files[0];

        formData.append('id', id);
        formData.append('nombre', nombre);
        formData.append('descripcion', descripcion);
        formData.append('precio', precio);
        formData.append('stock', stock);

        if (imagenFile) {
            formData.append('imagen', imagenFile);
        }

        // const url = editingProductId ? 'products/update' : 'products/create';
        // if (editingProductId) {
        //     formData.append('id', editingProductId);
        // }

        const response = await fetch('productos/actualizar-producto', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'error') {
            throw new Error(result.message);
        }

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showAlert('success', result.message);

        // Recargar la lista de productos
        obtenerProducto();

        //Resetear el formulario
        resetForm();        
    } catch (error) {
        showAlert('error', error.message);
    }
}

async function eliminarProducto(id) {
    console. log('entro aqui a eliminar ')
    try {
        if (!confirm('¿Está seguro de que desea eliminar?')) {
            return;
        }

        const respuesta = await fetch('tipo-documento/eliminar-producto', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_tipoDocumento:id })
        });

        const resultado = await respuesta.json();

        if (resultado.status === 'error') {
            throw new Error(resultado.message);
        }

        showAlert('success', resultado.message);
        listar();

    } catch (error) {
        showAlert('error', error.message);
    }
}

function mostrarDataEditar(producto){
    console.log(producto);

    document.getElementById('productId').value = producto.id_producto;
    document.getElementById('name').value = producto.nombre;
    document.getElementById('description').value = producto.descripcion;
    document.getElementById('price').value = producto.precio;
    document.getElementById('stock').value = producto.stock;
    document.getElementById('image').value = '';
    
    
    // Actualizar título del modal
    document.getElementById('modalTitle').textContent = 'Editar Producto';
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();

    
}

function almacenar(){
    if(document.getElementById('productId').value){
        // Actualizar producto
        actualizarProducto();
    }else{
        // Crear producto
        guardarProducto();
    }
}

function resetForm() {
    document.getElementById('productId').value = '';
    document.getElementById('productForm').reset();
    document.getElementById('modalTitle').textContent = 'Nuevo Producto';
}





