<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Bitacoracontrol;
use App\Models\Mglobal;
use CodeIgniter\HTTP\Response;
use stdClass;

class Magregarturno extends Model 
{
    protected $DBGroup = 'default';
    public $errorConexion = false;
    private $Mglobal;

    //protected $table = 'zeus_usuarios';

    function __construct() {
        parent::__construct();        
        $this->db->query("SET lc_time_names = 'es_MX'");
        $this->Mglobal = new Mglobal();
    }

    public function guardarTurnoNuevo($dataInsert,$dataBitacora){
       
        $Bitacoracontrol = new Bitacoracontrol();
        $response = new \stdClass();
        $response->error = true;
        $bitacora = [];
        $data = [];
        $datos = [];
        $datosCopia = [];
        $datosIndicacion = [];
        $errorDB = false;
        
        /** para guardar en la primera
            * Funcion que realiza el guardado, actualización y manejop de errores en el manejo de tablas
            * @param object:db                     La instancia de base de datos que estas manejando. [$this->db]
            * @param object:response               Objeto stdClass para manejo de respuesta
            * @param array:dataInsert
            * @param array:dataBitacora
            * @param string:tabla
            * @param array:bitacora
            * @param string:variableReferencia     (opcional) Nombre de la variable que manejará el id insertado, RECOMENDABLE PARA TABLAS PRINCIPALES
            * @param array:editar                  (opcional) Llave primaria para editar ["idCampoTablaName",idTabla]
            * @param array:adicionales             Variable utilizada para el caso en que se requiera cambiar parte de la estructura de la función
            */
        // die(var_dump($dataInsert['id_destinatario']));  
        
        $dataInsert = [
            'id'                    => $dataInsert['id'],           
            'clave_ramo'            => $dataInsert['clave_ramo'],           
            'nombre_ramo'           => $dataInsert['nombre_ramo'],           
            'abreviatura_ramo'      => $dataInsert['abreviatura_ramo'],           
            'enlace'                => $dataInsert['enlace']       
        ];
        
        // die(var_dump($dataInsert)); 
        $this->db->transBegin();     
            if(!$this->Mglobal->localSaveTabla($this->db, $response, $data, $dataBitacora, 'centro', $bitacora, 'id_centro', false, false)){
                log_message("critical","Respuesta: ".json_encode($response));
                log_message('critical','statusDB signosVitales: '.json_encode($this->db->transStatus()));
                return $response;
            }
            foreach ($dataInsert['id_destinatario'] as $valor) {
                $datos[] = ['id_turno'=>$response->id_turno,'id_destinatario' => (int)$valor];
            }
            $dataConfig = [
                'tabla' => "turno_destinatario",
                'paramIdTabla' => "id_turno_destinatario",
                'paramDelete' => ['visible' => 0],
                'whereDelete' => ['id_turno'=>$response->id_turno, 'visible' => 1],
                'llave' => ['id_turno','id_destinatario' ],
            ];
            if (!$this->Mglobal->localUpdateInsertTabla($datos, $dataConfig, $dataBitacora, "turnoDestinatario", $this->db, $response, $bitacora )){
                log_message("critical","Respuesta: ".json_encode($response));
                log_message('critical','statusDB: '.json_encode($this->db->transStatus()));
                return $response;
            }
            ////////////////////////////////////////
            foreach ($dataInsert['id_destinatario_copia'] as $valor) {
                $datosCopia[] = ['id_turno'=>$response->id_turno,'id_destinatario' => (int)$valor];
            }
            $dataConfig = [
                'tabla' => "turno_con_copia",
                'paramIdTabla' => "id_turno_con_copia",
                'paramDelete' => ['visible' => 0],
                'whereDelete' => ['id_turno'=>$response->id_turno, 'visible' => 1],
                'llave' => ['id_turno','id_destinatario'],
            ];
            if (!$this->Mglobal->localUpdateInsertTabla($datosCopia, $dataConfig, $dataBitacora, "turnoConCopia", $this->db, $response, $bitacora )){
                log_message("critical","Respuesta: ".json_encode($response));
                log_message('critical','statusDB: '.json_encode($this->db->transStatus()));
                return $response;
            }
            ////////////////////////////////////////
            foreach ($dataInsert['id_indicacion'] as $valor) {
                $datosIndicacion[] = ['id_turno'=>$response->id_turno,'id_indicacion' => (int)$valor];
            }
            $dataConfig = [
                'tabla' => "turno_indicacion",
                'paramIdTabla' => "id_turno_indicacion",
                'paramDelete' => ['visible' => 0],
                'whereDelete' => ['id_turno'=>$response->id_turno, 'visible' => 1],
                'llave' => ['id_turno','id_indicacion'],
            ];
            if (!$this->Mglobal->localUpdateInsertTabla($datosIndicacion, $dataConfig, $dataBitacora, "turnoIndicacion", $this->db, $response, $bitacora )){
                log_message("critical","Respuesta: ".json_encode($response));
                log_message('critical','statusDB: '.json_encode($this->db->transStatus()));
                return $response;
            }

        $this->db->transCommit();    
       $response->error = false; 
       return $response;

    }
    

  
    
        
       
     
}