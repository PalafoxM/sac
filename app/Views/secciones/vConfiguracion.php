<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <!--  -->
            </div>
            <h3> CATEGORIA: <?php echo (isset($categoria[0]->categoryname))?$categoria[0]->categoryname:'' ?> </h3>
            <h6> NOMBRE CORTO: <?php echo (isset($details[0]->shortname))?$details[0]->shortname:'' ?></h6>
        </div>
    </div>
</div>
<!-- end page title -->
<div class=" mt-3">

    <div class="row">
        <input type="hidden" id="dsc_curso" checked data-switch="bool" name="dsc_curso"
            value="<?php echo (isset($eventos->dsc_curso))?$eventos->dsc_curso:'' ?>" />
        <input type="hidden" id="dsc_curso_corto" checked data-switch="bool" name="dsc_curso_corto"
            value="<?php echo (isset($eventos->dsc_curso_corto))?$eventos->dsc_curso_corto:''?>" />
        <input type="hidden" id="id_curso" checked data-switch="bool" name="id_curso"
            value="<?php echo (isset($id_curso))?$id_curso:''?>" />
        <!-- seccion izquierdo incio -->
        <div class="col-md-12 ">
            <div class="card">
                <!--init card -->
                <div class="card-body">
                    <blockquote class="blockquote">
                        <h3 class="textoNegro">
                            <?php if(isset($details[0]->fullname)){ echo $details[0]->fullname; } ?></h3>
                    </blockquote>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="visible" class="form-label campoObligatorio">VISIBILIDAD DEL CURSO
                            </label>
                            <?php if(isset($details[0]->visible) && $details[0]->visible == 1): ?>
                            <input type="checkbox" id="visible" disabled checked data-switch="success" name="visible" />
                            <label for="visible" data-on-label="visible" data-off-label="Off"></label>
                            <?php endif ?>
                            <?php if(isset($details[0]->visible) && $details[0]->visible == 0): ?>
                            <input type="checkbox" id="cerrado" disabled checked data-switch="warning" name="cerrado" />
                            <label for="visible" data-on-label="cerrado" data-off-label="cerrado"></label>
                            <?php endif ?>
                        </div>

                    </div>
                    <?php $fec_ini = (isset($fec_inicio))?date("Y-m-d", strtotime($fec_inicio)):''; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="fec_inicio" class="form-label campoObligatorio">FECHA DE INICIO DEL
                                    CURSO</label>
                                <input type="date" class="form-control" id="fec_inicio" name="fec_inicio"
                                    value="<?php echo $fec_ini ?>">
                            </div>
                        </div>
                        <?php $fec_f = (isset($fec_fin))?date("Y-m-d", strtotime($fec_fin)):''; ?>

                        <div class="col-md-6">
                            <div class="mb-6 position-relative">
                                <label for="fec_fin" class="form-label campoObligatorio">FECHA DE TERMINACION
                                    DEL
                                    CURSO</label>
                                <input type="date" autocomplete="off" class="form-control" id="fec_fin" name="fec_fin"
                                    value="<?php echo $fec_f; ?>">
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-6 position-relative" id="">
                                <label for="numero_id_curso" class="form-label campoObligatorio">NUMERO ID DEL
                                    CURSO</label>
                                <input type="number" autocomplete="off" class="form-control" id="numero_id_curso"
                                    name="numero_id_curso"
                                    value="<?php echo (isset($eventos->numero_id_curso))?$eventos->numero_id_curso:''; ?>">
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="d-flex justify-content-start">
                        <button id="btn_fecha" class="btn btn-primary">Copiar fechas</button>
                    </div>
                </div>
            </div>
            <!--end card -->
        </div>
        <!-- seccion izquierdo fin-->
        <!-- seccion derecha incio -->
    </div>
</div>


<div class=" mt-3">

    <div class="row">
        <div class="card-body">
            <?php if(isset($quizz) && !empty($quizz)): ?>
            <table class="table table-centered mb-0">
                <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>TIEMPO INICIO</th>
                        <th>TIEMPO FIN</th>
                        <th>TIEMPO LIMITE</th>
                        <th>EDITAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($quizz as $q): ?>

                    <tr>
                        <td><?= htmlspecialchars($q->name) ?></td>
                        <td>
                            <input type="date" autocomplete="off" class="form-control" id="timeopen<?= $i ?>"
                                name="timeopen<?= $i ?>" value="<?= date("Y-m-d", $q->timeopen) ?>" readonly>
                            <input type="hidden" name="id_curso<?= $i ?>" id="id_curso<?= $i ?>" value="<?= $q->id ?>">
                        </td>
                        <td>
                            <input type="date" autocomplete="off" class="form-control" id="timeclose<?= $i ?>"
                                name="timeclose<?= $i ?>" value="<?= date("Y-m-d", $q->timeclose) ?>" readonly>
                            <!-- Solo `YYYY-MM-DD` -->
                        </td>
                        <td><?= gmdate("H:i:s", $q->timelimit) ?></td>
                        <!-- Convierte `timelimit` en horas:minutos:segundos -->
                        <td>
                            <!-- Switch -->
                            <div>
                                <input type="checkbox" id="switch_<?= $i ?>" data-switch="success"
                                    onclick="activar_fecha(<?=$i?>)" />
                                <label for="switch_<?= $i ?>" data-on-label="Sí" data-off-label="No"
                                    class="mb-0 d-block"></label>
                            </div>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>


            </table>
            <?php endif ?>


        </div>
    </div>


</div>
<div class="row">
    <div class="d-flex justify-content-end">
        <button type="submit" id="btn_guardar_conf" class="btn btn-primary">Guardar Configuración</button>
        <button id="btn_guardar_load" style="display:none" class="btn btn-primary">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </div>
</div>



<script>
$(document).ready(function() {

    st.agregar.copiar_fecha();
    st.agregar.formConfigurarCurso();

});

function activar_fecha(i) {
    document.getElementById(`timeopen${i}`).readOnly = false;
    document.getElementById(`timeclose${i}`).readOnly = false;
}
</script>