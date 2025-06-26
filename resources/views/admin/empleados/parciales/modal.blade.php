<!-- Modal -->
<div class="modal fade" id="empleadoModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="empleadoForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Formulario Empleado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          @csrf
          <input type="hidden" id="empleadoId">
          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" class="form-control" id="nombre" required>
          </div>
          <div class="mb-3">
            <label>Correo</label>
            <input type="email" class="form-control" id="correo" required>
          </div>
          <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" class="form-control" id="telefono">
          </div>
          <div class="mb-3">
            <label>Puesto</label>
            <input type="text" class="form-control" id="puesto">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>