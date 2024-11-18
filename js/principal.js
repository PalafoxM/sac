var saeg = window.ssa || {};

saeg.principal = (function () {
    return {
        cargar_documento: function () {
            $("#frmDocumento").submit(function (event) {
                //disable the default form submission                
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: base_url + '/index.php/Principal/SubiendoDocumento',
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //data: $("#frmAsuntoEntradaNuevo").serialize(),
                    success: function (response, textStatus, jqXHR) {
                        //console.log(response);
                        if(response == 'correcto'){
                            Swal.fire("", "Se agregó correctamente el logotipo", "success");
                            location.reload();
                        }else{
                            Swal.fire("Error", response, "warning");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error');
                        console.log('error:' + textStatus, errorThrown);
                    }
                });
                event.preventDefault();
                event.stopImmediatePropagation();
            });
        },
        observacionesFormatter: function(value, row, index) {
            // Aquí puedes personalizar el color y estilo según el valor de `value`
            if (value) {
                return `<span class="badge badge-danger-lighten">${value}</span>`;
            } else {
                return `<span class="badge badge-success-lighten">Sin observaciones</span>`;
            }
        },
     /*    formattCheck: function(value, row, index) {
            return `
                <div class="form-check form-checkbox-success mb-2">
                    <input type="checkbox" class="form-check-input select-participant" value="${row.id_participante}" data-id="${row.id_participante}">
                    <label class="form-check-label">Seleccionar</label>
                </div>`;
        }, */
        
        formattParticipantes: function(value, row){
          
                let accion = "<div class='contenedor'>"+
                "<a  data-bs-toggle='modal' data-bs-target='#modalAltaParticipante' onclick='saeg.principal.updateParticipante("+row.id_participante+")' href='javascript:void(0);' class='action-icon'><i class='mdi mdi-square-edit-outline'></i> </a>"+
                "<a onclick='saeg.principal.eliminarParticipante("+row.id_participante+")' href='javascript:void(0);' class='action-icon'><i class='mdi mdi-delete'></i></a>"+
                "</div>";
        
           
        return accion;
        },
        formattDetenido: function(value, row){
            let accion = "";
            if(row.observaciones == 'Curp ya existe en la base de datos'){
                accion = "<div class='contenedor'>"+
                "<a onclick='saeg.principal.eliminarDetenido("+row.id_detenido+")' href='javascript:void(0);' class='action-icon'><i class='mdi mdi-delete'></i></a>"+
                "</div>";
            }else{
                accion = "<div class='contenedor'>"+
                "<a  data-bs-toggle='modal' data-bs-target='#modalAltaParticipante' onclick='saeg.principal.updateDetenido("+row.id_detenido+")' href='javascript:void(0);' class='action-icon'><i class='mdi mdi-square-edit-outline'></i> </a>"+
                "<a onclick='saeg.principal.eliminarDetenido("+row.id_detenido+")' href='javascript:void(0);' class='action-icon'><i class='mdi mdi-delete'></i></a>"+
                "</div>";
            }
           
        return accion;
        },
        login: function(){
            $("#login").submit(function (e) {
                e.preventDefault();                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Login/validar_usuario",
                    data: $(this).serialize(),
                    dataType: "html",
                    success: function (response) {
                        console.log(response);
                        if(response == 'correcto'){
                            Swal.fire("Bienvenido!", "ingresando...", "success");
                            window.location.href = base_url + "index.php/Inicio";                           
                        }else{
                            Swal.fire("Usuario incorrecto!", "Favor de verificar sus credenciales de acceso", "error");                            
                            return false;
                        } 
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire("Error!", textStatus, errorThrown, "error");  
                        console.log('error:' + textStatus, errorThrown);
                    }
                });
            });
        },
        formParticipante: function(){
            $("#formParticipante").submit(function (e) {
                e.preventDefault();                
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Principal/guardarParticipantes",
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        console.log(response.error);
                        console.log(response.respuesta);
                        if(response.error == false){
                            Swal.fire("Exitó", response.respuesta, "success");
                            $('#formParticipante')[0].reset();
                            $('#tableDetenidos').bootstrapTable('refresh');
                            $('#getParticipantes').bootstrapTable('refresh');
                            $('#modalAltaParticipante').modal('hide');
                                                    
                        }else{
                            Swal.fire("Error", response.respuesta , "error"); 
                            //$("#formParticipante")[0].reset();                         
                            return false;
                        } 
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse (response.responseText);
                       //  console.log(res.message);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                   }
                });
            });
        },
        uploadCSVP: function(){
                let formData = new FormData();
                let csvFile = $('#fileParticipantes')[0].files[0];
                formData.append('fileParticipantes', $('#fileParticipantes')[0].files[0]);
            
                if (!csvFile) {
                    Swal.fire("Error", "Es requerido el archivo CSV", "error");
                    return;
                }
            
                Swal.fire({
                    title: "Atención",
                    text: "Esta operación puede regresar información, que no sea correcta",
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
                            url: base_url + "index.php/Principal/uploadCSV",
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (!response.error) {
            
                                    Swal.fire("Éxito", "Los datos se guardaron correctamente.", "success");
                                    $('#tableDetenidos').bootstrapTable('refresh');
                                    $('#getParticipantes').bootstrapTable('refresh');
                                    $('#standard-modal').modal('hide');
                                    $('#uploadCSVParticipantes')[0].reset();
                                   // window.location.reload();
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: response.respuesta,
                                        icon: "error",
                                        confirmButtonText: "Descargar archivo de ejemplo",
                                        showCancelButton: true,
                                        cancelButtonText: "Cerrar"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Redirige a la URL del archivo de ejemplo
                                            window.location.href =  base_url+'ejemplo.csv';
                                        }
                                    });
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
          
        },
        eliminarParticipante : function(id){
            console.log(id);
            Swal.fire({
                title: "Atención",
                text: "El usuario se eliminara de los Usuarios Listos, más no de los detenidos",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + "index.php/Principal/eliminarDetenido",
                        type: 'POST',
                        dataType: "json",
                        data:{id_participante:id},
                        success: function(response) {
                            console.log( response);
                            if(response.error){
                                Swal.fire("Error",response.respuesta , "error")
                            }else{
                                Swal.fire("Éxito",response.respuesta , "success");
                                $('#getParticipantes').bootstrapTable('refresh');
                                //window.location.reload();
                                //$('#products-datatable').DataTable().ajax.reload(null, false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.fire("Error", "Favor de llamar al Administrador", "error")
                        }
                    });
                }
            });
           
        },
        eliminarDetenido : function(id){
            console.log(id);
            Swal.fire({
                title: "Atención",
                text: "El usuario se eliminara de la tabla de detenidos, más no de los Usuarios Listos",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + "index.php/Principal/eliminarDetenido",
                        type: 'POST',
                        dataType: "json",
                        data:{id_detenido:id},
                        success: function(response) {
                            console.log( response);
                            if(response.error){
                                Swal.fire("Error",response.respuesta , "error")
                            }else{
                                Swal.fire("Éxito",response.respuesta , "success");
                               
                                $('#tableDetenidos').bootstrapTable('refresh');
                                //window.location.reload();
                                //$('#products-datatable').DataTable().ajax.reload(null, false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.fire("Error", "Favor de llamar al Administrador", "error")
                        }
                    });
                }
            });
           
        },
  
        updateParticipante: function(id){
            $("#btn_guardar").hide();
            $("#btn_load").show()
                $.ajax({
                    url: base_url + "index.php/Principal/updateDetenido",
                    type: 'POST',
                    dataType: "json",
                    data:{id_participante:id},
                    success: function(response) {
                        console.log( response);
                        if(response.error){
                            Swal.fire("Error",response.respuesta , "error")
                        }else{
                            let fechaNacimiento = response.data.fec_nac;
                            const formattedDate = fechaNacimiento.split("T")[0];
                            console.log(response);
                            $("#nombre").val(response.data.nombre);
                            $("#primer_apellido").val(response.data.primer_apellido);
                            $("#segundo_apellido").val(response.data.segundo_apellido);
                            //$("#id_municipio").val(response.data.id_municipio);
                            $("#id_municipio").val(response.data.id_municipio).trigger('change');
                            $("#curp").val(response.data.curp);
                            $("#curp_viejo").val(response.data.curp);
                            $("#fec_nac").val(formattedDate);
                            $("#centro_gestor").val(response.data.centro_gestor);
                            $("#jefe_inmediato").val(response.data.jefe_inmediato);
                            $("#area").val(response.data.area);
                            $("#rfc").val(response.data.rfc);
                            $("#correo_enlace").val(response.data.correo_enlace);
                            $("#denominacion_funcional").val(response.data.denominacion_funcional);
                            $("#funcion").val(response.data.funcion);
                            $("#correo").val(response.data.correo);
                            $("#id_nivel").val(response.data.id_nivel).trigger('change');
                            $("#id_sexo").val(response.data.id_sexo).trigger('change');
                            $("#editar").val(1);
                            $("#id_detenido").val(0);
                            $("#id_participante").val(id);
                           // window.location.reload();
                           
                           $("#btn_guardar").show();
                           $("#btn_load").hide()
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Swal.fire("Error", "Favor de llamar al Administrador", "error")
                    }
                });
        },
        updateDetenido: function(id){
            $("#btn_guardar").hide();
            $("#btn_load").show()
                $.ajax({
                    url: base_url + "index.php/Principal/updateDetenido",
                    type: 'POST',
                    dataType: "json",
                    data:{id_detenido:id},
                    success: function(response) {
                        console.log( response);
                        if(response.error){
                            Swal.fire("Error",response.respuesta , "error")
                        }else{
                            console.log(response.data.fec_nac);
                            
                            $("#nombre").val(response.data.nombre);
                            $("#primer_apellido").val(response.data.primer_apellido);
                            $("#segundo_apellido").val(response.data.segundo_apellido);
                            //$("#id_municipio").val(response.data.id_municipio);
                            $("#id_municipio").val(response.data.id_municipio).trigger('change');
                            $("#curp").val(response.data.curp);
                            $("#curp_viejo").val(response.data.curp);
                            $("#fec_nac").val(response.data.fec_nac);
                            $("#centro_gestor").val(response.data.centro_gestor);
                            $("#jefe_inmediato").val(response.data.jefe_inmediato);
                            $("#area").val(response.data.area);
                            $("#correo_enlace").val(response.data.correo_enlace);
                            $("#correo").val(response.data.correo);
                            $("#denominacion_funcional").val(response.data.denominacion_funcional);
                            $("#funcion").val(response.data.funcion);
                            $("#id_nivel").val(response.data.id_nivel).trigger('change');
                            $("#id_sexo").val(response.data.id_sexo).trigger('change');
                            //Swal.fire("Exitó",response.respuesta , "success")
                            $("#editar").val(1);
                            $("#id_detenido").val(id);
                            $("#id_participante").val(0)
                            validarCURP();
                           // window.location.reload();
                           $("#btn_guardar").show();
                           $("#btn_load").hide()
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Swal.fire("Error", "Favor de llamar al Administrador", "error")
                    }
                });
        },
        
    }
    
})();