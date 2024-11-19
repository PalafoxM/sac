<?php  $session = \Config\Services::session();    ?>

    
<style>
    .neon {
        display: inline-block;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 10px;
        border: none;
        font: normal 20px/normal "Warnes", Helvetica, sans-serif;
        color: rgba(255,255,255,1);
        text-decoration: normal;
        text-align: center;
        -o-text-overflow: clip;
        text-overflow: clip;
        white-space: pre;
        text-shadow: 0 0 10px rgba(255,255,255,1) , 0 0 20px rgba(255,255,255,1) , 0 0 30px rgba(255,255,255,1) , 0 0 40px #ff00de , 0 0 70px #ff00de , 0 0 80px #ff00de , 0 0 100px #ff00de ;
        -webkit-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        -moz-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        -o-transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1);
        transition: all 200ms cubic-bezier(0.42, 0, 0.58, 1); 
    }
    .neon:hover {
    text-shadow: 0 0 10px rgba(255,255,255,1) , 0 0 20px rgba(255,255,255,1) , 0 0 30px rgba(255,255,255,1) , 0 0 40px #00ffff , 0 0 70px #00ffff , 0 0 80px #00ffff , 0 0 100px #00ffff ;
    }
    body{
        background-color: #d1d7d9;
    }
    section{
        border: 2px solid darkgray;
        padding: 20px;
        margin-top: 10px;
    }
    .enLiniea{
        display: flex;
        align-items: stretch;
    }
    .item {
        flex-grow: 4; /* default 0 */
    }
    table {
            border-collapse: collapse;
            width: 100%;
        }
    th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

    /* Estilo para todas las opciones */
    .opciones {
        font-weight: bold;
        color:black ;
    }

    /* Estilo para las dos primeras opciones en el select */
    .primeras2 {
        font-weight: bold;
        color: blue;
    }
    .primeras2:hover{
        color:#d1d7d9;
    }
    .icono{
        font-weight: bold;
        color:yellow;
    }
   
    .campoObligatorio::after {
            content: "*";
            color: red;
            margin-left: 5px; 
        }
    .invalid-input  {
      border: 2px solid red;

    }
   
</style>
<body>
    <div class=" mt-3">
        <form id="formAgregarTurno" name="formAgregarTurno" >
            <div class="row">
                <!-- seccion izquierdo incio -->
                <div class="col-md-12 ">
                    <div class="card"><!--init card -->
                        <div class="card-body">
                            <blockquote class="blockquote">
                                <h3 class="textoNegro">REGISTRO:</h3>
                                <small>Los campos marcados con<strong class="campoObligatorio"></strong> son obigatorios</small>
                            </blockquote>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3 position-relative" id="datepicker1">
                                        <label for="fecha_peticion" class="form-label campoObligatorio">ID</label>
                                        <input type="text" class="form-control" data-date-autoclose="true" data-date-container="#datepicker1" id="id" name="id" placeholder="ID">
                                        <div id="fecha-error" style="color: red; display: none;">No se pueden ingresar fechas futuras.</div>
                                    </div>
                                </div>
                            
                                <div class="col-md-3">
                                    <div class="mb-3 position-relative" id="datepicker1">
                                        <label for="fecha_peticion" class="form-label campoObligatorio">CLAVE DEL RAMO</label>
                                        <input type="text" class="form-control" data-date-autoclose="true" data-date-container="#datepicker1" id="clave_ramo" name="clave_ramo" placeholder="CLAVE DEL RAMO" required>
                                        <div id="fecha-error" style="color: red; display: none;">No se pueden ingresar fechas futuras.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative" id="datepicker2">
                                        <label for="fecha_recepcion" class="form-label campoObligatorio">NOMBRE DEL RAMO</label>
                                        <input type="text" class="form-control" data-date-autoclose="true" data-date-container="#datepicker2" id="nombre_ramo" name="nombre_ramo"  placeholder="NOMBRE DEL RAMO" required>
                                        <div id="fecha-error2" style="color: red; display: none;">No se pueden ingresar fechas futuras.</div>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="abreviatura_ramo" class="form-label campoObligatorio">ABREVIATURA DEL RAMO</label>
                                        <input type="text" id="abreviatura_ramo" name="abreviatura_ramo" class="form-control form-control-sm" placeholder="ABREVIATURA DEL RAMO"  onkeyup="st.agregar.toUpperCase(this)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="enlace" class="form-label campoObligatorio">ENLACE</label>
                                        <input type="text" id="enlace" name="enlace" class="form-control form-control-sm " placeholder="ENLACE" required  onkeyup="st.agregar.toUpperCase(this)">
                                    </div>
                                </div>
                               
                            </div>
                          
                          
                        </div>    
                    </div><!--end card -->
                </div>
                <!-- seccion izquierdo fin-->
                <!-- seccion derecha incio -->
            </div>
       
        
                <div class="row mb-5 ">
                        <div class="col-md-12 text-center ">
                            <button class="btn btn-info" type="submit"><i class="mdi mdi-content-save"></i> Guardar </button>
                            <button class="btn btn-warning" type="button" onclick="st.agregar.cancelarTurno();"><i class="mdi mdi-content-save-off-outline" id="cancelarTurno" ></i> Cancelar </button>
                        </div>
                </div>
        </form>    
    </div>
<script>
    $(document).ready(function(){
        st.agregar.saveTempccp();
        st.agregar.saveTempIndicacion();
        st.agregar.saveTempNombreTurno();
        st.agregar.agregarTurno();  
        
       
    });
</script>
