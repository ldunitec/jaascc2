<script>
    $(function() {
        let tabla = $("#clientesTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.clientes.data') }}", // Asegúrate que esta ruta funcione
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
                    data: 'direccion',
                    name: 'direccion'
                },
                {
                    data: 'activo',
                    name: 'activo'
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
        const form = document.getElementById('formCliente');
        const erroresDiv = document.getElementById('errores');
        const cardFormulario = document.getElementById('cardFormulario');
        const btnMostrarFormulario = document.getElementById('btnMostrarFormulario');
        const btnCancelar = document.getElementById('btnCancelar');
        const btnEdit = document.getElementById('btnEdit');
        const btnDelete = document.getElementById('btnDelete');
        const urlEdit = "{{ url('admin/clientes') }}/";
        const urlCreate = "{{ url('admin/clientes') }}";

        // ver formulario 
        btnMostrarFormulario.addEventListener('click', () => {
            cardFormulario.style.display = 'block';
            $('#formCliente')[0].reset();
            $('#formTitulo').text('Nuevo Cliente');
            $('#id').val('');
        });
        // cancelar
        btnCancelar.addEventListener('click', () => {
            cardFormulario.style.display = 'none';
        });

        // metodo abrir el formulario para editar 
        $(document).on('click', '.btnEdit', function() {
            // console.log(data.id); 
            const id = $(this).data('id');
            $.get(urlEdit + id, function(data) {
                $('#id').val(data.id);
                $('#nombre').val(data.nombre);
                $('#correo').val(data.correo);
                $('#telefono').val(data.telefono);
                $('#direccion').val(data.direccion);
                $('#cardFormulario').show();
                $('#formTitulo').text('Editar Cliente');
            });
        });

        // metodopara enviar para actualizar
        $('#formCliente').submit(function(e) {
            e.preventDefault();

            const id = $('#id').val();

            const url = id ? urlEdit + id : urlCreate;
            const method = id ? 'PUT' : 'POST';
            let formData = $(this).serialize();
            // console.log(formData);
            if (id) formData += '&_method=PUT';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    Swal.fire('Éxito', response.message, 'success');
                    $('#formCliente')[0].reset();
                    $('#cardFormulario').hide();
                    $('#clientesTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'No se pudo guardar.', 'error');
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id');
            // Swal.fire({
            //     title: '¿Estás seguro?',
            //     text: "¡Esta acción no se puede deshacer!",
            //     // icon: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#d33',
            //     cancelButtonColor: '#3085d6',
            //     confirmButtonText: 'Sí, eliminar'
            // }).then((result) => {
                // if (result.isConfirmed) {
                       const url = "{{ url('admin/clientes') }}/" + id;
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content'),
                                'Content-Type': 'application/json',
                            }
                        }).then(response => response.json())
                        .then(data => {
                            Swal.fire('Eliminado', data.message, 'success');
                            $('#clientesTable').DataTable().ajax.reload();
                        })
                        .catch(error => {
                            Swal.fire('Error', 'No se pudo eliminar al cliente.', 'error');
                            console.error(error);
                        });
                // }
            });
        });
    // });
</script>
