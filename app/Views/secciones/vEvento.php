<br>
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
<div class="container mt-5">
    <h2 class="mb-4">CURSOS</h2>
    <input type="hidden" autocomplete="off" id="id_categoria" name="id_categoria" value="<?= $id_categoria ?>">


    <div class="row">
        <div class="col-md-6">
            <div class="mb-6 position-relative">
                <label for="dsc_evento" class="form-label campoObligatorio">NOMBRE DEL EVENTO</label>
                <input type="text" autocomplete="off" class="form-control" name="dsc_evento" id="dsc_evento"
                    placeholder="NOMBRE DEL EVENTO" oninput="this.value = this.value.toUpperCase();">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-6 position-relative">
                <label for="id_sap" class="form-label campoObligatorio">ID SAP</label>
                <input type="number" autocomplete="off" class="form-control" name="id_sap" id="id_sap"
                    placeholder="ID SAP">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-6 position-relative">
                <label for="fec_inicio" class="form-label campoObligatorio">FECHA INICIO</label>
                <input type="date" autocomplete="off" class="form-control" name="fec_inicio" id="fec_inicio">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-6 position-relative">
                <label for="fec_fin" class="form-label campoObligatorio">FECHA FIN</label>
                <input type="date" autocomplete="off" class="form-control" name="fec_fin" id="fec_fin">
            </div>
        </div>

    </div>

    <br />

    <div class="d-flex justify-content-end">
        <button id="saveTable" class="btn btn-success">Guardar</button>
        <button id="load" class="btn btn-success" style="display:none">
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </div>

    <!-- Formulario de subida -->
    <form id="uploadCSVForm" enctype="multipart/form-data">
        <input type="hidden" autocomplete="off" id="id_categoria" name="id_categoria" value="<?= $id_categoria ?>">
        <div class="form-group">
            <label for="csvFile">Seleccionar Archivo CSV:</label>
            <input type="file" name="csvFile" id="csvFile" accept=".csv" required class="form-control">
        </div>

        <button type="button" id="btn_csv" class="btn btn-primary mt-3" onclick="uploadCSV()">Subir y Procesar</button>
        <button id="load_csv" class="btn btn-primary mt-3" style="display:none">
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </form>
    <div class="d-flex justify-content-end">
        <span class="badge bg-success">Con contenido</span>
        <span class="badge bg-warning">Sin contenido</span>
    </div>
    <!-- HTML sin el ID de categoría -->
    <table id="table" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table" data-search="true"
        data-search-highlight="true" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true"
        data-show-refresh="true" data-header-style="headerStyle">
        <thead>
            <tr>
                <th data-field="id" data-width="20" data-sortable="true">ID MOODLE</th>
                <th data-field="fullname" data-width="20" data-sortable="true">NOMBRE DEL CURSO</th>
                <th data-field="idnumber" data-width="20" data-sortable="true">ID SAP</th>
                <th data-field="startdate_legible" data-width="20" data-sortable="true">FECHA INICIO</th>
                <th data-field="enddate_legible" data-width="20" data-sortable="true">FECHA FIN</th>
                <th data-field="summary" data-width="100" data-sortable="true"
                    data-formatter="ini.inicio.configurarCurso" data-tooltip="true">ACCIONES</th>
            </tr>
        </thead>
    </table>




</div>



<script>
function uploadCSV() {
    let formData = new FormData();
    let csvFile = $('#csvFile')[0].files[0];
    formData.append('csvFile', $('#csvFile')[0].files[0]);
    formData.append('id_categoria', $('#id_categoria').val()); // Agrega `id_categoria` a FormData

    if (!csvFile) {
        Swal.fire("Error", "Es requerido el archivo CSV", "error");
        return;
    }

    Swal.fire({
        title: "Atención",
        text: "Se realizar la carga masiva",
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
                url: '<?= base_url('/index.php/Agregar/uploadCSV') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (!response.error) {

                        Swal.fire("Éxito", "Los datos se guardaron correctamente.", "success");
                        window.location.reload();
                    } else {
                        Swal.fire("Error",
                            "Inconsistencia en el archivo, favor de verificar el ID moodle",
                            "error");
                        console.log("Error: " + response); // Error en el procesamiento
                    }
                    $("#btn_csv").show();
                    $("#load_csv").hide();
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
    });



}


document.addEventListener('DOMContentLoaded', function() {
    const saveTableButton = document.getElementById('saveTable');


    // Función para guardar datos de la tabla
    saveTableButton.addEventListener('click', function() {

        const dsc_evento = document.getElementById('dsc_evento').value;
        const fec_inicio = document.getElementById('fec_inicio').value;
        const fec_fin = document.getElementById('fec_fin').value;
        const id_categoria = document.getElementById('id_categoria').value;
        const id_sap = document.getElementById('id_sap').value;
        $("#load").show();
        $("#saveTable").hide();
        $.ajax({
            type: "POST",
            url: `${base_url}index.php/Usuario/saveTableData`,
            dataType: "json",
            data: {
                dsc_evento,
                fec_inicio,
                fec_fin,
                id_categoria,
                id_sap
            },
            success: function(response) {
                if (response.error) {
                    Swal.fire("Error", response.respuesta, "error");
                } else {
                    Swal.fire("Éxito", "Los datos se guardaron correctamente.", "success");
                    window.location.reload();
                }
                $("#load").hide();
                $("#saveTable").show();

            },
            error: function() {
                Swal.fire("Error", "Hubo un problema al guardar los datos.", "error");
            }
        });
    });

});

// Supongamos que `$id_categoria` viene de algún otro lugar en el JavaScript
const idCategoria = $("#id_categoria").val();
console.log(idCategoria);

// Construye la URL dinámicamente
const url = `<?= base_url("/index.php/Agregar/getCoursesByCategoryId/") ?>${idCategoria}`;

// Asigna la URL al atributo `data-url` de la tabla
document.getElementById('table').setAttribute('data-url', url);
</script>