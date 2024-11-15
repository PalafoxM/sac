<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use stdClass;
use Exception;
use CodeIgniter\API\ResponseTrait;

class Principal extends BaseController {

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
   
    public function index()
    {  
       
        $session = \Config\Services::session();
        $data = array();
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vVacio';                
        $this->_renderView($data);
        
    }
    public function cursoMatriculados($id_curso = null)
    {  
       
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        if ($session->get('id_dependencia') >= 5) {
            $tabla = ['tabla' => 'vw_participante_moodleid', 'where' => ['visible' => 1, 'id_curso' => $id_curso,  'id_dependencia' =>  $session->get('id_dependencia')]];
        } else {
            $tabla = ['tabla' => 'vw_participante_moodleid', 'where' => ['visible' => 1, 'id_curso' => $id_curso]];
        }
       
        $participante = $globals->getTabla($tabla);

       
        if(empty($participante->data)){
          echo '<center>ID DEL CURSO NO TIENE PARTICIPANTES</center>';
          die();
        }
        
       $participantes = $participante->data;

        $data['scripts'] = array('principal');

        $data['participantes'] = $participantes;
        $data['contentView'] = 'secciones/vcursoMatriculados';                
        $this->_renderView($data);
        
    }
    public function MatricularCurso()
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = $this->request->getPost();
        $usuarios = [];
         
    
       

        if(!empty($data['participantes'])){
           foreach($data['participantes'] as $key){
            if ($session->get('id_dependencia') >= 5) {
                $tabla = ['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_participante' => $key,  'id_dependencia' =>  $session->get('id_dependencia')]];
            } else {
                $tabla = ['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_participante' => $key]];
            }

