<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Magregarturno;

define('ENCRYPTION_KEY', 'your-secure-key'); 
define('ENCRYPTION_METHOD', 'AES-256-CBC');


use stdClass;
use Exception;
use CodeIgniter\API\ResponseTrait;

class Agregar extends BaseController {

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
        if($session->get('logueado')!= 1){
            header('Location:'.base_url().'index.php/Login/cerrar?inactividad=1');            
            die();
        }
    }

    private function _renderView($data = array()) {     
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }
    
    //public function obtenerOpcionesSelect($select, $tabla, $where = null)
    //{
    //     $catalogos = new Mglobal;
    //     try {
    //         $dataDB = array('select' => $select, 'tabla' => $tabla, 'where' => $where);
    //         $response = $catalogos->getTabla($dataDB);

    //         if (isset($response) && isset($response->data)) {
    //             return $response->data;
    //         } else {
    //             return array();
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', "Se produjo una excepción: " . $e->getMessage());
    //         return array();
    //     }
    // }

    // Ejemplo de uso
    // $opcionesAsunto = obtenerOpcionesSelect('id_asunto, dsc_asunto', 'cat_asuntos', 'visible = 1');


    // public function index()
    // {        
       
    //     $session = \Config\Services::session();   
    //     $data = array();
    //     $catalogos = new Mglobal;
      
    //     try {
    //         $dataDB = array('select'=> 'id_asunto, dsc_asunto', 'tabla' => 'cat_asuntos', 'where' => 'visible = 1');
    //         $response = $catalogos->getTabla($dataDB);
    //         if (isset($response) && isset($response->data)) {
    //             $data['cat_asunto'] = $response->data;
    //         } else {
    //             $data['cat_asunto'] = array(); 
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', "Se produjo una excepción: " . $e->getMessage());
    //     }
    //     try {
    //         $dataDB = array( 'select'=> 'id_destinatario, nombre_destinatario, cargo, id_tipo_cargo',  'tabla' => 'cat_destinatario', 'where' => 'visible = 1');
    //         $response = $catalogos->getTabla($dataDB);
    //         if (isset($response) && isset($response->data)) {
    //             $data['turnado'] = $response->data;
    //             $resultadoFiltrado = array_filter($response->data, function($elemento) {
    //                 return $elemento->id_tipo_cargo == '2';
    //             });
    //             $data['cppNombre']= $resultadoFiltrado;
    //             $personaFirma = array_filter($response->data, function($elemento) {
    //                 return $elemento->id_tipo_cargo == '9';
    //             });
    //             $data['firmaTurno']= $personaFirma;

    //         } else {
    //             $data['turnado'] = array(); 
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', "Se produjo una excepción: " . $e->getMessage());
    //     }
    //     try {
    //         $dataDB = array( 'select'=> 'id_indicacion, dsc_indicacion',  'tabla' => 'cat_indicaciones', 'where' => 'visible = 1');
    //         $response = $catalogos->getTabla($dataDB);
    //         if (isset($response) && isset($response->data)) {
    //             $data['indicacion'] = $response->data;
    //         } else {
    //             $data['indicacion'] = array(); 
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', "Se produjo una excepción: " . $e->getMessage());
    //     }
    //     try {
    //         $dataDB = array( 'select'=> 'id_estatus, dsc_status',  'tabla' => 'cat_estatus', 'where' => 'visible = 1');
    //         $response = $catalogos->getTabla($dataDB);
    //         if (isset($response) && isset($response->data)) {
    //             $data['status'] = $response->data;
    //         } else {
    //             $data['status'] = array(); 
    //         }
    //     } catch (\Exception $e) {
    //         log_message('error', "Se produjo una excepción: " . $e->getMessage());
    //     }
    //     //  var_dump($data['firmaTurno']);
    //     //  die();
    //     $data['scripts'] = array('principal','agregar');
    //     $data['edita'] = 0;
    //     $data['nombre_completo'] = $session->nombre_completo; 
    //     $data['contentView'] = 'formularios/vFormAgregar';                
    //     $this->_renderView($data);
        
    // }
    public function index()
    {
        $session = \Config\Services::session();
        $data = array();
        $catalogos = new Mglobal;

      
        // var_dump($data['cat_destinatario']);
        // die();

            $data['scripts'] = array('principal','agregar');
            $data['edita'] = 0;
            $data['nombre_completo'] = $session->nombre_completo; 
            $data['contentView'] = 'formularios/vFormAgregar';                
            $this->_renderView($data);
    }

    public function usuarioSti()
    {
        $session = \Config\Services::session();
        if($session->get('id_perfil') >= 5){
            header('Location:'.base_url().'index.php/Principal/Matricular');            
            die();
        }
        $data             = array();
        $catalogos        = new Mglobal;
        $dataDB           = array('tabla' => 'cat_nivel', 'where' => ['visible' => 1]);
        $dependenciaDB    = array('tabla' => 'cat_dependencia', 'where' => ['visible' => 1]);
        $perfilDB         = array('tabla' => 'cat_perfil', 'where' => ['visible' => 1]);
        $cat_nivel        = $catalogos->getTabla($dataDB);
        $cat_dependencia  = $catalogos->getTabla($dependenciaDB);
        $cat_perfil       = $catalogos->getTabla($perfilDB);
        
        $data['cat_nivel'] =$cat_nivel->data;
        $data['cat_dependencia'] =$cat_dependencia->data;
        $data['cat_perfil'] =$cat_perfil->data;
        //die(var_dump( $data['cat_dependencia']  ) );
        $data['scripts'] = array('agregar');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo; 
        $data['contentView'] = 'formularios/vUsuarioSti';                
        $this->_renderView($data);
    }
    public function Curso()
    {
        $session = \Config\Services::session();
        $data             = array();
        $catalogos        = new Mglobal;
        
        $data['scripts'] = array('inicio');
        $data['perfil']  =  $session->get('id_perfil');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo; 
        $data['contentView'] = 'formularios/vCurso';                
        $this->_renderView($data);
    }

    private function handleException($e)
    {
        log_message('error', "Se produjo una excepción: " . $e->getMessage());
    }
    // function validarCampo($valor,$nombreCampo) {
    // // function validarCampo($valor, $nombreCampo) {
    //     $pattern = "/^([a-zA-Z 0-9]+)$/";
    //     // global $pattern;
    //     // return preg_match($pattern, $valor) ? $valor : null;
    //     if (!preg_match($pattern, $valor)) {
    //         throw new Exception("Error en el campo '$nombreCampo': No cumple con el patrón esperado.");
    //         // $this->handleException("Error en el campo '$nombreCampo': No cumple con el patrón esperado.");
    //     }
    
    //      return $valor;
    // }
    // function validarCampo($valor, $nombreCampo) {
    //     $pattern = "/^([a-zA-Z 0-9]+)$/";
    
    //     if (!preg_match($pattern, $valor)) {
    //         throw new Exception("Error en el campo '$nombreCampo': No cumple con el patrón esperado.");
    //     }
    
    //     return $valor;
    // }
    function validarCampo($valor, $nombreCampo) {
        // $pattern = "/^([a-zA-Z 0-9]+)$/";
        $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";
        
        if (!preg_match($pattern, $valor)) {
            throw new Exception("Error en el campo '$nombreCampo': Por favor, utilice únicamente caracteres alfanuméricos (letras y números). Gracias.");
        }
    
        return $valor;
    }
    
    public function guardaUsuarioSti(){
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
        $hoy = date("Y-m-d H:i:s"); 
      
        if($data['contrasenia'] != $data['confirmar_contrasenia'] ){
            throw new Exception("Las contraseñas no son identicas");
        }
        if(empty($data['contrasenia']) || empty($data['confirmar_contrasenia']) ){
            throw new Exception("Los campos de contraseña son obligatorios");
        }
        if(empty($data['usuario']) ){
            throw new Exception("El campo de <strong>usuario</strong> es requerido");
        }
        if($data['id_sexo'] == 0 ){
            throw new Exception("El campo sexo es requerido");
        }
        if($data['id_nivel'] == 0 ){
            throw new Exception("El campo Nivel es requerido");
        }
        if($data['id_dependencia'] == 0){
            throw new Exception("El campo Dependencia es requerido");
        }
        if($data['id_perfil'] == 0 ){
            throw new Exception("El campo perfil es requerido");
        }
        if(empty($data['correo']) ){
            throw new Exception("El campo correo es requerido");
        }
        if(empty($data['nombre']) || 
           empty($data['primer_apellido']) || 
           empty($data['rfc']) ){
            throw new Exception("Algunos campos son requeridos");
        }
        $curp  = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['curp' => $data['curp'], 'visible' =>1]]); 
        if( !empty($curp->data) ){
            throw new Exception("El campo de <strong>CURP</strong> ya existe en la base de datos");
        }
        
        $dataInsert = [
            'id_sexo'               => (int)$data['id_sexo'],           
            'id_nivel'              => (int)$data['id_nivel'],           
            'id_dependencia'        => (int)$data['id_dependencia'],             
            'id_perfil'             => (int)$data['id_perfil'],           
            'id_padre'              => (int)$session->get('id_perfil'),           
            'usuario'               => $data['usuario'],           
            'contrasenia'           => md5($data['contrasenia']),             
            'nombre'                => $data['nombre'],           
            'primer_apellido'       => $data['primer_apellido'],           
            'segundo_apellido'      => $data['segundo_apellido'],             
            'correo'                => $data['correo'],           
            'curp'                  => $data['curp'],           
            'rfc'                   => $data['rfc'],             
            'denominacion_funcional'=> $data['denominacion_funcional'],             
            'area'                  => $data['area'],             
            'jefe_inmediato'        => $data['jefe_inmediato'],             
            'fec_nac'               => $data['fec_nac'],            
            'fec_registro'          => $hoy   
        ];     
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        
       
        $dataConfig = [
            "tabla"=>"usuario",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];

        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        
        return $this->respond($response);
    }
    public function guardaCategoria(){
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
        $hoy = date("Y-m-d H:i:s"); 
      
        if(empty($data['nombre_curso']) ){
            throw new Exception("Es requerido el Nombre del curso");
        }
        //valida que el nombre del curso y nombre corto del curso no se repitan
        if(!empty($data['nombre_curso']) ){
            $cursoDB = $this->globals->getTabla(['tabla' => 'categoria', 'where' => ['dsc_categoria'=> $data['nombre_curso'] ,'visible' => 1]]);
            if(!empty($cursoDB->data) && isset($cursoDB->data[0]->dsc_categoria) ){
                throw new Exception("Es Nombre del curso ya existe");
            }

        }
          
        $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
        $dataInsert = [
            'categoryName' => $data['nombre_curso'],                      
            'courseName' => 'Curso de Prueba',
            'startDate' => '2023-01-01',
            'endDate' => '2023-12-31' 
        ];
   
        $response = $this->globals->createCurso($dataInsert, 'crearCategoria');
      
        if($response->error){
            throw new Exception("No se puedo crear la Categoria");
        }else{
            $dataConfig = [
                "tabla"=>"categoria",
                "editar"=>false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
            $Insert = [
                'dsc_categoria'  => $response->data[0]->name,                      
                'id_moodle_categoria'      => $response->data[0]->id,                      
                'fec_reg'        => $hoy   
            ];
           $response = $this->globals->saveTabla($Insert,$dataConfig,$dataBitacora);
        }
      
        return $this->respond($response);
    }
    public function guardaUsuarioAula(){
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
        $hoy = date("Y-m-d H:i:s"); 
        $dataInsert = [
            'id'                    => $data['id'],           
            'clave_ramo'            => $data['clave_ramo'],           
            'nombre_ramo'           => $data['nombre_ramo'],           
            'abreviatura_ramo'      => $data['abreviatura_ramo'],           
            'enlace'                => $data['enlace'],   
            'fec_registro'          => $hoy   
        ];
       /*  var_dump($dataInsert);
        die(); */
     
        $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaTurno'];
        
       
        $dataConfig = [
            "tabla"=>"centro",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        return $this->respond($response);
    }
    public function showCurso($id_categoria)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $catalogos      = new Mglobal;

        $cursoDB = array('tabla' => 'categoria', 'where' => ['id_categoria'=> $id_categoria ,'visible' => 1]);
        $curso        = $catalogos->getTabla($cursoDB);
      
       
        if(empty($curso->data) ){
           echo "<center>NO HAY CURSO CON ESE ID</center>";
           die();
        }
      
       // $data['idCounter'] = (!empty($eventos->data))? 1 : 1 ;

        $data['id_categoria'] = $id_categoria;
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vVistaEventos';                
        $this->_renderView($data);

    }
    public function Evento($id_categoria)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data['id_categoria'] = $id_categoria;
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vEvento';                
        $this->_renderView($data);

    }
    public function uploadCSV()
    {
        $response = new \stdClass();
    
        // Verificar si el archivo se recibió correctamente
        if ($file = $this->request->getFile('csvFile')) {
            if ($file->getClientMimeType() !== 'text/csv' && strtolower($file->getExtension()) !== 'csv') {
                $response->error = true;
                $response->respuesta = 'El archivo debe ser de formato CSV.';
                return $this->respond($response);
            }
            $id_categoria = $this->request->getPost('id_categoria');
         
            if ($file->isValid() && !$file->hasMoved()) {
                // Asignar un nombre aleatorio y mover el archivo a la carpeta de uploads
              
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                $filePath = WRITEPATH . 'uploads/' . $newName;
            
                // Procesar el archivo CSV y enviar los datos a Node.js
                $processResponse = $this->processCSVAndSend($filePath, $id_categoria);
                // Eliminar el archivo CSV después de procesarlo
                // Configurar la respuesta en función del resultado de `processCSVAndSend`
                if ($processResponse->error) {
                    $response->error = true;
                    $response->respuesta = 'Error al procesar el CSV';
                    
                } else {
                    $response->error = false;
                    $response->respuesta = 'Archivo procesado correctamente';
                    //$response->data = $processResponse->data;
                }
            } else {
                $response->error = true;
                $response->respuesta = 'Error en la subida del archivo.';
            }
        } else {
            $response->error = true;
            $response->message = 'Archivo no recibido.';
        }
        return $this->respond($response);
        //return $this->response->setJSON($response);
    }

    public function processCSVAndSend($filePath, $id_categoria)
    {
        $response = new \stdClass();
        $data = [];
        
        if (($handle = fopen($filePath, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // Lee la primera fila como encabezado
    
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $encodedRow = array_map('utf8_encode', $row); // Codifica los valores a UTF-8
                $courseData = array_combine($header, $encodedRow); // Combina encabezado y valores
    
               // Convertir las fechas al formato `yyyy-mm-dd`
               // $courseData['startdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $courseData['startdate'])));
               // $courseData['enddate'] = date('Y-m-d', strtotime(str_replace('/', '-', $courseData['enddate'])));
    
                $data[] = $courseData;
            }
            fclose($handle);
        }
        // Enviar los datos a Node.js
        return $this->sendDataToNode($data, $id_categoria);
    }
    


    public function sendDataToNode($data, $id_categoria)
    {
        $client = \Config\Services::curlrequest();
        $session = \Config\Services::session();
        $response = new \stdClass();
     
        $catalogos      = new Mglobal;
       
        foreach($data as $key){
             $insert = [
                'fullname'   => $key['fullname'],
                'categoryid' => $id_categoria,
                'startdate'  => $key['startdate'],
                'enddate'    => $key['enddate'],
                'idnumber'   => $key['idnumber']
             ];
             $result = $catalogos->createCurso($insert, 'crearCursosDesdeCSV');
        
             if(!$result->error){
                $response->error     = false;
                $response->respuesta = 'creacion de cursos exitoso';
             }else{
                $response->error     = true;
                $response->respuesta = 'Inconsistencia en el archivo, verificar ID moodle';
             }
             
        }
    return $response;
        
    }


    
    public function crearSubCategoria()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data = $this->request->getPost();
        $catalogos      = new Mglobal;
        $insert=[
            'parentCategoryId' => $data['parent'],            
            'name'             => $data['name'],            
        ];
        $result = $catalogos->createCurso($insert, 'createSubcategory');
        return $this->respond($result); 
    }
    public function createCourse()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data = $this->request->getPost();
        $catalogos      = new Mglobal;
        $hoy = date("Y-m-d H:i:s"); 
        $insert=[
            'idCategoria' => $data['category'],            
            'courseName'        => $data['fullname'],            
            'startDate'   => $hoy,
            'endDate'     => "2025-12-12 00:00:00"

        ];
      
        $result = $catalogos->createCurso($insert, 'crearCurso');
        return $this->respond($result); 
    }
    public function getCoursesByCategoryId($id_categoria)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $catalogos = new Mglobal;
    
        $eventos = '';
        $data = [
            'categoryId' => $id_categoria
        ];
    
        $categoria = $catalogos->createCurso($data, 'getCoursesByCategoryId');
    
        if (!empty($categoria->data)) {
            // Recorre los cursos y convierte las fechas a un formato legible
            foreach ($categoria->data as &$curso) {
                if (isset($curso->startdate)) {
                    $curso->startdate_legible = date('d-m-Y', $curso->startdate);
                }
                if (isset($curso->enddate)) {
                    $curso->enddate_legible = date('d-m-Y', $curso->enddate);
                }
            }
            $eventos = $categoria->data;
        }
      
        return $this->respond($eventos);
    }
    
    public function validarCurso()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $data = $this->request->getPost();
        $catalogos      = new Mglobal;
        $insert = [
            'categoryId' => $data['categoryId']
        ];
      
        $response = $catalogos->createCurso($insert, 'getCoursesByCategoryId');
        return $this->respond($response);
       

    }
   // Método para encriptar datos
   private function encrypt($data) {
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', 'unique_salt'), 0, 16); // IV de 16 bytes
    return base64_encode(openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv) . '::' . $iv);
    }

    // Método para desencriptar datos
    private function decrypt($data) {
        $key = hash('sha256', ENCRYPTION_KEY);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, $key, 0, $iv);
    }

    // Endpoint para encriptar el ID
    public function encryptId() {
        // Obtener el evento_id de la solicitud GET
        $evento_id = $this->request->getGet('evento_id');
        
        if (empty($evento_id)) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'No se ha proporcionado el ID del evento.'
            ]);
        }
    
        // Encriptar el ID del evento
        $encryptedId = $this->encrypt($evento_id);  // Usa $this->encrypt()
    
        if ($encryptedId) {
            // Responder con el ID encriptado en formato JSON
            return $this->response->setJSON(['encryptedId' => $encryptedId]);
        } else {
            // En caso de error en la encriptación
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error al encriptar el ID del evento.'
            ]);
        }
    }
    

    // Método de Configuración que recibe el ID encriptado
    public function Configuracion() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $catalogos   = new Mglobal;
        // Obtener el evento_id encriptado desde GET y desencriptarlo
        $encryptedEventoId = $this->request->getGet('evento_id');
        
        $id_curso = $this->decrypt($encryptedEventoId);
      
        if ($id_curso === false) {
            // Manejar error de desencriptación
            echo "ID no válido o error de desencriptación.";
            return;
        }
        $data = ['courseId' => $id_curso ];
        $categoria = "";
        $quizz = $catalogos->createCurso($data, 'traerQuiz');
        $details = $catalogos->createCurso($data, 'getCourseDetailsById');
        //die( var_dump( $details ) );
        if(!empty($quizz->data)){
            $data['quizz'] = $quizz->data;
        }
        if(!empty($details->data)){
            $data['details'] = $details->data;
            $insert = [
                 'categoryId' => $details->data[0]->categoryid
            ];
            $categoria = $catalogos->createCurso($insert, 'getCoursesByCategoryId');
            $data['categoria'] = $categoria->data;
            $data['fec_inicio'] = date('d-m-Y', $categoria->data[0]->startdate); 
            $data['fec_fin'] = date('d-m-Y', $categoria->data[0]->enddate); 
        }
    
        //var_dump( $categoria->data[0]->modules );
        $data['id_curso'] = $id_curso;

        $data['scripts'] = array('agregar');
        $data['contentView'] = 'secciones/vConfiguracion';                
        $this->_renderView($data);
    }


    public function formConfigurarCurso() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $catalogos   = new Mglobal;

        // Obtener el evento_id encriptado desde GET y desencriptarlo
        $formData = $this->request->getPost();

       

        //validar que ya exista el curso 
        $cursoExiste        = $catalogos->getTabla(['tabla' => 'cursos_perfil', 'where' => ['id_curso'=> $formData['id_curso'] ,'visible' => 1, 'id_padre'   => $session->get('id_perfil') ]]);
        if(empty($cursoExiste->data) ){
           
            $insert = [
                'id_curso'   => (int)$formData['id_curso'],
                'id_padre'   => $session->get('id_perfil'),
                'fec_reg'    => date("Y-m-d H:i:s"),
                'usu_reg'    => $session->get('id_usuario')
            ]; $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/updateEventos'];
   
            $dataConfig = [
                "tabla"=>"cursos_perfil",
                "editar"=>false,
               // "idEditar"=>['id_curso_moodle'=>$formData['id_curso']]
            ];
           $result = $catalogos->saveTabla($insert,$dataConfig,$dataBitacora);
            if(!$result->error){
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
            }else{
                $response->error = true;
                $response->respuesta = 'Error al actualizar las fechas';
            }

        }
       
       
        foreach ($formData['tableData'] as $key) {
            // Accede a los valores directamente sin `$i` en el índice
            if(isset($key["id_curso"]) && $key["id_curso"] > 0 ){
                $data = [
                    'id_curso'  => $key["id_curso"],
                    'timeopen'  => strtotime($key["timeopen"]),  // Convierte a Unix timestamp
                    'timeclose' => strtotime($key["timeclose"])  // Convierte a Unix timestamp
                ];
            $result       = $catalogos->createCurso($data, 'updateQuiz'); 
                if(!$result->error){
                    $response->error = $result->error;
                    $response->respuesta = $result->respuesta;
                }else{
                    $response->error = true;
                    $response->respuesta = 'Error al actualizar las fechas';
                }
               
            }
        }

        return $this->respond($response);
    }
  


  
}