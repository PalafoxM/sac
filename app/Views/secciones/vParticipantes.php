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
                <h4 class="page-title">Estudiantes en CampusGto</h4>
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
                                data-bs-toggle='modal' data-bs-target='#modalAltaParticipante' onclick="agregar()" ><i
                                    class="mdi mdi-plus-circle me-2"></i> Agregar Estudiante</button>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-success mb-2 me-1"><i
                                        class="mdi mdi-cog"></i></button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#standard-modal"
                                    class="btn btn-light mb-2">Importar</button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#home1" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                <span class="d-none d-md-block">Usuarios Listos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                <span class="d-none d-md-block">Usuarios Detenidos</span>
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="home1">
                            <div class="table-responsive">
                                <table id="getParticipantes" data-locale="es-MX" data-toolbar="#toolbar"
                                    data-toggle="table" data-search="true" data-search-highlight="true"
                                    data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
                                    data-show-refresh="true" data-header-style="headerStyle"
                                    data-url="<?=base_url("/index.php/Principal/getParticipantes")?>">
                                    <thead>
                                        <tr>
                                            <th data-field="curp" data-width="20" data-sortable="true">CURP</th>
                                            <th data-field="nombre_completo" data-width="20" data-sortable="true">
                                                NOMBRE</th>
                                            <th data-field="correo" data-width="100" data-sortable="true">CORREO</th>
                                            <th data-field="rfc" data-width="50" data-sortable="true"
                                                data-tooltip="true">RFC
                                            </th>
                                            <th data-field="observaciones" data-width="100" data-sortable="true"
                                                data-tooltip="true"
                                                data-formatter="saeg.principal.observacionesFormatter">OBSERVACIONES
                                            </th>
                                            <th data-field="id_participante" data-width="100" data-sortable="true"
                                                data-formatter="saeg.principal.formattParticipantes" data-tooltip="true">
                                                ACCIONES</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane" id="profile1">
                            <div class="table-responsive">
                                <table id="tableDetenidos" data-locale="es-MX" data-toolbar="#toolbar"
                                    data-toggle="table" data-search="true" data-search-highlight="true"
                                    data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
                                    data-show-refresh="true" data-header-style="headerStyle"
                                    data-url="<?=base_url("/index.php/Principal/getDetenidos")?>">
                                    <thead>
                                        <tr>
                                            <th data-field="curp" data-width="20" data-sortable="true">CURP</th>
                                            <th data-field="nombre_completo" data-width="100" data-sortable="true">
                                                NOMBRE</th>
                                            <th data-field="correo" data-width="100" data-sortable="true">CORREO</th>
                                            <th data-field="rfc" data-width="50" data-sortable="true"
                                                data-tooltip="true">RFC
                                            </th>
                                            <th data-field="observaciones" data-width="100" data-sortable="true"
                                                data-tooltip="true"
                                                data-formatter="saeg.principal.observacionesFormatter">OBSERVACIONES
                                            </th>
                                            <th data-field="id_detenido" data-width="100" data-sortable="true"
                                                data-formatter="saeg.principal.formattDetenido" data-tooltip="true">
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