           $user = $globals->getTabla($tabla)->data[0];
            $usuarios[] = [
                         'id_participante' =>  $key, 
                         'curp'            =>  $user->curp, 
                         'nombre'          =>  $user->nombre, 
                         'primer_apellido' =>  $user->primer_apellido, 
                         'segundo_apellido'=>  $user->segundo_apellido, 
                         'correo'          =>  $user->correo
               ];  
           }
        }
       
        $datos = ['usuarios' =>  $usuarios, 'courseId' => $data['id_curso'] ];
        $result = $globals->createCurso($datos, 'matricularEnMoodle');
       
        if(!$result->error){
            $response->error = $result->error;
            $response->data = $result->data;
           if(!empty($result->data)){
         
              foreach($result->data->usuariosVerificados as $e ){
               // Construye el array condicionalmente
                    $whereConditions = [
                        'visible' => 1,
                        'id_curso' => $data['id_curso'],
                    ];

                    // Agrega 'userid' solo si existe en el objeto $e
                    if (isset($e->userid)) {
                        $whereConditions['userid'] = $e->userid;
                    }

                    // Ahora construimos el array final usando el array $whereConditions
                    $tabla = [
                        'tabla' => 'participante_moodleid',
                        'where' => $whereConditions,
                    ];

                $user = $globals->getTabla($tabla);
             
               if(empty($user->data)){
               
                    $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarIdMoodle'];
                    $dataConfig = ["tabla" => "participante_moodleid", "editar" => false ];
                    $dataInsert = [
                               'id_participante' => $e->id_participante, 
                               'userid'          => isset($e->userid) ? $e->userid : null, 
                               'existe'          => (int)$e->existe, 
                               'id_curso'         => $data['id_curso'],
                               'id_dependencia' =>  $session->get('id_dependencia')
                    ];
                    $resultado = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                 
                } 
               
              } 
              

           }
        }


        return $this->respond($response);
    
    }
    public function Matricular()
    {  
       
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $cursos = [];
        if($session->get('id_perfil') === 1){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1]]);
        }
        if($session->get('id_perfil') === 4){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1, 'id_padre' => 4]]);
        }
        if($session->get('id_perfil') >= 5){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1, 'id_padre' => $session->get('id_padre')]]);
        }
        if(isset($cursoPadre->data) && !empty($cursoPadre->data)){
            $id_cursos= $cursoPadre->data;
            foreach ($id_cursos as $key) {
                $data = ['courseId' => $key->id_curso];
                $details = $globals->createCurso($data, 'getCourseDetailsById');
            
                if (isset($details->data) && !empty($details->data)) {
                    $cursos[] = [
                        'id' => $details->data[0]->id,
                        'shortname' => $details->data[0]->shortname,
                        'fullname' => $details->data[0]->fullname,
                        'startdate' => date('d-m-Y', $details->data[0]->startdate),
                        'enddate' => date('d-m-Y', $details->data[0]->enddate),
                    ];
                }
            }
        }

        if ($session->get('id_dependencia') >= 5) {
            $participantes = $globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->get('id_dependencia')]]);
        } else {
            $participantes = $globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1]]);
        }

      
    
            // Add full name to each filtered $detenidos record
            foreach ($participantes->data as $d) {
                $d->nombre_completo = $d->nombre . ' ' . $d->primer_apellido . ' ' . $d->segundo_apellido;
            }
        
        //die( var_dump( $participantes ) );
     
        $data['cursos'] = $cursos;
        $data['participantes'] = (!empty($participantes->data))?$participantes->data:'';
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vMatricular';                
        $this->_renderView($data);
        
    }
    public function Participantes()
    {  
       
        $session = \Config\Services::session();

        if($session->get('id_perfil')== 3 || $session->get('id_perfil')== 4){
            header('Location:'.base_url().'index.php/Inicio');            
            die();
        }
        $catalogos        = new Mglobal;
        $dataDB           = array('tabla' => 'cat_nivel', 'where' => ['visible' => 1]);
        $dependenciaDB    = array('tabla' => 'cat_dependencia', 'where' => ['visible' => 1]);
        $perfilDB         = array('tabla' => 'cat_perfil', 'where' => ['visible' => 1]);
        $cat_nivel        = $catalogos->getTabla($dataDB);
        $cat_dependencia  = $catalogos->getTabla($dependenciaDB);
        $cat_perfil       = $catalogos->getTabla($perfilDB);
        $cat_municipio    = $catalogos->getTabla(['tabla' => 'cat_municipio', 'where' => ['visible' => 1]]);

     
         
        $data['cat_nivel']       =$cat_nivel->data;
        $data['cat_dependencia'] =$cat_dependencia->data;
        $data['cat_perfil']      =$cat_perfil->data;
        $data['cat_municipio']   =$cat_municipio->data; 
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vParticipantes';                
        $this->_renderView($data);
        
    }
    public function getDetenidos()
    {
        $session = \Config\Services::session();
        $catalogos = new Mglobal();
    
        // Fetch data based on session conditions
        if ($session->get('id_dependencia') >= 5) {
            $detenidos = $catalogos->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1, 'id_dependencia' => $session->get('id_dependencia')]]);
            $participantes = $catalogos->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->get('id_dependencia')]]);
        } else {
            $detenidos = $catalogos->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1]]);
            $participantes = $catalogos->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1]]);
        }
    
  
        $existingCurps = [];
        $existingCurpViejo = [];
    
        if (!empty($participantes->data)) {
            foreach ($participantes->data as $p) {
                $existingCurps[] = $p->curp;
                $existingCurpViejo[] = $p->curp_viejo;
            }
        }
        
 
    
        if (!empty($detenidos->data)) {
            $detenidos->data = array_filter($detenidos->data, function ($d) use ($existingCurps, $existingCurpViejo) {
           // $detenidos->data = array_filter($detenidos->data, function ($d) use ($existingCurps) {
               return !in_array($d->curp, $existingCurps) && !in_array($d->curp, $existingCurpViejo);
             //   return !in_array($d->curp, $existingCurps);
            });
    
            // Add full name to each filtered $detenidos record
            foreach ($detenidos->data as $d) {
                $d->nombre_completo = $d->nombre . ' ' . $d->primer_apellido . ' ' . $d->segundo_apellido;
            }
        }

        return $this->respond(array_values($detenidos->data)); // Reset array keys
    }
    
    public function getParticipantes()
    {  
        $session = \Config\Services::session();
        $catalogos        = new Mglobal;
        if($session->get('id_dependencia') >= 5 ){
            $participantes    = $catalogos->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->get('id_dependencia') ]]);
           }else{
             $participantes    = $catalogos->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1 ]]);
           }
        if(!empty($participantes->data)){
            foreach($participantes->data as $d  ){
                $d->nombre_completo = $d->nombre.' '.$d->primer_apellido.' '.$d->segundo_apellido;
            }

        }
        return $this->respond($participantes->data);
    }

    public function uploadCSV()
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
    
        if (isset($_FILES['fileParticipantes']) && $_FILES['fileParticipantes']['error'] == 0) {
            $filePath = $_FILES['fileParticipantes']['tmp_name'];
            
            // Lee el archivo CSV y convierte sus datos en un array
            $data = [];
        
            if (($handle = fopen($filePath, "r")) !== false) {
                $header = fgetcsv($handle, 1000, ","); // Lee la primera fila como encabezado
        
                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                    $encodedRow = array_map('utf8_encode', $row); // Codifica los valores a UTF-8
                    $courseData = array_combine($header, $encodedRow); // Combina encabezado y valores

                    $data[] = $courseData;
                }
                fclose($handle);
            }

            $columnasRequeridas = [
                'nombre', 'primer_apellido', 'segundo_apellido', 'curp', 'correo',
                'denominacion_funcional', 'nivel', 'municipio',
                 'area', 'jefe_inmediato', 'centro_gestor'
            ];
        
            // Compara las columnas requeridas con el encabezado del archivo CSV
            $columnasFaltantes = array_diff($columnasRequeridas, $header);
        
            if (!empty($columnasFaltantes)) {
                // Si faltan columnas, devolver error con los nombres de las columnas faltantes
                $response->error = true; 
                $response->respuesta = 'faltan columnas'; 
                return $this->respond($response);
            }
        
      

            $processResponse = $this->procesarDatos($data);
            if($processResponse->error){
                $response->error = true;
                $response->respuesta = $processResponse->respuesta;
                return $this->respond($response);
            }
         
            // Convertir el array a JSON (opcional)
          
           // $json_data = json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            //echo $json_data; // Para ver el JSON resultante
            //var_dump($json_data);
            
        }

     
        $response->error = false; 
        return $this->respond($response);
        //return $this->response->setJSON($response);
    }
    function validarCURP($curp) {
        // Lista de códigos de entidades válidos en México
        $response = new \stdClass();
        $response->error = true;
        $entidadesValidas = [
            'AS', 'BC', 'BS', 'CC', 'CL', 'CM', 'CS', 'CH', 'DF', 'DG', 'GT', 
            'GR', 'HG', 'JC', 'MC', 'MN', 'MS', 'NT', 'NL', 'OC', 'PL', 'QT', 
            'QR', 'SP', 'SL', 'SR', 'TC', 'TL', 'TS', 'VZ', 'YN', 'ZS'
        ];
        
        // Validación de longitud de 18 caracteres y el formato general
       
        if (strlen($curp) !== 18 ) {
            $response->respuesta = "CURP no válida por formato general";
            return false; // CURP no válida por formato general
        }
       
        // Validación de fecha de nacimiento en CURP
        $anio = intval(substr($curp, 4, 2));
        $mes = intval(substr($curp, 6, 2));
        $dia = intval(substr($curp, 8, 2));
        
        // Ajustar año para fechas de 1900 a 2099
        //$anio += ($anio < 50) ? 2000 : 1900;
        $anioCompleto = ($anio < 50) ? 2000 + $anio : 1900 + $anio;
        
        if (!checkdate($mes, $dia, $anioCompleto)) {
            $response->respuesta = "CURP no válida por fecha de nacimiento incorrecta";
            return $response; // CURP no válida por fecha de nacimiento incorrecta
        }
    
        // Validación de sexo (posición 11)
        $sexo = $curp[10];
        if ($sexo !== 'H' && $sexo !== 'M') {
          
            $response->respuesta = "Validación de sexo solo es valido H o M";
            return $response; // CURP no válida por sexo incorrecto
        }
    
        // Validación de entidad de nacimiento (posiciones 12 y 13)
        $entidad = substr($curp, 11, 2);
        if (!in_array($entidad, $entidadesValidas)) {
            $response->respuesta = "CURP no válida por entidad de nacimiento ejemplo GT";
            return $response;// CURP no válida por entidad incorrecta
        }
    
        // Validación de primeras consonantes internas en apellidos y nombre (posiciones 14, 15 y 16)
        $consonantesInternas = substr($curp, 13, 3);
        if (!preg_match("/^[B-DF-HJ-NP-TV-Z]{3}$/", $consonantesInternas)) {
            $response->respuesta = "CURP no válida por consonantes internas incorrectas del apellidos y nombre";
            return $response; // CURP no válida por consonantes internas incorrectas
        }
        $ultimosDos = substr($curp, -1);
        if (!ctype_digit($ultimosDos)) {
            $response->respuesta = "los ultimos 1 digitos tiene que ser números entero";
            return $response;; // CURP no válida por consonantes internas incorrectas

        }
         // CURP válida - calcular fecha de nacimiento y edad
       // $fechaNacimiento = new DateTime("$anioCompleto-$mes-$dia");
       $fechaNacimiento = "$anioCompleto-$mes-$dia";
        $timestampNacimiento = strtotime($fechaNacimiento);
        $timestampHoy = time();
        $edad = (int) date('Y', $timestampHoy) - (int) date('Y', $timestampNacimiento);

        // Ajuste en caso de que el cumpleaños aún no haya ocurrido en el año actual
        if (date('md', $timestampHoy) < date('md', $timestampNacimiento)) {
            $edad--;
        }
        $response->error = false;
        $response->respuesta = "CURP válida";
        $response->fecha_nacimiento = $fechaNacimiento;
        $response->edad = $edad;
        $response->sexo = $sexo;
        return $response;
    }
    public function procesarDatos($data)
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $dataClean = [];
        $dataTrash = [];
        $emailsSeen = []; // Lista para verificar correos duplicados en el CSV
        $curpSeen = []; 
    
        foreach ($data as $d) {
            if (isset($d['curp']) && !empty($d['curp'])) {
    
                // Validación de duplicados de correo en el archivo CSV
                if (in_array($d['correo'], $emailsSeen)) {
                    $response->respuesta = "Existen correos duplicados en el CSV";
                    $response->error = true;
                    return $response;
                } else {
                    $emailsSeen[] = $d['correo']; // Guardar correo para evitar duplicados en el CSV
                }
                if (in_array($d['curp'], $curpSeen)) {
                    $response->respuesta = "Existen CURP duplicados en el CSV";
                    $response->error = true;
                    return $response;
                } else {
                    $curpSeen[] = $d['curp']; // Guardar correo para evitar duplicados en el CSV
                }
    
                // Verificar CURP en la tabla 'detenidos'
            /*     $detenidosDB = $this->globals->getTabla(['tabla' => 'detenidos', 'where' => [
                    'visible' => 1,
                    'id_dependencia' => $session->get('id_dependencia'),
                    'curp' => $d['curp']
                ]]);
    
                if (!empty($detenidosDB->data)) {
                    $d['observaciones'] = 'Curp ya existe en detenidos';
                    $dataTrash[] = $d;
                    continue;
                } */
    
                // Verificar CURP y correo en la tabla 'participantes'
                $curpDB = $this->globals->getTabla(['tabla' => 'participantes', 'where' => [
                    'visible' => 1,
                    'id_dependencia' => $session->get('id_dependencia'),
                    'curp' => $d['curp']
                ]]);
                $correoDB = $this->globals->getTabla(['tabla' => 'participantes', 'where' => [
                    'visible' => 1,
                    'id_dependencia' => $session->get('id_dependencia'),
                    'correo' => $d['correo']
                ]]);
    
                if (!empty($curpDB->data)) {
                    $d['observaciones'] = 'Curp ya existe en la base de datos';
                    $dataTrash[] = $d;
                    continue;
                }
                if (!empty($correoDB->data)) {
                    $d['observaciones'] = 'Correo ya existe en la base de datos';
                    $dataTrash[] = $d;
                    continue;
                }
    
                // Validar la CURP en formato y datos
                $result = $this->validarCURP($d['curp']);
                if (is_object($result) && !$result->error) {
                    // Si es válido, añadir la fecha de nacimiento, edad y sexo al registro
                    $d['fecha_nacimiento'] = $result->fecha_nacimiento;
                    $d['edad'] = $result->edad;
                    $d['sexo'] = $result->sexo;
                    $dataClean[] = $d;
                } else {
                    $d['observaciones'] = is_object($result) ? $result->respuesta : 'Error al procesar la CURP';
                    $dataTrash[] = $d;
                }
            } else {
                // CURP vacía
                $d['observaciones'] = 'CURP vacía';
                $dataTrash[] = $d;
            }
        }
    
        // Procesar y guardar los datos limpios y descartados en la base de datos
        $this->guardarEnBaseDeDatos($dataClean, $dataTrash);
    
        // Respuesta final
        $response->error = false;
        return $response;
    }
    
    
    private function guardarEnBaseDeDatos($dataClean, $dataTrash)
    {
        $session = \Config\Services::session();
       
        if (!empty($dataTrash)) {
            foreach ($dataTrash as $c) {
                $dataInsert = [
                    'nombre'             => $c['nombre'],
                    'primer_apellido'    => $c['primer_apellido'],
                    'segundo_apellido'   => $c['segundo_apellido'],
                    'curp'               => $c['curp'],
                    'correo'             => $c['correo'],
                   // 'fec_nac'            => date("Y-m-d H:i:s", strtotime($c['fec_nac'])),
                    'centro_gestor'      => $c['centro_gestor'],
                    'jefe_inmediato'     => $c['jefe_inmediato'],
                    'area'               => $c['area'],
                    'rfc'                => substr($c['curp'], 0, 10), 
                    'observaciones'      => $c['observaciones'],
                    //'id_sexo'            => ($c['sexo'] == 'HOMBRE') ? 1 : 2,
                    'id_municipio'       => 15,
                    'id_dependencia'     => $session->get('id_dependencia'),
                    'id_dep_padre'       => $session->get('id_padre'),
                    'id_nivel'           => (int)$c['nivel'],
                    'fec_reg'            => date("Y-m-d H:i:s"),
                    'usu_reg'            => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarDetenido'];
                $dataConfig = ["tabla" => "detenidos", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }
    
        if (!empty($dataClean)) {
            foreach ($dataClean as $c) {
                $dataInsert = [
                    'nombre'             => $c['nombre'],
                    'primer_apellido'    => $c['primer_apellido'],
                    'segundo_apellido'   => $c['segundo_apellido'],
                    'curp'               => $c['curp'],
                    'correo'             => $c['correo'],
                    'fec_nac'            => $c['fecha_nacimiento'],
                    'centro_gestor'      => $c['centro_gestor'],
                    'jefe_inmediato'     => $c['jefe_inmediato'],
                    'area'               => $c['area'],
                    'rfc'                => substr($c['curp'], 0, 10),
                    'edad'               => $c['edad'],
                    'id_sexo'            => ($c['sexo'] == 'H') ? 1 : 2,
                    'id_municipio'       => 15,
                    'id_dependencia'     => $session->get('id_dependencia'),
                    'id_dep_padre'       => $session->get('id_padre'),
                    'id_nivel'           => (int)$c['nivel'],
                    'fec_reg'            => date("Y-m-d H:i:s"),
                    'usu_reg'            => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarParticipantes'];
                $dataConfig = ["tabla" => "participantes", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }
    }
    
    public function guardarParticipantes()
    {  
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
    
        // Validación de campos requeridos
        $result = $this->validarCamposRequeridos($data);
         if($result->error){
             $response->error = true;
             $response->respuesta =  $result->respuesta;
             return $this->respond($response);
         }  
        
     

        // Configuración de bitácora
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaParticipante'];

        // Verificación de unicidad para CURP y correo
  
        if($data['editar'] == 1 && $data['id_detenido'] != 0 || $data['id_participante'] ==0){
            if (!$this->verificarUnicidad('curp', $data['curp']) || !$this->verificarUnicidad('correo', $data['correo'])) {
                $response->error = true;
                $response->respuesta = !$this->verificarUnicidad('curp', $data['curp']) ? 'La CURP ya existe en la base de datos' : 'El correo ya existe en la base de datos';
                return $this->respond($response);
            }
        }
    

        $hoy = date("Y-m-d H:i:s"); 
        $dataInsert = [
            'curp'                  => $data['curp'],           
            'curp_viejo'            => $data['curp_viejo'],           
            'nombre'                => $data['nombre'],           
            'primer_apellido'       => $data['primer_apellido'],           
            'segundo_apellido'      => $data['segundo_apellido'],           
            'fec_nac'               => date("Y-m-d H:i:s", strtotime($data['fec_nac'])),   
            'rfc'                   => $data['rfc'],   
            'correo'                => $data['correo'],   
            'id_sexo'               => $data['id_sexo'],   
            'id_nivel'              => $data['id_nivel'],   
            'id_dependencia'        => $session->get('id_dependencia'),   
            'funcion'               => $data['funcion'],   
            'denominacion_funcional'=> $data['denominacion_funcional'],   
            'area'                  => $data['area'],   
            'jefe_inmediato'        => $data['jefe_inmediato'],   
            'id_municipio'          => $data['id_municipio'],   
            'centro_gestor'         => $data['centro_gestor'],   
            'correo_enlace'        => $data['correo_enlace'],   
            'id_dep_padre'          => $session->get('id_dependencia'),
            'usu_reg'               => $session->get('id_usuario'),
            'fec_reg'               => $hoy   
        ];
     

     
       //agregar nuevo
        if($data['editar'] == 0 && $data['id_participante'] == 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => false,
               // "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }
        //editar participante
        if($data['editar'] == 1 && $data['id_participante'] != 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => true,
                "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }
        //editar detenido
        if($data['editar'] == 1 && $data['id_detenido'] != 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => false,
               // "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }

       
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
      
        // Si es una edición, marcar al participante en detenidos como inactivo
        if ($data['editar'] == 1 && $data['id_detenido'] != 0) {
            $dataConfigDetenidos = ["tabla" => "detenidos", "editar" => true, "idEditar" => ['id_detenido' => $data['id_detenido']]];
            $dataDetenidos = ['visible' => 0];
            $result = $this->globals->saveTabla($dataDetenidos, $dataConfigDetenidos, $dataBitacora);
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;
        }
        // ahora insertamos en la de participante 
      

        return $this->respond($response);
    }

    // Función para verificar que los campos requeridos no estén vacíos
    private function validarCamposRequeridos($data)
    {
       
        $response = new \stdClass();
        $response->error = false;
        $response->respuesta = 'campos requeridos';
        if(empty($data['curp'])){
            $response->error = true;
            $response->respuesta = 'El campo curp es requerido';
        }
        if(empty($data['correo'])){
            $response->error = true;
            $response->respuesta = 'El campo correo es requerido';
        }
        if(empty($data['fec_nac'])){
            $response->error = true;
            $response->respuesta = 'El campo fecha de nacimiento es requerido';
        }
        if(empty($data['primer_apellido'])){
            $response->error = true;
            $response->respuesta = 'El campo primer apellido es requerido';
        }
        if(empty($data['id_municipio'])){
            $response->error = true;
            $response->respuesta = 'El campo municipio es requerido';
        }
        if(empty($data['correo_enlace'])){
            $response->error = true;
            $response->respuesta = 'El campo correo del enclace es requerido';
        }
        if(empty($data['id_nivel'])){
            $response->error = true;
            $response->respuesta = 'El campo nivel es requerido';
        }
        if(empty($data['id_sexo'])){
            $response->error = true;
            $response->respuesta = 'El campo sexo es requerido';
        }
        return $response;
    }

    // Función para verificar la unicidad de un campo en la base de datos
    private function verificarUnicidad($campo, $valor)
    {
        $session = \Config\Services::session();
        $registro = $this->globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, $campo => $valor, 'id_dependencia' => $session->get('id_dependencia'), ]]);
        return empty($registro->data);
    }

    public function eliminarDetenido()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = false;
        $response->respuesta = 'Usuario se elimino correctamente';
        // $response->error = true;
        $this->globals = new Mglobal();
  
        $id_detenido = $this->request->getPost('id_detenido');
        $id_participante = $this->request->getPost('id_participante');
        if(isset($id_detenido) && $id_detenido >= 0){
            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/eliminarDetenidos'];
            $dataConfig   =  ["tabla" => "detenidos", "editar" => true, "idEditar"=>['id_detenido'=>$id_detenido] ];
            $data = ['visible' => 0];
            
            $result = $this->globals->saveTabla($data, $dataConfig, $dataBitacora);
          
            if($result->error){
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
    
               // return $this->respond($response);
            }
        }
        if(isset($id_participante) && $id_participante >= 0){
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/eliminarDetenidos'];
        $dataConfig   =  ["tabla" => "participantes", "editar" => true, "idEditar"=>['id_participante'=>$id_participante] ];
        $data = ['visible' => 0];
        
        $result = $this->globals->saveTabla($data, $dataConfig, $dataBitacora);
      
            if($result->error){
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;

            // return $this->respond($response);
            }
        }
      
      
       
        return $this->respond($response);
       
    }
    public function updateDetenido()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error en la comunicacion al servido';
        $response->respuesta = 'Usuario se elimino correctamente';
        // $response->error = true;
        $this->globals = new Mglobal();
  
        $id_participante = $this->request->getPost('id_participante');
        $id_detenido     = $this->request->getPost('id_detenido');

        if(isset($id_detenido) && !empty($id_detenido)){
            $detenidos  = $this->globals->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1, 'id_detenido' => $id_detenido]]);
            if(isset($detenidos->data) && !empty($detenidos->data)){
              $response->error = false;
              $response->data =  $detenidos->data[0];
            }
        }
        if(isset($id_participante) && !empty($id_participante)){
            $participante  = $this->globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_participante' => $id_participante]]);
           
            if(isset($participante->data) && !empty($participante->data)){
              $response->error = false;
              $response->data =  $participante->data[0];
            }
        }
      
     
       
        return $this->respond($response);
       
    }
  
}