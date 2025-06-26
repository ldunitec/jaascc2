<script>
    let tabla;
    $(function() {
        tabla = $("#indexTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.empleados.data') }}", // Asegúrate que esta ruta funcione
            pageLength: 10,
            language: {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ ",
                "infoEmpty": "Mostrando 0 a 0 de 0 ",
                "infoFiltered": "(Filtrado de _MAX_ total )",
                "lengthMenu": "Mostrar _MENU_ ",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [
                [1, 'asc']
            ],
            columnDefs: [{
                    targets: 0,
                    width: '50px'
                }, // Índice
                {
                    targets: -1,
                    width: '100px'
                } // Acciones
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'correo',
                    name: 'correo'
                },
                {
                    data: 'telefono',
                    name: 'telefono'
                },
                {
                    data: 'puesto',
                    name: 'puesto'
                },


                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            buttons: [{
                    text: '<i class="fas fa-copy"></i> COPIAR',
                    extend: 'copy',
                    className: 'btn btn-default'
                },
                {
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    extend: 'pdf',
                    className: 'btn btn-danger'
                },
                {
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    extend: 'csv',
                    className: 'btn btn-info'
                },
                {
                    text: '<i class="fas fa-file-excel"></i> EXCEL',
                    extend: 'excel',
                    className: 'btn btn-success'
                },
                {
                    text: '<i class="fas fa-print"></i> IMPRIMIR',
                    extend: 'print',
                    className: 'btn btn-warning'
                }
            ],
            dom: 'Bfrtip'
        });

        // tabla.buttons().container().appendTo('#clientesTable_wrapper .col-md-6:eq(0)');
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnCreate = document.getElementById('btnCreate');
        // const form = document.getElementById('formCliente');
        // const erroresDiv = document.getElementById('errores');
        // const cardFormulario = document.getElementById('cardFormulario');
        // const btnMostrarFormulario = document.getElementById('btnMostrarFormulario');
        // const btnCancelar = document.getElementById('btnCancelar');
        const btnEdit = document.getElementById('btnEdit');
        // const btnDelete = document.getElementById('btnDelete');
        const urlEdit = "{{ url('admin/empleados') }}/";
        const urlCreate = "{{ url('admin/empleados') }}";

        btnCreate.addEventListener('click', openModal);

           function openModal() {
            $('#empleadoId').val('');
            $('#nombre').val('');
            $('#correo').val('');
            $('#telefono').val('');
            $('#puesto').val('');
            $('#empleadoModal').modal('show');
        }

        $('#empleadoForm').submit(function(e) {
            e.preventDefault();
            let id = $('#empleadoId').val();
            let url = id ? urlEdit + id : urlCreate;
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
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

     

        $(document).on('click', '.btnEdit', function() {
            const id = $(this).data('id');


            $.get(urlEdit + id, function(emp) {
                $('#empleadoId').val(emp.id);
                $('#nombre').val(emp.nombre);
                $('#correo').val(emp.correo);
                $('#telefono').val(emp.telefono);
                $('#puesto').val(emp.puesto);
                $('#empleadoModal').modal('show');
            });
        });

        // metodopara enviar para actualizar
        $('#empleadoForm').submit(function(e) {
            e.preventDefault();
            const id = $('#empleadoId').val();

            const url = id ? urlEdit + id : urlCreate;
            const method = id ? 'PUT' : 'POST';
            let formData = $(this).serialize();
            // console.log(formData);
            if (id) formData += '&_method=PUT';

            $.ajax({
                url: url,
                type:method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    Swal.fire('Éxito', response.message, 'success');
                    $('#empleadoForm')[0].reset();
                    // $('#cardFormulario').hide();
                      $('#empleadoModal').modal('hide');
                    tabla.ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'No se pudo guardar.', 'error');
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `/admin/clientes/${id}`;
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr(
                                        'content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire('Eliminado', data.message, 'success');
                            $('#clientesTable').DataTable().ajax.reload();
                        })
                        .catch(error => {
                            Swal.fire('Error',
                                'No se pudo eliminar al cliente.', 'error');
                            console.error(error);
                        });
                }
            });
        });
    });
    
</script>
