<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnos 2.0</title>
</head>
<style>
      .contenedor {
            display: grid;
            grid-auto-flow: column;
            gap: 10px; /* Espacio entre los botones */
        }
        .boton {
            padding: 1px 1px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 50px;
        }
</style>
<body>
    <div class="row mt-3">
        <div class="col text-end">
            <button  class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#staticBackdrop' onclick='ini.inicio.limpiaModal();'>Agregar Usuario</button>
        </div>
    </div>
    <table
        id="table"
        data-locale="es-MX"
        data-toolbar="#toolbar"
        data-toggle="table"
        data-search="true"
        data-search-highlight="true"
        data-pagination="true"
        data-page-list="[10, 25, 50, 100, all]"
        data-sortable="true"
        data-show-refresh="true"
        data-header-style="headerStyle"
        data-url="<?=base_url("/index.php/Usuario/getUsuarios")?>">
        <thead>
            <tr>
                <th data-field="usuario" data-width="20" data-sortable="true">Usuario</th>
                <th data-field="descripcion_perfil" data-width="100" data-sortable="true">Perfil</th>
                <th data-field="nombre" data-width="50" data-sortable="true">Nombre</th>
                <th data-field="primer_apellido" data-width="100" data-sortable="true" >Primer apellido</th>
                <th data-field="segundo_apellido" data-width="100" data-sortable="true" data-tooltip="true">Segundo apellido</th>
                <th data-field="dsc_sexo" data-width="20" data-sortable="true"  class="text-center" >Sexo</th>
                <th data-field="NOMBRE_UNIDAD" data-width="20"  data-sortable="true">Unidad</th>
                <th data-field="correo" data-width="20"  data-sortable="true">Correo</th>
                <th data-field="id_usuario" data-width="20" data-sortable="true" data-formatter="ini.inicio.formattAcciones" class="text-center">Acciones</th>
            </tr>
        </thead>
    </table>  
  
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div> <!-- end modal header -->
            <div class="modal-body">
                <form id="formUsuario">
                    <div class="mb-3 ">
                        <input type="hidden" class="form-control" id="id_usuario" name="id_usuario" >
                        <input type="hidden" class="form-control" id="editar" name="editar" >
                    </div>
                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="contrasenia" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasenia" readonly name="contrasenia" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-3 col-md-6">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo">
                        </div>
                        <div class="mb-3 col-md-3">
                                <label for="perfil" class="form-label">Perfil</label>
                                <select id="perfil" name="perfil" class="form-select">
                                <option value="">Seleccione</option>
                                <?php foreach ($perfiles->data as $perfil): ?>
                                    <option value="<?php echo $perfil->id_perfil; ?>"><?php echo $perfil->nombre_perfil; ?></option>
                                <?php endforeach; ?>
                                </select>
                        </div>  
                        <div class="mb-3 col-md-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select id="sexo" name="sexo" class="form-select">
                                <option value="">Seleccione</option>
                                <?php foreach ($cat_sexo->data as $sexo): ?>
                                    <option value="<?php echo $sexo->id_sexo; ?>"><?php echo $sexo->dsc_sexo; ?></option>
                                <?php endforeach; ?>
                                </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-3 col-md-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="primer_apellido" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" placeholder="Primer Apellido">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" placeholder="Segundo Apellido">
                        </div>
                    </div>            
                        <div class="mb-3 ">
                            <label for="id_clues" class="form-label">Unidad</label>
                            
                            <select class="form-control select2" data-toggle="select2" id="id_clues" name="id_clues" style="z-index:100;">
                                <option value="">Seleccione..</option>
                                <?php foreach ($unidad->data as $item):?>
                                    <option value="<?=$item->id_clues?>"><?=$item->NOMBRE_UNIDAD?></option>
                                <?php endforeach?>
                            </select>
                           
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





<link href="<?php echo (base_url('/assets/bootstrap-table-master/dist_/bootstrap-table.min.css'));?>" rel="stylesheet">
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/bootstrap-table.min.js');?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/tableExport.min.js');?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/bootstrap-table-locale-all.min.js');?>"></script>
<script src="<?php echo base_url('/assets/bootstrap-table-master/dist_/extensions/export/bootstrap-table-export.min.js');?>"></script>
<script>
    $(document).ready(function() {
        ini.inicio.updateUsuario();
        

        
        $('#id_clues').select2({
            language: {
                noResults: function() {return "No hay resultado";},
                searching: function() {return "Buscando..";}
            },
            dropdownParent: $("#staticBackdrop"),
            // ajax: {
            //     url: <?=base_url("/index.php/Usuario/getUnidades")?>,
            //     dataType: 'json'
            //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            // }
        });
        function headerStyle() {
        return {
            css: {
            background: '#000099', // Fondo del encabezado
            color: '#FFFFFF'        // Color del texto del encabezado
            }
        };
        }
        $('#staticBackdrop').on('hidden.bs.modal', function (e) {
            
            $('#formUsuario')[0].reset();
        });

    });
</script>
</body>
</html>