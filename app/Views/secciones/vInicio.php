
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
        data-show-refresh="true" data-header-style="headerStyle"
        data-url="<?=base_url("/index.php/Inicio/getPrincipal")?>">
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
        <div class="modal-dialog modal-lg">
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
                        <div class="row g-2">
                            <div class="mb-3 col-md-3">
                                <label for="nombre" class="form-label">NOMBRE</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="primer_apellido" class="form-label">PRIMER APELLIDO</label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="segundo_apellido" class="form-label">SEGUNDO APELLIDO</label>
                                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="rfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="rfc" name="rfc">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="mb-3 col-md-3">
                                <label for="curp" class="form-label">CURP</label>
                                <input type="text" class="form-control" id="curp" name="curp">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="correo" class="form-label">CORREO</label>
                                <input type="text" class="form-control" id="correo" name="correo">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="jefe_inmediato" class="form-label">JEFE INMEDIATO</label>
                                <input type="text" class="form-control" id="jefe_inmediato" name="jefe_inmediato">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="area" class="form-label">AREA</label>
                                <input type="text" class="form-control" id="area" name="area">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="mb-3 col-md-3">
                                <label for="id_nivel" class="form-label">NIVEL</label>
                                <select class="form-control" id="id_nivel" name="id_nivel" data-placeholder="seleccione"
                                    style="z-index:100;">
                                    <option>seleccione</option>
                                    <?php foreach ($cat_nivel as $n): ?>
                                    <option value=<?php echo $n->id_nivel ?>> <?php echo $n->dsc_nivel ?> </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                            <label for="id_perfil" class="form-label">PERFIL</label>
                                <select class="form-control" id="id_perfil" name="id_perfil" data-placeholder="seleccione"
                                    style="z-index:100;">
                                    <option>seleccione</option>
                                    <?php foreach ($cat_perfil as $p): ?>
                                    <option value=<?php echo $p->id_perfil ?>> <?php echo $p->dsc_perfil ?> </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="usuario" class="form-label">USUARIO</label>
                                <input type="text" class="form-control" id="usuario" name="usuario">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="contrasenia" class="form-label">CONTRASEÑA</label>
                                <input type="text" class="form-control" id="contrasenia" name="contrasenia">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->

    <link href="<?php echo (base_url('/assets/bootstrap-table-master/dist_/bootstrap-table.min.css'));?>"
        rel="stylesheet">
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
