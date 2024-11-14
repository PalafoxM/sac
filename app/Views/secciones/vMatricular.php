<div class="container mt-5">
    <h2 class="mb-4">MATRICULAR PARTICIPANTES</h2>

    <table id="table" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table" data-search="true"
        data-search-highlight="true" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
        data-show-refresh="true" data-header-style="headerStyle" data-show-export="true" data-export-types="['excel']">
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>FECHA INICIO</th>
                <th>FECHA FIN</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso): ?>
            <tr>
                <td><?= htmlspecialchars($curso['id']) ?></td>
                <td><?= htmlspecialchars($curso['fullname']) ?></td>
                <td><?= htmlspecialchars($curso['startdate']) ?></td>
                <td><?= htmlspecialchars($curso['enddate']) ?></td>
                <td>
                    <button type="button" class="btn btn-secondary rounded-pill"
                        onclick="matricular(<?= $curso['id'] ?>)">Matricular</button>
                    <a href="<?= base_url('index.php/Principal/cursoMatriculados/'.$curso['id']) ?>" type="button"
                        class="btn btn-light  rounded-pill">Matriculados</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



    <!-- Modal para Matricular -->
    <div id="modalMatricular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fullWidthModalLabel">Matricular</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="formMatricular" method="post"
                    action="<?= base_url('/index.php/Principal/MatricularCurso') ?>">
                    <div class="modal-body">
                        <input type="hidden" id="id_curso" name="id_curso">
                      <table id="getParticipantes" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table"
                            data-search="true" data-search-highlight="true" data-pagination="false"
                            data-page-list="[10, 25, 50, 100, all]" data-sortable="true" data-show-refresh="true"
                            data-header-style="headerStyle">
                        <!-- <table id="getParticipantes" class="table table-centered mb-0" data-search="true" > -->
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>CURP</th>
                                    <th>NOMBRE</th>
                                    <th>CORREO</th>
                                    <th>RFC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($participantes->data)): ?>
                                <?php foreach ($participantes as $participante): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="select-participant form-check-input"
                                            name="participantes[]" value="<?= $participante->id_participante; ?>">
                                    </td>
                                    <td><?= htmlspecialchars($participante->curp) ?></td>
                                    <td><?= htmlspecialchars($participante->nombre_completo) ?></td>
                                    <td><?= htmlspecialchars($participante->correo) ?></td>
                                    <td><?= htmlspecialchars($participante->rfc) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" id="btn_save">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="matricularButton">Matricular</button>
                    </div>
                    <div class="modal-footer" id="btn_load" style="display:none;">
                        <button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                             Matriculando ...
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                    window.location.href = base_url +
                        "index.php/Principal/cursoMatriculados/" + id_curso;
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

function matricular(id) {
    $('#modalMatricular').modal('show');
    $('#id_curso').val(id);
}
</script>