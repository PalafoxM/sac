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