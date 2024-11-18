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
            <button  class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#staticBackdrop' onclick='ini.opciones.limpiaModal();'>Agregar Usuario</button>
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
        data-url="<?=base_url("/index.php/Opciones/getUsuarios")?>">
        <thead>
            <tr>
                <th data-field="nombre_destinatario" data-width="20" data-sortable="true">Nombre</th>
                <th data-field="cargo" data-width="100" data-sortable="true">Cargo</th>
                <th data-field="dsc_tipo_cargo" data-width="50" data-sortable="true">Descripcion del cargo</th>
                <th data-field="id_usuario" data-width="20" data-sortable="true" data-formatter="ini.opciones.formattAcciones" class="text-center">Acciones</th>
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
            <form id="formDestinatario">
                    <div class="mb-3 ">
                        <input type="hidden" class="form-control" id="id_destinatario" name="id_destinatario" >
                        <input type="hidden" class="form-control" id="editar" name="editar" >
                    </div>
                    
                        <div class="mb-3">
                            <label for="nombre_destinatario" class="form-label">Destinatarios</label>
                            <input type="text" class="form-control" id="nombre_destinatario" name="nombre_destinatario" placeholder="Nombre Destinatario">
                        </div>
                        <div class="mb-3">
                            <label for="cargo" class="form-label">Cargo</label>
                            <input type="text" class="form-control" id="cargo"  name="cargo" placeholder="Cargo">
                        </div>
                        <div class="mb-3">
                            <!-- <label for="dsc_cargo" class="form-label">Descripcion del Cargo</label>
                            cat_tipo_cargo
                            <input type="text" class="form-control" id="dsc_cargo"  name="dsc_cargo" placeholder="Cargo"> -->
                            <label for="dsc_cargo" class="form-label">ESTATUS</label>
                            <select id="dsc_cargo" name="dsc_cargo" class="form-select">
                                <option value="">Seleccione</option>
                                    <?php foreach ($cat_tipo_cargo->data as $item): ?>
                                        <option value="<?php echo $item->id_tipo_cargo; ?>"><?php echo $item->dsc_tipo_cargo; ?></option>
                                    <?php endforeach; ?>
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
        ini.opciones.updateUsuario();
        

        
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
        // $('#staticBackdrop').on('hidden.bs.modal', function (e) {
            
        //     $('#formUsuario')[0].reset();
        // });

    });
</script>
</body>
</html>