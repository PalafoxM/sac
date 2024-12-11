<?php $session = \Config\Services::session(); ?>

<div class="container mt-2">
    <button type="button" onclick="window.history.back();" class="btn btn-warning mb-2">
        <i class="uil-history-alt me-2"></i> Atrás
    </button>

    <div class="col-md-12 ">
        <div class="card">
            <!--init card -->
            <div class="card-body">
                <blockquote class="blockquote">
                    <h3 class="textoNegro">PREINSCRITOS</h3>
                </blockquote>
                <?php if( $session->get('id_perfil') == 4 || $session->get('id_perfil') == 3 ): ?>
                <div class="row">
                    <div class="col-md-8">

                        <?php if (!empty($enlace) && is_array($enlace)): ?>
                        <select class="select2 form-control select2-multiple" id="nombre" data-toggle="select2"
                            data-placeholder="Buscar Dependencia / Ente / Etc">
                            <?php foreach ($enlace as $e): ?>
                            <option value="<?= htmlspecialchars($e->id_dependencia) ?>">
                                <?= htmlspecialchars($e->dsc_dependencia) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php else: ?>
                        <p>No hay datos disponibles.</p>
                        <?php endif; ?>


                    </div>
                    <div class="col-md-4">
                        <div class="mb-4 position-relative" id="">
                            <button type="submit" class="btn btn-primary" onclick="btnBuscar()"
                                id="btnBuscar">Buscar</button>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>

            <form id="formMatricular" method="post" action="<?= base_url('/index.php/Principal/MatricularCurso') ?>">
                <div class="modal-body">
                    <input type="hidden" id="id_curso" name="id_curso" value=<?= $id_curso ?>>
                    <table id="getParticipantes" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table"
                        data-search="true" data-search-highlight="true" data-pagination="false"
                        data-page-list="[10, 25, 50, 100, all]" data-sortable="true" data-show-refresh="true"
                        data-header-style="headerStyle">
                        <!-- <table id="getParticipantes" class="table table-centered mb-0" data-search="true" > -->
                        <thead>
                            <tr>
                                <?php if( $session->get('id_perfil') == 4 || $session->get('id_perfil') == 3 ):  ?>
                                <th>
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <?php endif ?>
                                <th>CURP</th>
                                <th>NOMBRE</th>
                                <th>CORREO</th>
                                <th>ESTATUS</th>
                                <th>OBSERVACION</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($preinscrito)): ?>
                            <?php foreach ($preinscrito as $participante): ?>
                            <tr>
                                <?php if( $session->get('id_perfil') == 4 || $session->get('id_perfil') == 3 ):  ?>
                                <td>
                                    <input type="checkbox" class="select-participant form-check-input"
                                        name="participantes[]" value="<?= $participante->id_participante; ?>">
                                </td>
                                <?php endif ?>
                                <td><?= htmlspecialchars($participante->curp) ?></td>
                                <td><?= htmlspecialchars($participante->nombre_completo) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($participante->correo) ?>
                                </td>
                                <td>
                                    <?php if($participante->existe === 1):?>
                                    <span class="badge badge-success-lighten">Inscrito</span>
                                    <?php endif ?>
                                    <?php if($participante->existe === 0 || $participante->existe ===3): ?>
                                    <input type="checkbox"
                                        onclick="saeg.principal.cambiosEstatus(<?= $participante->id_participante ?>, this.checked);"
                                        id="switch<?= $participante->id_participante ?>" data-switch="success" <?= ($participante->existe ===3)? 'checked':'' ?> />
                                    <label for="switch<?= $participante->id_participante ?>" data-on-label="Si"
                                        data-off-label="No"></label>
                                    <?php endif; ?>

                                    <?php if($participante->existe == ''):?>
                                    <span class="badge badge-warning-lighten">Pendiente</span>
                                    <?php endif ?>


                                </td>
                                <td><a href="<?= base_url('/index.php/Principal/Participantes') ?>">

                                        <?php if($participante->existe === 1):?>
                                        <span
                                            class="badge badge-success-lighten"><?= $participante->observaciones ?></span>
                                        <?php endif ?>
                                        <?php if($participante->existe === 0 || $participante->existe ===3):?>
                                        <span
                                            class="badge badge-danger-lighten"><?= $participante->observaciones ?></span>
                                        <?php endif ?>
                                        <?php if($participante->existe == ''):?>
                                        <span class="badge badge-warning-lighten">Pendiente</span>
                                        <?php endif ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <?php if( $session->get('id_perfil') == 1 || $session->get('id_perfil') == 3 ||  $session->get('id_perfil') == 4): ?>
                <div class="modal-footer" id="btn_save">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="matricularButton">Guardar</button>
                </div>
                <div class="modal-footer" id="btn_load" style="display:none;">
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                        Guardando ...
                    </button>
                </div>
                <?php endif ?>
            </form>
        </div>
        <!--end card -->
    </div>

</div>