<div id="modalAltaParticipante" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Agregar Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="formParticipante" name="formParticipante">
                <input type="hidden" value="0" id="editar" name="editar">
                <input type="hidden" value="0" id="id_detenido" name="id_detenido">
                <input type="hidden" value="0" id="id_participante" name="id_participante">
                <input type="hidden"  id="curp_viejo" name="curp_viejo" >
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3">
                            <label for="curp" class="form-label">CURP</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon1"><i id="icono"
                                        class="dripicons-search"></i>
                                    <div style="display:none;" id="spinner" class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </span>
                                <input type="text" class="form-control" oninput="validarCURP()" placeholder="CURP"
                                    aria-label="Username" id="curp" name="curp" aria-describedby="basic-addon1"
                                    autocomplete="off">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="nombre" class="form-label campoObligatorio">NOMBRE</label>
                                <input type="text" autocomplete="off" class="form-control" id="nombre" name="nombre"
                                    placeholder="NOMBRE">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="primer_apellido" class="form-label campoObligatorio">PRIMER
                                    APELLIDO</label>
                                <input type="text" autocomplete="off" class="form-control" id="primer_apellido"
                                    name="primer_apellido" placeholder="PRIMER APELLIDO">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="segundo_apellido" class="form-label campoObligatorio">SEGUNDO
                                    APELLIDO</label>
                                <input type="text" autocomplete="off" class="form-control" id="segundo_apellido"
                                    name="segundo_apellido" placeholder="SEGUNDO APELLIDO">
                            </div>
                        </div>


                    </div>
                    <div class="row">

                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="fec_nac" class="form-label campoObligatorio">FECHA
                                    NACIMIENTO</label>
                                <input type="date" autocomplete="off" class="form-control" id="fec_nac" name="fec_nac"
                                    placeholder="FEC. NACIMIENTO">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="rfc" class="form-label campoObligatorio">RFC</label>
                                <input type="text" autocomplete="off" class="form-control" id="rfc" name="rfc"
                                    placeholder="NOMBRE COMPLETO">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="correo" class="form-label campoObligatorio">CORREO</label>
                                <input type="text" autocomplete="off" class="form-control" id="correo" name="correo"
                                    placeholder="CORREO ELECTRONICO">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="id_sexo" class="form-label">SEXO</label>
                                <select class="form-control" id="id_sexo" name="id_sexo" data-placeholder="seleccione"
                                    style="z-index:100;">
                                    <option>seleccione</option>
                                    <option value="1">HOMBRE</option>
                                    <option value="2">MUJER</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="id_nivel" class="form-label">NIVEL TABULAR</label>
                                <select class="form-control select2" data-toggle="select2" id="id_nivel" name="id_nivel"
                                    data-placeholder="Seleccione" style="z-index:100;">
                                    <option>Seleccione</option>
                                    <?php foreach ($cat_nivel as $g): ?>
                                    <option value="<?php echo $g->id_nivel; ?>">
                                        <?php echo $g->dsc_nivel.' '.$g->denominacion_tabular; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="id_dependencia" class="form-label">DEPENDENCIA</label>
                                <select class="form-control select2" id="id_dependencia" name="id_dependencia"
                                    style="z-index:100;" disabled>
                                    <option>Seleccione</option>
                                    <?php foreach ($cat_dependencia as $dep): ?>
                                    <option value="<?php echo $dep->id_dependencia; ?>"
                                        <?php echo ($dep->id_dependencia == $session->get('id_dependencia')) ? 'selected' : ''; ?>>
                                        <?php echo $dep->dsc_dependencia; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="funcion" class="form-label campoObligatorio">FUNCION</label>
                                <input type="text" autocomplete="off" class="form-control" id="funcion"
                                    name="funcion" placeholder="DENOMINACION FUNCIONAL"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="area" class="form-label campoObligatorio">AREA PERSONAL</label>
                                <input type="text" autocomplete="off" class="form-control" id="area" name="area"
                                    placeholder="GRUPO" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="jefe_inmediato" class="form-label campoObligatorio">FEJE/A
                                    INMEDIATO</label>
                                <input type="text" autocomplete="off" class="form-control" id="jefe_inmediato"
                                    name="jefe_inmediato" placeholder="SUPERVISOR"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="id_p" class="form-label">MUNICIPIO</label>
                                <select class="form-control select2" data-toggle="select2" id="id_municipio"
                                    name="id_municipio" data-placeholder="Seleccione" style="z-index:100;">
                                    <option>Seleccione</option>
                                    <?php foreach ($cat_municipio as $p): ?>
                                    <option value="<?php echo $p->id_municipio; ?>">
                                        <?php echo $p->dsc_municipio ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="centro_gestor" class="form-label campoObligatorio">CENTRO GESTOR</label>
                                <input type="text" autocomplete="off" class="form-control" id="centro_gestor"
                                    name="centro_gestor" placeholder="CENTRO GESTOR"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="correo_enlace" class="form-label campoObligatorio">CORREO ENLACE</label>
                                <input type="text" autocomplete="off" class="form-control" id="correo_enlace"
                                    name="correo_enlace" placeholder="CORREO ENLACE">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="denominacion_funcional" class="form-label campoObligatorio">DEMONINACION
                                    FUNCIONAL</label>
                                <input type="text" autocomplete="off" class="form-control" id="denominacion_funcional"
                                    name="denominacion_funcional" placeholder="DEMONINACION FUNCIONAL"
                                    oninput="this.value = this.value.toUpperCase();">
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

