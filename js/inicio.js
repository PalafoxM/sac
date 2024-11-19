var ini = window.ssa || {};

ini.inicio = (function () {
    return {
        
        abrirVentanaPdf: function(idTurno) {
            var pdfUrl = base_url + "index.php/Inicio/pdfTurno?id_turno=" + idTurno;
            var opcionesVentana = 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=800';
            window.open(pdfUrl, '_blank', opcionesVentana);
        },
        obtenerNombreMes: function(indiceMes) {
            var meses = [
              "enero", "febrero", "marzo", "abril", "mayo", "junio",
              "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
            ];
            return meses[indiceMes];
          },
        calculaFecha: function(valor,dias){
            var fechaReferencia = new Date(valor); 
            var fechaActual = new Date();
            var diferenciaMilisegundos = fechaActual - fechaReferencia;
            var diferenciaDias = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24));
            var diasParaVerificar = dias;
            if (diferenciaDias >= diasParaVerificar) {
                return true;
            } else {
                return false;
            }
        },
        
        formatterAccionesTurno: function(value,row){
            let accion = "<div class='contenedor'>"+
                "<button type='button' onclick='ini.inicio.abrirVentanaPdf("+ row.id_turno+")' class='btn btn-secondary' title='Mostrar'><i class='mdi mdi-file-pdf'></i> </button>"+
                "<button type='button'  class='btn btn-warning' title='Modificar' style='margin-left:5px'><i class='mdi mdi-lead-pencil'></i> </button>"+
                "</div>";
            return accion;
        },
        formatterTruncaTexto:function(value, row) {
            if(value === null) return "";
            var maxLength = 30;
            var truncatedValue = value.length > maxLength ? value.substring(0, maxLength) + '...' : value;
            return '<span data-toggle="tooltip" title="' + value + '">' + truncatedValue + '</span>';
        },
        formatteStatusResultadoTurno:function(value,row){
            if (value === '1') {
                return '<span  title="CON RESULTADO">CON RESULTADO</span>';
            }else if (value ==='2'){
                return '<span  title="SIN RESULTADO">SIN RESULTADO</span>';
            }else if (value ==='3'){
                return '<span  title="AMBOS">AMBOS</span>';
            }else{
                return '<span  title="SIN RESULTADO">SIN RESULTADO</span>';
            }
        },
        agregarCategoria: function(){
         
            $("#formAgregarCurso").submit(function (e) {
                e.preventDefault(); 
                var formData = $("#formAgregarCurso").serialize();
                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaCategoria",
                    data:formData,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.respuesta.error){
                            Swal.fire("error", "Solicite apoyo al area de sistemas","error" );
                        }
                        Swal.fire("success", "Se guardo con exito", "success");
                        $("#formAgregarCurso")[0].reset();
                        $('#categoryTree').jstree(true).refresh();
                        //window.location.href = base_url + "index.php/Agregar/Curso";
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
            });
        },
            
        formatteStatus: function(value, row){
            // TODO lo se es una mala practica hacer esto pero en este caso me es de mucha ayuda I'm sorry
            // opcion 1  
            // if(value ==1){
            //     let clase = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? '#fa5c7c' : (ini.inicio.calculaFecha(row.fecha_recepcion, 5)) ? '#f9bc0d': '#47d420';
            //     let titulo = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? 'Vencido' :ini.inicio.calculaFecha(row.fecha_recepcion, 5) ? 'Por vencer':'En proceso';
            //     return `<button type="button" class="btn" style="background:${clase}; color:#1D438A;" data-toggle="tooltip" title="${titulo}">En proceso </button>`;
            // }
            // if(value ==2){
            //     return '<button type="button" class="btn" style="background:#baddfd;color:#1D438A;" data-toggle="tooltip" title="Resuelta">Resuelta</button>';
            // }
            // opcion 2  
            if (value === '1') {
                let opciones = {
                    10: { clase: '#fa5c7c', titulo: 'Vencido' },
                    5: { clase: '#f9bc0d', titulo: 'Por vencer' },
                    default: { clase: '#47d420', titulo: 'En proceso' }
                };
                let key = ini.inicio.calculaFecha(row.fecha_recepcion, 10) ? 10 : ini.inicio.calculaFecha(row.fecha_recepcion, 5) ? 5 : 'default';
                let { clase, titulo } = opciones[key];
                return `<button type="button" class="btn" style="background:${clase}; color:#1D438A;" data-toggle="tooltip" title="${titulo}">${titulo}</button>`;
            }
            if (value === '2') {
                return '<button type="button" class="btn" style="background:#baddfd;color:#1D438A;" data-toggle="tooltip" title="Resuelta">Resuelta</button>';
            }     
        },
        formattFechaRecepcion: function(value,row){
           
            var fechaOriginalString = value;
            var fechaOriginal = new Date(fechaOriginalString);
            fechaOriginal.setMinutes(fechaOriginal.getTimezoneOffset());
            var dia = fechaOriginal.getDate();
            var mes = ini.inicio.obtenerNombreMes(fechaOriginal.getMonth()); // Sumar 1 al índice del mes
            var año = fechaOriginal.getFullYear();
            var nuevoFormato = dia + " de " + mes + " de " + año;
            return '<strong>' + nuevoFormato + '</strong>';
        },
        formattAcciones: function(value,row){
            let Botones = "<div class='contenedor'>" +
            "<button type='button' class='btn btn-danger' title='Remover' id='remover' onclick='ini.inicio.deleteUsuario(" + row.id_usuario + ")'><i class='mdi mdi-account-off'></i></button>" +
            "<button type='button' title='Editar' data-bs-toggle='modal' data-bs-target='#staticBackdrop2' class='btn btn-warning' onclick='ini.inicio.getUsuario(" + row.id_usuario + ")'><i class='mdi mdi-account-edit'></i></button>" +
            "</div>";
           return Botones;
        },
        accionesCategorias: function(value,row){
            let Botones = "<div class='contenedor'>" +
            "<button type='button' class='btn btn-danger' title='Remover' id='remover' onclick='ini.inicio.deleteCurso(" + row.id_categoria + ")'><i class='dripicons-trash'></i></button>" +
            "<button type='button' title='Editar' data-bs-toggle='modal' data-bs-target='#staticBack' class='btn btn-warning' onclick='ini.inicio.getCurso(" + row.id_categoria + ")'><i class='dripicons-pencil'></i></button>" +
            "<button type='button' title='agregar evento' data-bs-toggle='modal'  class='btn btn-success' onclick='ini.inicio.agregarEvento(" + row.id_categoria + ")'><i class='dripicons-network-3'></i></button>" +
            "<button type='button' title='ver' data-bs-toggle='modal'  class='btn btn-primary' onclick='ini.inicio.verCurso(" + row.id_categoria + ")'><i class='dripicons-preview'></i></button>" +
            "</div>";
           return Botones;
        },
        agregarEvento: function(id){
            
            window.location.href = `${base_url}index.php/Agregar/Evento/${id}`;
        },
        configurarCurso: function(value,row){
          
            if(row.summary != ""){
                return "<button type='button' title='Curso listo para configurar' data-bs-toggle='modal'  class='btn btn-success' onclick='ini.inicio.configurar(" + row.id + ")'><i class='dripicons-gear'></i></button>";
            }else{
                return "<button type='button' title='Curso sin contenido' data-bs-toggle='modal'  class='btn btn-warning' onclick='ini.inicio.configurar(" + row.id + ")'><i class='dripicons-gear'></i></button>";
            }
            
        },
        configurar: function (eventoId) {
            fetch(`${base_url}index.php/Agregar/encryptId?evento_id=${eventoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.encryptedId) {
                        // Redirige con el ID encriptado
                        window.location.href = `${base_url}index.php/Agregar/Configuracion?evento_id=${data.encryptedId}`;
                    } else {
                        //console.error("Error en la encriptación del ID");
                        window.location.href = `${base_url}index.php/Agregar/Configuracion?evento_id=${eventoId}`;
                    }
                })
                .catch(error => console.error("Error en la solicitud de encriptación:", error));
        },
        verCurso: function(id){
            
            window.location.href = `${base_url}index.php/Agregar/showCurso/${id}`;
        },
        getCurso: function(id){
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getCurso",
                dataType: "json",
                data:{id_categoria:id},
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#labelCurso').text('Editar Categoría');
                        $('#id_moodle_categoria').val(data.id_moodle_categoria);
                        $('#dsc_categoria').val(data.dsc_categoria);
                        $('#fec_reg').val(data.fec_reg);
                      

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
        getUsuario: function(id){
            
            $.ajax({
                type: "POST",
                url: base_url + "index.php/Usuario/getUsuario",
                dataType: "json",
                data:{id_usuario:id},
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#staticBackdropLabel2').text('Editar Registro');
                        $('#id_usuario').val(data.id_usuario);
                        $('#nombre').val(data.nombre);
                        $('#primer_apellido').val(data.primer_apellido);
                        $('#segundo_apellido').val(data.segundo_apellido);
                        $('#correo').val(data.correo);
                        $('#rfc').val(data.rfc);
                        $('#curp').val(data.curp);
                        $('#area').val(data.area);
                        $('#jefe_inmediato').val(data.jefe_inmediato);
                        $('#denominacion_funcional').val(data.denominacion_funcional);
                        $('#id_sexo').val(data.id_sexo);
                        $('#id_nivel').val(data.id_nivel).trigger('change');
                        $('#id_dependencia').val(data.id_dependencia).trigger('change');
                        $('#id_perfil').val(data.id_perfil).trigger('change');
                        $('#fec_nac').val(data.fec_nac);
                        $('#usuario').val(data.usuario);

                    } else {
                        Swal.fire("info", "No se encontraron datos del usuario.", "info");
                    }
                },
                error: function() {
                    Swal.fire("info", "No se encontraron datos del usuario.", "info");
                }
            });
        },
      
        updateUsuario: function(){
                $('#formUsuario2').submit(function(event) {
                    event.preventDefault();

                    var formData = $(this).serialize();
                    console.log(formData);   
                    $.ajax({
                        url: base_url + "index.php/Usuario/UpdateUsuario",
                        type: "post",
                        dataType: "json",
                        data: formData,
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#btnGuardar').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#btnGuardar').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro exitoso", "success");
                            window.location.href = `${base_url}index.php/Usuario`;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                });
        },
        formEditCurso: function(){
                $('#formEditCurso').submit(function(event) {
                    event.preventDefault();

                    var formData = $(this).serialize();
                    console.log(formData);   
                    $.ajax({
                        url: base_url + "index.php/Usuario/UpdateCurso",
                        type: "post",
                        dataType: "json",
                        data: formData,
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#btnGuardar').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#btnGuardar').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro exitoso", "success");
                            window.location.href = `${base_url}index.php/Agregar/Curso`;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                });
        },
        deleteUsuario: function(id){
            // TODO preguntar si desea borrar o no con un swal 

            Swal.fire({
                title: "Estas Seguro?",
                text: "No podras revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar",
                cancelButtonText: "Cancelar"
              }).then((result) => {
                if (result.isConfirmed) {
                  
                    console.log(id);
                    $.ajax({
                        url: base_url + "index.php/Usuario/deleteUsuario",
                        type: "post",
                        dataType: "json",
                        data: {'id_usuario':id},
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#remover').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#remover').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro eliminado con exito", "success");
                            //window.location.href = `${base_url}index.php/Usuario`;
                            $('#table').bootstrapTable('refresh');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                }
              });



            
        },
        deleteCurso: function(id){
            // TODO preguntar si desea borrar o no con un swal 

            Swal.fire({
                title: "Estas Seguro?",
                text: "No podras revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar",
                cancelButtonText: "Cerrar"
              }).then((result) => {
                if (result.isConfirmed) {
                  
                    console.log(id);
                    $.ajax({
                        url: base_url + "index.php/Usuario/deleteCurso",
                        type: "post",
                        dataType: "json",
                        data: {'id_curso':id},
                        beforeSend: function () {
                            // element.disabled = true;
                            $('#remover').prop('disabled', true);
                        },
                        complete: function () {
                            // element.disabled = false;
                            $('#remover').prop('disabled', false);
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.error) {
                                Swal.fire("Atención", response.respuesta, "warning");
                                return false;
                            }
                            Swal.fire("Correcto", "Registro eliminado con exito", "success");
                            window.location.href = `${base_url}index.php/Agregar/Curso`;
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error(s):" + jqXHR);
                        },
                    });

                }
              });



            
        },
        limpiaModal:function(){
            $('#formUsuario')[0].reset();
            $('#id_clues').val('').change();
            $('#staticBackdropLabel').text('Agregar Usuario');
            $('#id_usuario').prop('disabled', true);
            $('#editar').prop('disabled', false);
            $('#editar').val(1);
            $("#contrasenia").prop("readonly", false);
        },


        
        
    }
})();