<!-- views/producto/index.php -->
<div class="row mb-4">
    <div class="col">
        <h2>Listado de Productos</h2>
    </div>
    <div class="col text-end">
        <a href="<?= BASE_URL ?>/reports/pdf" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
        <a href="<?= BASE_URL ?>/reports/excel" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fas fa-plus"></i> Nuevo Producto
        </button>
    </div>
</div>

<!-- Filtros -->
<div class="shadow-none p-3 mb-5 bg-body-tertiary rounded">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control"
                        id="searchProduct"
                        placeholder="Buscar producto por nombre..."
                        onkeyup="searchProducts(event)">
                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <!-- Los productos se cargarán aquí dinámicamente -->
        </tbody>
    </table>
</div>

<!-- Paginación -->
<nav aria-label="Navegación de productos">
    <ul class="pagination justify-content-center" id="pagination">
        <!-- La paginación se generará dinámicamente -->
    </ul>
</nav>

<!-- Modal para Crear/Editar Producto -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="image" accept="image/*">
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este producto?</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vista Previa -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="previewImage" src="" alt="Imagen del producto" class="img-fluid rounded">
                </div>
                <dl class="row">
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9" id="previewName"></dd>

                    <dt class="col-sm-3">Descripción:</dt>
                    <dd class="col-sm-9" id="previewDescription"></dd>

                    <dt class="col-sm-3">Precio:</dt>
                    <dd class="col-sm-9" id="previewPrice"></dd>

                    <dt class="col-sm-3">Stock:</dt>
                    <dd class="col-sm-9" id="previewStock"></dd>

                    <dt class="col-sm-3">Creado:</dt>
                    <dd class="col-sm-9" id="previewCreated"></dd>

                    <dt class="col-sm-3">Actualizado:</dt>
                    <dd class="col-sm-9" id="previewUpdated"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>