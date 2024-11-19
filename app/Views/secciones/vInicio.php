<?php $session = \Config\Services::session(); ?>
<style>
.contenedor {
    display: grid;
    grid-auto-flow: column;
    gap: 10px;
    /* Espacio entre los botones */
}

.boton {
    padding: 1px 1px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    border-radius: 50px;
}
</style>


<h1 style="color:black">Bienvenido <?php echo $nombre_completo; ?></h1>
<h4>Resgistros</h4>

<table id="table" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table" data-search="true"
    data-search-highlight="true" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
    data-show-refresh="true" data-header-style="headerStyle" data-url="<?=base_url("/index.php/Inicio/getPrincipal")?>">
    <thead>
        <tr>
            <th data-field="nombre_completo" data-width="20" data-sortable="true">NOMBRE</th>
            <th data-field="correo" data-width="100" data-sortable="true">CORREO</th>
            <th data-field="curp" data-width="50" data-sortable="true">CURP</th>
            <th data-field="id_nivel" data-width="100" data-sortable="true" data-tooltip="true">NIVEL</th>
            <th data-field="rfc" data-width="100" data-sortable="true" data-tooltip="true">RFC</th>
            <th data-field="area" data-width="100" data-sortable="true" data-tooltip="true">AREA</th>
            <th data-field="dsc_sexo" data-width="100" data-sortable="true" data-tooltip="true">SEXO</th>
            <th data-field="id_usuario" data-width="100" data-sortable="true"
                data-formatter="ini.inicio.formattAcciones" data-tooltip="true">ACCIONES</th>
        </tr>
    </thead>
</table>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel2"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div> <!-- end modal header -->
            <div class="modal-body">
                <form id="formUsuario2">
                    <div class="mb-3 ">
                        <input type="hidden" class="form-control" id="id_usuario" name="id_usuario">
                        <input type="hidden" class="form-control" id="editar" name="editar" value="1">
                    </div>
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
                                        <?php echo $g->dsc_nivel . ' ' . $g->denominacion_tabular; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="id_dependencia" class="form-label">DEPENDENCIA</label>
                                <select class="form-control select2" id="id_dependencia" name="id_dependencia"
                                    style="z-index:100;">
                                    <option>Seleccione</option>
                                    <?php foreach ($cat_dependencia as $dep): ?>
                                    <option value="<?php echo $dep->id_dependencia; ?>">
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
                                <label for="denominacion_funcional" class="form-label campoObligatorio">DEMONINACION
                                    FUNCIONAL</label>
                                <input type="text" autocomplete="off" class="form-control" id="denominacion_funcional"
                                    name="denominacion_funcional" placeholder="DEMONINACION FUNCIONAL"
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
                                <label for="id_perfil" class="form-label">PERFIL</label>
                                <select class="form-control select2" data-toggle="select2" id="id_perfil"
                                    name="id_perfil" data-placeholder="Seleccione" style="z-index:100;">
                                    <option value="0">Seleccione</option>
                                    <?php foreach ($cat_perfil as $p): ?>
                                    <?php 
                                            // Verifica si el perfil actual en sesión es igual a 5
                                            if ($session->get('id_perfil') >= 4) {
                                                if ($p->id_perfil >= 4 && $p->id_perfil <= 6): ?>
                                    <option value="<?php echo $p->id_perfil; ?>">
                                        <?php echo $p->dsc_perfil; ?>
                                    </option>
                                    <?php endif;
                                            } else {
                                                ?>
                                    <option value="<?php echo $p->id_perfil; ?>">
                                        <?php echo $p->dsc_perfil; ?>
                                    </option>
                                    <?php }
                                        ?>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="usuario" class="form-label campoObligatorio">USUARIO</label>
                                <input type="text" autocomplete="off" class="form-control" id="usuario" name="usuario"
                                    placeholder="USUARIO" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 position-relative" id="">
                                <label for="contrasenia" class="form-label campoObligatorio">CONTRASEÑA</label>
                                <input type="password" autocomplete="off" class="form-control" id="contrasenia"
                                    name="contrasenia" placeholder="CONTRASEÑA"
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
                </form>
            </div>
            <!-- end modal footer -->
        </div> <!-- end modal content-->
    </div> <!-- end modal dialog-->
</div> <!-- end modal-->

<link href="<?php echo (base_url('/assets/bootstrap-table-master/dist_/bootstrap-table.min.css'));?>" rel="stylesheet">
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/bootstrap-table.min.js');?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/tableExport.min.js');?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/bootstrap-table-locale-all.min.js');?>">
</script>
<script
    src="<?php echo base_url('/assets/bootstrap-table-master/dist_/extensions/export/bootstrap-table-export.min.js');?>">
</script>
<script>
$(document).ready(function() {
    ini.inicio.updateUsuario();
    $('.select2').select2({
        multiple: false,
    });
    $('#id_municipio, #id_nivel, #id_perfil').select2({
        dropdownParent: $('#staticBackdrop2')
    });

    function headerStyle() {
        return {
            css: {
                background: '#000099', // Fondo del encabezado
                color: '#FFFFFF' // Color del texto del encabezado
            }
        };
    }
    $('#staticBackdrop2').on('hidden.bs.modal', function(e) {

        $('#formUsuario2')[0].reset();
    });

});
</script>