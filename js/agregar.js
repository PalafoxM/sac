var st = window.ssa || {};

st.agregar = (function () {
    return {
        sha256: function(str) {
            var buffer = new TextEncoder("utf-8").encode(str);
            return crypto.subtle.digest("SHA-256", buffer).then(function(hash) {
                return Array.prototype.map.call(new Uint8Array(hash), function(x) {
                    return ('00' + x.toString(16)).slice(-2);
                }).join('');
            });
        },
        formConfigurarCurso: function(){
            $('#btn_guardar_conf').on('click', function() {
                $("#btn_guardar_conf").hide();
                $("#btn_guardar_load").show();
                let tableData = [];
            
                // Itera sobre cada fila en el cuerpo de la tabla
                $('tbody tr').each(function() {
                    let rowData = {
                        name: $(this).find('td:first').text(),  // Nombre del curso
                        id_curso: $(this).find('input[name^="id_curso"]').val(), // Fecha de inicio
                        timeopen: $(this).find('input[name^="timeopen"]').val(), // Fecha de inicio
                        timeclose: $(this).find('input[name^="timeclose"]').val(), // Fecha de fin
                       // timelimit: $(this).find('td:nth-child(4)').text(), // Límite de tiempo
                       // visible: $(this).find('input[type="checkbox"]').is(':checked') ? 1 : 0 // Si está visible
                    };
            
                    tableData.push(rowData);
                });
                let id_curso = $("#id_curso").val();
                // Enviar datos a PHP mediante AJAX
                $.ajax({
                    url: base_url + "index.php/Agregar/formConfigurarCurso",
                    type: 'POST',
                    data: { tableData: tableData, id_curso:id_curso },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire("Éxito", "Datos guardados correctamente.", "success");
                            //window.location.reload();
                            window.location.href = base_url + "index.php/Principal/Matricular/";
                        } else {
                            Swal.fire("Error", "No se pudo guardar la configuración.", "error");
                        }
                        $("#btn_guardar_conf").show();
                        $("#btn_guardar_load").hide();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire("Error", "Ocurrió un error en la solicitud: " + error, "error");
                    }
                });
             
             
            });
            
        },
        copiar_fecha: function(){
            $('#btn_fecha').on('click', function(e) {
                e.preventDefault(); 
                // Obtiene los valores de fecha de inicio y fin
                let fec_inicio = $('#fec_inicio').val();
                let fec_fin = $('#fec_fin').val();
            
                console.log("Fecha inicio:", fec_inicio, "Fecha fin:", fec_fin);
            
                // Itera sobre cada fila en el cuerpo de la tabla y asigna los valores
                $('tbody tr').each(function(index) {
                    // Encuentra los inputs que comienzan con "timeopen" y "timeclose" según el índice
                    $(this).find(`input[name="timeopen${index}"]`).val(fec_inicio);
                    $(this).find(`input[name="timeclose${index}"]`).val(fec_fin);
                   
                });
            
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 2500
                  })
                  
                  Toast.fire({
                      type: 'success',
                      title: 'Copia de fechas exitosa',
                      icon: 'success'
                  });
                
 
            });
            
        },
     
        agregarTurno: function(){
            $("#formAgregarUsuarioTsi").submit(function (e) {
                e.preventDefault(); 
                var formData = $("#formAgregarUsuarioTsi").serialize();
                $("#btn_save").hide();
                $("#btn_load").show();
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/Agregar/guardaUsuarioSti",
                    data:formData,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(response.respuesta.error){
                            Swal.fire("error", "Solicite apoyo al area de sistemas");
                        }
                        Swal.fire("success", "Se guardo con exito", 'success');
                        $("#formAgregarUsuarioTsi")[0].reset();
                        $("#btn_save").show();
                        $("#btn_load").hide()
              
                        window.location.href = base_url + "index.php/Inicio";
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                         var res= JSON.parse (response.responseText);
                        //  console.log(res.message);
                         Swal.fire("Error", '<p> '+ res.message + '</p>', 'error');  
                         $("#btn_save").show();
                         $("#btn_load").hide();
                    }
                });
            });
        },
       
        
        
        cancelarTurno: function(){
            Swal.fire({
                title: "¿Está seguro de que desea cancelar?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Si",
                
              }).then((result) => {
                if (result.isConfirmed) {
                    $("#formAgregarTurno")[0].reset();
                    window.location.href = base_url + "index.php/Inicio";
                } else if (result.isDenied) {
                  Swal.fire("Ok", "", "info");
                }
              });
               
           
        },
        saveTempNombreTurno: function(){
            $('#nombre_turno').on('change', function() {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#nombre_turno option:selected').map(function() {
                    return $(this).text();
                }).get();
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesNombreTurno tbody').empty();
                $('#selectedValuesNombreTurno1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesNombreTurno tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesNombreTurno1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesNombreTurno tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesNombreTurno1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },
        saveTempccp: function(){
            
            $('#cpp').on('change', function() {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#cpp option:selected').map(function() {
                    return $(this).text();
                }).get();

                // Actualizar la tabla
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesTable tbody').empty();
                $('#selectedValuesTable1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesTable tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesTable1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesTable tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesTable1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },
        saveTempIndicacion: function(){
            $('#indicacion').on('change', function() {
                // Obtener los valores y textos de las opciones seleccionadas
                var selectedValues = $(this).val();
                var selectedTexts = $('#indicacion option:selected').map(function() {
                    return $(this).text();
                }).get();
                updateTable(selectedValues, selectedTexts);
            });

            // Función para actualizar la tabla
            function updateTable(values, texts) {
                // Limpiar la tabla
                $('#selectedValuesIndicacion tbody').empty();
                $('#selectedValuesIndicacion1 tbody').empty();

                // Mostrar los valores y textos seleccionados en la tabla
                if (values && values.length > 0) {
                    for (var i = 0; i < values.length; i++) {
                        $('#selectedValuesIndicacion tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                        $('#selectedValuesIndicacion1 tbody').append('<tr><td>' + values[i] + '</td><td>' + texts[i] + '</td></tr>');
                    }
                } else {
                    $('#selectedValuesIndicacion tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                    $('#selectedValuesIndicacion1 tbody').append('<tr><td colspan="2">No hay elementos seleccionados</td></tr>');
                }
            }
        },
        validarEntrada:function(input) {
            var resumen = input.val();
            var regex = /^[a-zA-Z0-9\s.,!?()-]+$/;
            $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";
            if (resumen.length > 0 && resumen.length <= 600 && regex.test(resumen)) {
              input.removeClass("invalid-input");
              return true;  
            } else {
              input.addClass("invalid-input");
              return false;
              
            }
          },
          // convioerte todo los de los inputs a mayusculas
          toUpperCase:function(element){
            element.value = element.value.toUpperCase();
        }
        
    }
})();