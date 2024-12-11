<?php $session = \Config\Services::session(); ?>
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <!--    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">eCommerce</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol> -->
                </div>
                <h4 class="page-title">Dependencia / Entidad / Ente</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <button type='button' href="javascript:void(0);" class="btn btn-danger mb-2"
                                data-bs-toggle='modal' onclick="agregar()"><i
                                    class="mdi mdi-plus-circle me-2"></i> Agregar</button>
                        </div>
                        <div class="col-sm-8">

                        </div><!-- end col-->
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active">
                            <div class="table-responsive">
                                <table id="getDependencia" data-locale="es-MX" data-toolbar="#toolbar"
                                    data-toggle="table" data-search="true" data-search-highlight="true"
                                    data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
                                    data-show-refresh="true" data-header-style="headerStyle"
                                    data-url="<?=base_url("/index.php/Principal/getDependencia")?>">
                                    <thead>
                                        <tr>
                                            <th data-field="id_dependencia" data-width="20" data-sortable="true">ID</th>
                                            <th data-field="dsc_dependencia" data-width="20" data-sortable="true">
                                                NOMBRE</th>

                                            <th data-field="id_participante" data-width="100" data-sortable="true"
                                                data-formatter="saeg.principal.formattDependencia" data-tooltip="true">
                                                ACCIONES</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>




                    </div>


                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- container -->

</div> <!-- content -->


<!-- Modal -->

<div id="modalDependencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Editar Dependencia / Entidad / Ente</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="formDependencia" name="formDependencia">
                <input type="hidden" value="0" id="id_dependencia" name="id_dependencia">
        
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="id_depen" class="form-label campoObligatorio">ID DEPENDENCIA</label>
                                <input type="text" autocomplete="off" class="form-control" id="id_depen"
                                    name="id_depen" placeholder="ID DEPENDENCIA" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="dsc_dependencia" class="form-label campoObligatorio">NOMBRE</label>
                                <input type="text" autocomplete="off" class="form-control" id="dsc_dependencia"
                                    name="dsc_dependencia" placeholder="NOMBRE">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" id="btn_guardar">
                    <button type="button" class="btn btn-light" id="closeModalButton"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>

                </div>
                <div class="modal-footer" style="display:none" id="btn_load">
                    <button class="btn btn-primary" id="btn_load" type="button" disabled>
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- third party js ends -->

<!-- demo app -->
<link href="<?php echo base_url('/assets/css/vendor/dataTables.bootstrap5.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('/assets/css/vendor/responsive.bootstrap5.css')?>" rel="stylesheet" type="text/css" />



<!-- third party css end -->

<!-- App css -->
<link href="<?php echo base_url('/assets/css/icons.min.css')?>" rel="stylesheet" type="text/css" />


<script src="<?php echo base_url('/assets/js/vendor/dropzone.min.js')?>"></script>
<!-- init js -->
<script src="<?php echo base_url('/assets/js/ui/component.fileupload.js')?>"></script>
<script src="<?php echo base_url('/assets/js/pages/demo.customers.js');?>"></script>
<script src="<?php echo base_url('/assets/js/vendor/dataTables.checkboxes.min.js');?>"></script>
<script src="<?php echo base_url('/assets/js/vendor/responsive.bootstrap5.min.js');?>"></script>
<script src="<?php echo base_url('/assets/js/vendor/dataTables.bootstrap5.js');?>"></script>
<script src="<?php echo base_url('/assets/js/vendor/jquery.dataTables.min.js');?>"></script>



<script src="<?php echo base_url('/assets/js/vendor/dataTables.responsive.min.js')?>"></script>


<!-- Datatable Init js -->


<script>
$(document).ready(function() {
    saeg.principal.formDependencia();




    // Restablecer el formulario al cerrar el modal
    $('#modalDependencia').on('hidden.bs.modal', function() {
        $('#formDependencia')[0].reset();
    });
    $('#basic-datatable').DataTable({
        select: false,
        "columnDefs": [{
            className: "Name",
            "targets": [0],
            "visible": false,
            "searchable": false
        }]
    });


});

function agregar() {
    Swal.fire({
            title: 'Ingrese el nombre de la nueva dependencia',
            input: 'text',
            inputLabel: 'Nombre de la dependencia',
            inputPlaceholder: 'dependencia',
            showCancelButton: true,
            confirmButtonText: 'Crear',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                    return '¡El nombre de la dependencia es obligatorio!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const dependencia = result.value;
                $.ajax({
                    url: `${base_url}index.php/Agregar/crearDependencia`,
                    method: "POST",
                    data: {
                        dsc_dependencia: dependencia
                    },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire('Éxito', 'Subcategoría creada correctamente', 'success');
                            $('#getDependencia').bootstrapTable('refresh');
                        } else {
                            Swal.fire('Error', 'Error al crear la subcategoría: ' + response.message, 'error');
                        }
                    },
                    error: function(error) {
                        Swal.fire('Error', 'Error en la solicitud: ' + error.responseText, 'error');
                    }
                });
            }
        });
}




</script>