<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Subir Archivo</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form id="uploadCSVParticipantes" name="uploadCSVParticipantes" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fileParticipantes">Seleccionar Archivo CSV:</label>
                        <input type="file" name="fileParticipantes" id="fileParticipantes" accept=".csv" required
                            class="form-control">
                    </div>

                    <button id="load_csv" class="btn btn-primary mt-3" style="display:none">
                        <div class="spinner-grow" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saeg.principal.uploadCSVP()">Procesar
                    csv</button>
            </div>
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
    saeg.principal.formParticipante();
    

    // Inicializar select2 en los elementos requeridos dentro del modal
    $('#id_municipio, #id_nivel').select2({
        dropdownParent: $('#modalAltaParticipante')
    });

    // Restablecer el formulario al cerrar el modal
    $('#modalAltaParticipante').on('hidden.bs.modal', function() {
        $('#formParticipante')[0].reset();
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

function agregar(){
    $('#formParticipante')[0].reset();
}



// Constantes generales
//const API_CURP = "http://localhost:5600/api-curp";

const AUTH_TOKEN = "<?php echo env('TOKEN_API'); ?>"; 
const API_CURP = "<?php echo env('NODE_API_CURP'); ?>";

// Selectores de elementos DOM
const inputCurp = document.getElementById('curp');
const btnBuscar = document.getElementById('icono');
const spinner = document.getElementById('spinner');

// Función para validar CURP
function validarCURP() {
    console.log('entro a validar curp');
    const curp = inputCurp.value.trim().toUpperCase();
    inputCurp.value = curp; // Convertir a mayúsculas

    if (curp.length >= 18) {
        // Estado de "check" si la CURP tiene longitud suficiente
        toggleButtonState('check');

        inputCurp.style.color = "black";
        consultarCURP();
    } else if (curp.length === 0) {
        // Reiniciar estado del botón si el campo está vacío
        toggleButtonState('search');
    } else {
        // Estado de "cargando" mientras se escribe
        btnBuscar.classList.remove('dripicons-loading');
        toggleButtonState('loading');
        inputCurp.style.color = "red";
    }
}

// Función para alternar el estado del botón y spinner
function toggleButtonState(state) {
    spinner.style.display = state === 'loading' ? "block" : "none";
    btnBuscar.classList.remove('dripicons-search', 'dripicons-checkmark', 'dripicons-loading');

    if (state === 'check') btnBuscar.classList.add('dripicons-checkmark');
    //else if (state === 'loading') btnBuscar.classList.add('dripicons-loading');
    //else btnBuscar.classList.add('dripicons-search');
}

// Función para consultar la CURP
function consultarCURP() {
    const curp = inputCurp.value;

    if (curp.length !== 18) {
        Swal.fire("Error", 'Ingresa una CURP válida.', "error");
        $("#formParticipante")[0].reset();
        return;
    }

    $.ajax({
        url: API_CURP,
        type: 'POST',
        dataType: 'json',
        data: {
            curp: curp,
            script: 'Bitacora->Script:001/15',
            id_clues: '0780',
            id_usuario: 7
        },
        headers: {
            'Authorization': `Bearer ${AUTH_TOKEN}`
        },
        success: function(result) {
            console.log(result)
            if (result.datos) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Validado por RENAPO",
                    showConfirmButton: false,
                    timer: 1500
                });
                inputCurp.style.color = "green";
                toggleButtonState('check');
                mostrarCamposDatos(result.datos);
            }
            if (result.error) {
                inputCurp.style.color = "red";
                toggleButtonState('search');
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: result.respuesta,
                    showConfirmButton: false,
                    timer: 1500
                });
                inputCurp.style.color = "red";
                toggleButtonState('check');
                mostrarCamposDatos(result.datos);
            }


        },
        error: function(xhr) {
            console.log("Error:", xhr.responseText);
            inputCurp.style.color = "red";
        }
    });
}

// Función para mostrar los datos obtenidos de CURP en los campos correspondientes
function mostrarCamposDatos(datos) {
    $('#nombre').val(datos.nombre);
    $('#primer_apellido').val(datos.primerApellido);
    $('#segundo_apellido').val(datos.segundoApellido);
    $('#id_sexo').val(datos.sexo);
    $('#fec_nac').val(datos.fechaNacimiento);
    $('#rfc').val(datos.CURP.substring(0, 10));
}
</script>