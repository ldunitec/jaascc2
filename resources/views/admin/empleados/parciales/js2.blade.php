<script>
let tabla;

$(document).ready(function() {
    tabla = $('#empleadosTable').DataTable({
        ajax: '{{ route('admin.empleados.list') }}',
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'correo' },
            { data: 'telefono' },
            { data: 'puesto' },
            {
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-warning btn-sm" onclick="editEmpleado(${data.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteEmpleado(${data.id})">Eliminar</button>
                    `;
                }
            }
        ]
    });

    $('#empleadoForm').submit(function(e) {
        e.preventDefault();
        let id = $('#empleadoId').val();
        let url = id ? `/admin/empleados/${id}` : `{{ route('admin.empleados.store') }}`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: {
                _token: '{{ csrf_token() }}',
                nombre: $('#nombre').val(),
                correo: $('#correo').val(),
                telefono: $('#telefono').val(),
                puesto: $('#puesto').val(),
                _method: method
            },
            success: function() {
                $('#empleadoModal').modal('hide');
                $('#empleadoForm')[0].reset();
                tabla.ajax.reload();
                Swal.fire('Éxito', 'Empleado guardado', 'success');
            }
        });
    });
});

function openModal() {
    $('#empleadoId').val('');
    $('#nombre').val('');
    $('#correo').val('');
    $('#telefono').val('');
    $('#puesto').val('');
    $('#empleadoModal').modal('show');
}

function editEmpleado(id) {
    $.get(`/empleados/${id}`, function(emp) {
        $('#empleadoId').val(emp.id);
        $('#nombre').val(emp.nombre);
        $('#correo').val(emp.correo);
        $('#telefono').val(emp.telefono);
        $('#puesto').val(emp.puesto);
        $('#empleadoModal').modal('show');
    });
}

function deleteEmpleado(id) {
    Swal.fire({
        title: '¿Eliminar empleado?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/empleados/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    tabla.ajax.reload();
                    Swal.fire('Eliminado', 'Empleado eliminado correctamente', 'success');
                }
            });
        }
    });
}
</script>