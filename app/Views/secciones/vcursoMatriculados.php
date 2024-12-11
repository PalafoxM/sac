<?php  $session = \Config\Services::session() ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <button type="button" onclick="window.history.back();" class="btn btn-warning mb-2">
                            <i class="uil-history-alt me-2"></i> Atrás
                        </button>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">

                        </div>
                    </div><!-- end col-->
                </div>


                <div class="tab-content">
                    <div class="tab-pane show active" id="home1">
                        <div class="table-responsive">
                            <table id="getParticipantes" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table"
                                data-search="true" data-search-highlight="true" data-pagination="true"
                                data-page-list="[10, 25, 50, 100, all]" data-sortable="true" data-show-refresh="true"
                                data-header-style="headerStyle">
                                <thead>
                                    <tr>

                                        <th>CURP</th>
                                        <th>NOMBRE</th>
                                        <th>CORREO</th>
                                        <th>ESTATUS</th>
                                        <?php if((int)$session->get('id_perfil') == 4 || (int)$session->get('id_perfil') == 5): ?>
                                        <th>ACCION</th><?php endif ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($participantes as $participante): ?>
                                    <tr>

                                        <td><?= htmlspecialchars($participante->curp) ?></td>
                                        <td><?= htmlspecialchars($participante->nombre_completo) ?></td>
                                        <td><?= htmlspecialchars($participante->correo) ?></td>
                                        <td><?= ($participante->existe)? '<div id="tooltip-container1">
                                                                                <span class="d-inline-block" tabindex="0" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" title="Activo">
                                                                                    <span class="badge bg-success" style="pointer-events: none;">Inscrito</span>
                                                                                </span>
                                                                            </div>': '<div id="tooltip-container2">
                                                                                <span class="d-inline-block" tabindex="0" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" title="correo ya existe o incorrecto">
                                                                                    <span class="badge bg-danger" style="pointer-events: none;">Sin Inscribir</span>
                                                                                </span>
                                                                            </div>' ?></td>
                                        <?php if((int)$session->get('id_perfil') == 4 || (int)$session->get('id_perfil') == 5): ?>
                                        <td>
                                            <center><a style="cursor: pointer;"
                                                    onclick="eliminar(<?= $participante->userid ?>, <?= $participante->id_curso ?>, <?= $participante->id_participante ?>)"><i
                                                        class="dripicons-trash"></i></a></center>
                                        </td>
                                        <?php endif ?>

                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>

<script>
function eliminar(userid, courseid, id_participante) {
    Swal.fire({
        title: "Atención",
        text: "Esta operación eliminara el usuario en Moodle y SAC",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Proceder"
    }).then((result) => {
        if (result.isConfirmed) {
            $("#btn_csv").hide();
            $("#load_csv").show();
            $.ajax({
                url: base_url + "index.php/Principal/deleteUserid",
                type: 'POST',
                data: {
                    userid: userid,
                    courseid: courseid,
                    id_participante: id_participante
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {

                        Swal.fire("Éxito", "Los datos se guardaron correctamente.", "success");
                        //$('#getParticipantes').bootstrapTable('refresh');
                        window.location.reload();
                    }

                },
                error: function(xhr, status, error) {
                    console.log(error);
                    Swal.fire("Error", "Favor de llamar al Administrador", "error")
                    $("#btn_csv").show();
                    $("#load_csv").hide();
                    //alert("Error en la solicitud: " + error);
                }
            });
        }
    })
}
</script>