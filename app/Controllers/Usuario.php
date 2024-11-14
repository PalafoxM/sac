<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use stdClass;
use CodeIgniter\API\ResponseTrait;
require_once FCPATH . '/mpdf/autoload.php';
class Usuario extends BaseController {

    use ResponseTrait;
    private $defaultData = array(
        'title' => 'Turnos 2.0',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );
    public function __construct()
    {
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');  
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        if($session->get('logueado')!= 1){
            header('Location:'.base_url().'index.php/Login/cerrar?inactividad=1');            
            die();
        }
    }

    private function _renderView($data = array()) {     
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data); 
                      
    }

    public function index()
    {        
        $session = \Config\Services::session();   
        $data = array();
    
    /*     $data['unidad'] = $this->globals->getTabla(["tabla"=>"cat_clues","select"=>"id_clues, NOMBRE_UNIDAD", "where"=>["visible"=>1],'limit' => 10]); 
        $data['perfiles'] = $this->globals->getTabla(["tabla"=>"seg_perfiles", "where"=>["visible"=>1]]); */ 
        $data['cat_nivel'] = $this->globals->getTabla(["tabla"=>"cat_nivel", "where"=>["visible"=>1]])->data; 
        $data['cat_perfil'] = $this->globals->getTabla(["tabla"=>"cat_perfil", "where"=>["visible"=>1]])->data; 
       // die( var_dump( $data['cat_nivel ']  ) );
        $data['scripts'] = array('principal','inicio');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo; 
        $data['contentView'] = 'secciones/vInicio';                
        $this->_renderView($data);
        
    }
    
    public function getUsuarios()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $dataDB = array();
        if ($session->id_perfil == -1) {
            $dataDB = array('tabla' => 'vw_usuarios', 'where' => 'id_perfil >= 1 AND visible = 1 ORDER BY fecha_registro DESC');
        } elseif ($session->id_perfil == 1) {
            $dataDB = array('tabla' => 'vw_usuarios', 'where' => 'id_perfil >= 1 AND visible = 1 ORDER BY fecha_registro DESC');
        } 
        $response = $principal->getTabla($dataDB);
        // var_dump($response);
        // die();
        return $this->respond($response->data);
    }
    public function getCurso()
    {
        $session = \Config\Services::session();
        $id_categoria = $this->request->getPost('id_categoria');
        
        // Validar que el ID de usuario esté presente y sea válido
        if (!$id_categoria) {
            return $this->fail('ID no proporcionado', 400);
        }

        // var_dump($id_usuario);
        // die();
        $response = $this->globals->getTabla(["tabla"=>"categoria","where"=>["id_categoria" => $id_categoria, "visible" => 1]])->data;
   
        return $this->respond($response[0]);
    }
    public function getUsuario()
    {
        $session = \Config\Services::session();
        $id_usuario = $this->request->getPost('id_usuario');
        
        // Validar que el ID de usuario esté presente y sea válido
        if (!$id_usuario) {
            return $this->fail('ID no proporcionado', 400);
        }

        // var_dump($id_usuario);
        // die();
        $response = $this->globals->getTabla(["tabla"=>"usuario","where"=>["id_usuario" => $id_usuario, "visible" => 1]])->data;
   
        return $this->respond($response[0]);
    }
    public function deleteCurso()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_curso']) || empty($data['id_curso'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla"=>"curso",
            "editar"=>true,
            "idEditar"=>['id_curso'=>$data['id_curso']]
        ];
        $response = $this->globals->saveTabla(["visible"=>0],$dataConfig,["script"=>"Usuario.deleteCurso"]);
        return $this->respond($response);
    }
    public function deleteUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_usuario']) || empty($data['id_usuario'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla"=>"usuario",
            "editar"=>true,
            "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla(["visible"=>0],$dataConfig,["script"=>"Usuario.deleteUsuario"]);
        return $this->respond($response);
    }
    public function UpdateCurso()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        // var_dump(isset($data['editar']));
        // die();
        
        $dataInsert = [
            'dsc_curso'       => $data['dsc_curso'],
       
        ];
        
        if (isset($data['editar']) && $data['editar']==1 ){
            $dataConfig = [
                "tabla"=>"curso",
                "editar"=>true,
                "idEditar"=>['id_curso'=>$data['id_curso']]
            ];  
        }else{
            $dataConfig = [
                "tabla"=>"curso",
                "editar"=>false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
        }
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.updateCurso"]);
        return $this->respond($response);
    }
    public function updateOrder()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

      

            // Obtener el evento con el id_orden viejo
        $eventoDB = [
            "tabla" => "eventos",
            "where" => ["id_curso" =>  $data['id_curso'], 'id_orden' => $data['id_orden_viejo'], "visible" => 1]
        ];
        $idEventoViejo = $this->globals->getTabla($eventoDB);
        $id_evento_viejo = (!empty($idEventoViejo->data)) ? $idEventoViejo->data[0]->id_evento : "";

        // Obtener el evento con el id_orden nuevo
        $eventoDBNuevo = [
            "tabla" => "eventos",
            "where" => ["id_curso" => $data['id_curso'], 'id_orden' => $data['id_orden_nuevo'], "visible" => 1]
        ];
        $idEventoNuevo = $this->globals->getTabla($eventoDBNuevo);
        $id_evento_nuevo = (!empty($idEventoNuevo->data)) ? $idEventoNuevo->data[0]->id_evento : "";

        // Verificar que ambos eventos existan
       if (!empty($id_evento_viejo) && !empty($id_evento_nuevo)) {
            // Actualizar el id_orden del evento viejo con el nuevo valor
            $dataConfig = [
                "tabla" => "eventos",
                "editar" => true,
                "idEditar" => ['id_evento' => $id_evento_viejo]
            ];
            $response = $this->globals->saveTabla(
                ["id_orden" => $data['id_orden_nuevo']], 
                $dataConfig, 
                ["script" => "Eventos.posicionEvento"]
            );

            // Actualizar el id_orden del evento nuevo con el valor viejo
            $dataConfig = [
                "tabla" => "eventos",
                "editar" => true,
                "idEditar" => ['id_evento' => $id_evento_nuevo]
            ];
            $response = $this->globals->saveTabla(
                ["id_orden" => $data['id_orden_viejo']], 
                $dataConfig, 
                ["script" => "Eventos.posicionEvento"]
            );

        

        }
        $response->error = false;
        return $this->respond($response);
    }
    public function deleteEvento()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        $dataConfig = [
            "tabla"=>"eventos",
            "editar"=>true,
            "idEditar"=>['id_evento'=>$data['id_evento']]
        ];

        $response = $this->globals->saveTabla(["visible"=>0],$dataConfig,["script"=>"Eventos.deleteEvento"]);
        return $this->respond($response);
    }
    public function saveTableData()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if(empty($data['dsc_evento'])){
            $response->respuesta = "El nombre del curso es requerido";
            return $this->respond($response);
        }
        if(empty($data['fec_inicio']) || empty($data['fec_fin'])){
            $response->respuesta = "La fecha Inicio o fin es requerido";
            return $this->respond($response);
        }
        if($data['fec_inicio'] > $data['fec_fin']){
            $response->respuesta = "La fecha de inicio no debe ser mayor a fecha fin";
            return $this->respond($response);
        }
      
         $dataInsert = [
             'courseName'   => $data['dsc_evento'],
             'idCategoria' => $data['id_categoria'],
             'startDate'   => $data['fec_inicio'],
             'endDate'      => $data['fec_fin'],
             'idnumber'      => $data['id_sap']
         ];

                //validar que el nombre del evento no exista
    
        $dscCurso = $this->globals->getTabla([
            "tabla" => "eventos",
            "where" => ["dsc_curso" => $data['dsc_evento'], "visible" => 1]
        ]);
        if(!empty($dscCurso->data)){
                    $response->respuesta = "El nombre del curso ya existe en la base de datos";
                    return $this->respond($response);
                }
                // Llamada a createCurso y control de errores
        $response = $this->globals->createCurso($dataInsert, 'crearCurso');
 
        if ($response && !$response->error) {
            $insert = [
                'id_curso_moodle' => $response->data[0]->id,
                'dsc_curso'       => $data['dsc_evento'],
                'dsc_curso_corto' => $response->data[0]->shortname,
                'id_categoria'    => $data['id_categoria'],
                'fec_inicio'      => $data['fec_inicio'],
                'fec_fin'         => $data['fec_fin'],
                'fec_reg'         => date('Y-m-d H:i:s')
            ]; 
    
            $dataConfig = [
                "tabla"  => "eventos",
                "editar" => false
            ];
    
            // Intento de inserción y control de errores
            $saveResponse = $this->globals->saveTabla($insert, $dataConfig, ["script"=>"Eventos.saveEvento"]);
            
            if (!$saveResponse) {
                error_log("Error al insertar el evento en la base de datos. ID del evento: " . $key['id']);
            }
        } else {
            error_log("Error en createCurso para el evento: " . $key['nombre_evento']);
        }
    
            
          
        
        
        return $this->respond($response);
    }
    public function saveEvento()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        $id_moodle = $this->globals->getTabla(["tabla"=>"eventos","where"=>["id_curso" => $data['id_curso'], "id_moodle" =>$data['id_moodle'] ,"visible" => 1]])->data;
        if(!empty($id_moodle)){
            $response->error = true;
            $response->respuesta = 'ID MOODLE DUPLICADO';
            $response->id_evento = $id_moodle[0]->id_evento;
            return  $this->respond($response);
           
        }
        $dataInsert = [
            'id_curso'        => $data['id_curso'],
            'id_moodle'       => $data['id_moodle'],
            'dsc_evento'      => $data['dsc_evento'],
            'id_orden'        => $data['id_orden'],
            'fec_reg'         => date('Y-m-d H:i:s'),
        ];
        
        $dataConfig = [
            "tabla"=>"eventos",
            "editar"=>false,

        ];

        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.updateCurso"]);
        return $this->respond($response);
    }
    public function UpdateUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        // var_dump(isset($data['editar']));
        // die();
        
        $dataInsert = [
            'id_usuario'       => $data['id_usuario'],
            'nombre'           => $data['nombre'],
            'primer_apellido'  => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'rfc'              => $data['rfc'],  // Corregí 'efc' a 'rfc' por si fue un typo
            'curp'             => $data['curp'],
            'jefe_inmediato'   => $data['jefe_inmediato'],
            'area'             => $data['area'],
            'id_perfil'        => $data['id_perfil'],
            'id_nivel'         => $data['id_nivel'],
        ];
        
        // Agregar 'contrasenia' solo si está definida y no está vacía
        if (isset($data['contrasenia']) && !empty($data['contrasenia'])) {
            $dataInsert['contrasenia'] = md5($data['contrasenia']);
        }
        
        if (isset($data['editar']) && $data['editar']==1 ){
            $dataConfig = [
                "tabla"=>"usuario",
                "editar"=>true,
                "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];  
        }else{
            $dataConfig = [
                "tabla"=>"centro",
                "editar"=>false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
        }
        

        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.saveUsuario"]);
        return $this->respond($response);
    }
    public function saveUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        var_dump($data['id_usuario']);
        die();
        // if (!isset($data['id_usuario']) || empty($data['id_usuario'])){
        //     $response->respuesta = "No se ha proporcionado un identificador válido";
        //     return $this->respond($response);
        // }
        // $dataInsert=[
        //     'dsc_carpeta'          => $dsc_carpeta,
        //     'id_carpeta_padre'  => $id_carpeta_raiz,
        //     'id_unidad'           => $id_unidad,
        //     'ruta'           => $ruta_raiz.'/'.$nombre_unix,
        //     'ruta_real'       => $ruta_carpeta_fisica,
        //     'fecha_registro'       => date('Y-m-d H:i:s'),
        //     'usuario_registro' => $session->id_usuario,
        //     'visible'     => 1,
        //     'nombre_carpeta'     => $nombre_unix
        // ];

        $dataConfig = [
            "tabla"=>"seg_usuarios",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.saveUsuario"]);
        return $this->respond($response);
    }
    
}