<script>
$(document).ready(function() {
    // Selección masiva
    let selectedParticipants = {};
    $('#select-all').on('click', function() {
        // Para almacenar el estado de selección
        const checked = this.checked;
        $('.select-participant').each(function() {
            $(this).prop('checked', checked);
            selectedParticipants[$(this).val()] = checked; // Almacena el estado en el objeto
        });
        // $('.select-participant').prop('checked', this.checked);
    });

    $(document).on('change', '.select-participant', function() {
        const participantId = $(this).val();
        selectedParticipants[participantId] = this.checked; // Actualiza el estado en el objeto
    });

    // Al cambiar de página, sincroniza la selección visual
    $('#getParticipantes').on('page-change.bs.table', function() {
        // Vuelve a seleccionar los participantes seleccionados en la nueva página
        $('.select-participant').each(function() {
            const participantId = $(this).val();
            $(this).prop('checked', selectedParticipants[participantId] ||
                false); // Verifica el estado en el objeto
        });
    });

    // Enviar IDs seleccionados al formulario
    $('#formMatricular').on('submit', function(e) {
        e.preventDefault();

        let id_curso = $('#id_curso').val();
        const selectedIds = $('.select-participant:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            Swal.fire("Advertencia", "Debe seleccionar al menos un participante.", "warning");
            return;
        }
        $("#btn_load").show();
        $("#btn_save").hide();

        // Enviar el formulario con los IDs seleccionados
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {
                participantes: selectedIds,
                id_curso: id_curso
            },
            success: function(response) {
                console.log(response)
                if (!response.error) {
                    Swal.fire("Éxito", "Participantes matriculados con éxito.", "success");
                    $('#modalMatricular').modal('hide');
                    $("#btn_load").hide();
                    $("#btn_save").show();
                    <?php if( $session->get('id_perfil') == 3 || $session->get('id_perfil') == 4 ): ?>
                    window.location.href = base_url +
                        "index.php/Principal/cursoMatriculados/" + id_curso;
                    <?php endif ?>
                    <?php if( $session->get('id_perfil') >= 5 ): ?>
                    window.location.href = base_url + "index.php/Principal/Preinscritos/" +
                        id_curso;
                    <?php endif ?>


                }

                // Actualizar la tabla principal si es necesario
                //$('#table').bootstrapTable('refresh');
            },
            error: function(xhr, status, error) {
                Swal.fire("Error", "Hubo un problema al matricular los participantes.",
                    "error");
            }
        });
    });
});

function btnBuscar() {
    const idDependencia = $('#nombre').val(); // Obtener el valor seleccionado en el select

    if (!idDependencia) {
        Swal.fire("Advertencia", "Debe seleccionar una dependencia.", "warning");
        return;
    }

    $.ajax({
        url: '<?= base_url('/index.php/Principal/getPreinscritos') ?>', // Ruta a tu controlador/método que devuelve los datos
        type: 'GET',
        data: {
            id_dependencia: idDependencia
        },
        success: function(response) {
            if (response.data && response.data.length > 0) {
                const participantes = response.data;

                // Vaciar el tbody actual
                $('#getParticipantes tbody').empty();

                // Llenar la tabla con los nuevos datos
                participantes.forEach(participante => {
                    const existeLabel = participante.existe === 1 ?
                        '<span class="badge badge-success-lighten">Inscrito</span>' :
                        participante.existe === 0 ?
                        '<span class="badge badge-danger-lighten">No Inscrito</span>' :
                        '<span class="badge badge-warning-lighten">Pendiente</span>';
                    const observacionesBadge = participante.existe == 1 ?
                        `<span class="badge badge-success-lighten">${participante.observaciones}</span>` :
                        participante.existe == 0 ?
                        `<span class="badge badge-danger-lighten">${participante.observaciones}</span>` :
                        `<span class="badge badge-warning-lighten">Sin observaciones</span>`;


                    const row = `
                        <tr>
                            <?php if( $session->get('id_perfil') == 4 || $session->get('id_perfil') == 3 ): ?>
                            <td><input type="checkbox" class="select-participant form-check-input" name="participantes[]" value="${participante.id_participante}"></td>
                            <?php endif; ?>
                            <td>${participante.curp}</td>
                            <td>${participante.nombre_completo}</td>
                            <td>${participante.correo}</td>
                            <td><a href="<?= base_url('/index.php/Principal/Participantes') ?>">${existeLabel}</a></td>
                           <td>${observacionesBadge}</td>

                        </tr>`;
                    $('#getParticipantes tbody').append(row);
                });

                // Sincronizar estado de selección si es necesario
            } else {
                Swal.fire("Advertencia", "No se encontraron participantes para esta dependencia.", "info");
                $('#getParticipantes tbody').empty(); // Limpiar tabla si no hay datos
            }
        },
        error: function(xhr, status, error) {
            Swal.fire("Error", "Hubo un problema al buscar los participantes.", "error");
            console.error(error);
        }
    });
}
</script>