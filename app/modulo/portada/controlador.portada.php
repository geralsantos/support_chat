<?php
date_default_timezone_set('America/Lima');
ini_set('max_execution_time',0);
ini_set('memory_limit', '300M');
ini_set('display_errors', 'Off');
error_reporting();
class portada extends App{
    public function index(){
      if(!isset($_SESSION["usuario"])){
          $this->vista->reenviar("index", "acceso");
      }
      $this->vista->setTitle("Inicio");
    }
    public function cerrar(){
      unset($_SESSION);
      session_destroy();
      $this->vista->reenviar("index");
    }
    public function list_modulos()
    {
      $modelo = new modeloPortada();
      $modulos = "SELECT * FROM lgs_modules WHERE state_module = 1";
      $modulos = $modelo->executeQuery( $modulos );
      $tree = $this->buildTree($modulos);
      //$treeHtml = $this->buildTreeHtml($tree);
      echo json_encode($tree);
        //return $tree;
    }
    public function buildTree($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
    public function buildTreeHtml($elements, $opt="")
    {
        $branch = array();
        $li = '';
       
        foreach ($elements as $element) {
            $li = $li . (isset($element['children']) ? ('
            <v-list-group '.($opt=="childs"?' no-action sub-group ':' prepend-icon="'.$element["icon_module"].'" ').' value="true" >
            <template v-slot:activator>
              <v-list-tile>
                <v-list-tile-title>'.$element['name_module'].'</v-list-tile-title>
              </v-list-tile>
            </template> '. $this->buildTreeHtml($element['children'], 'childs').' </v-list-group>') :
                            ('<v-list-tile href="#'.$element["url_module"].'">
                            <v-list-tile-action>
                              <v-icon>' . $element["icon_module"] . '</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-title>' . $element['name_module']) .'</v-list-tile-title>
                          </v-list-tile>');
        }
        return $li;
    }
    public function is_in_array($array, $key, $key_value){
      $within_array = false;
      foreach( $array as $k=>$v ){
        if( is_array($v) ){
            $within_array = $this->is_in_array($v, $key, $key_value);
            if( $within_array ){
                break;
            }
        } else {
                if( $v == $key_value && $k == $key ){
                        $within_array = true;
                        break;
                }
        }
      }
      return $within_array;
    }
    function removeElementWithValue($array, $key, $value){
      $new_array = array();
      foreach($array as $subKey => $subArray){
          if($subArray[$key] == $value){
                unset($array[$subKey]);
          }else{
            $new_array[]=$subArray;
          }
      }
      return $new_array;
    } 
    public function inscripcion_pago($callback=false){
        include_once APP.DS.'libreria'.DS.'Requests-Culqi/library/Requests.php';
        Requests::register_autoloader();
        include_once APP.DS.'libreria'.DS.'culqi.php';
        $culqi = new Culqi\Culqi(array('api_key' => SECRET_KEY_CULQI_TEST));
        // Creamos Cargo a una tarjeta
        $id_plan=$_POST["id_plan"];
        $plan_detail = $this->getplans(true,array("id"=>$id_plan));
        $email_token=$_POST["email_token"];
        $token=$_POST["token"];
        $data = array(
            "amount" => ($plan_detail["precio"]."00"),
            "capture" => true,
            "currency_code" => "PEN",
            "description" => $plan_detail["nombre"],
            "email" => $email_token,
            "installments" => 0,
            "source_id" => $token,
        );
        /*
            "antifraud_details" => array(
                "address_city" => "LIMA",
                "country_code" => "PE",
                "first_name" => $nombre,
                "last_name" => $apellido,
            ),*/
        $charge = $culqi->Charges->create($data);
        /*[type] => venta_exitosa
            [code] => AUT0000
            [merchant_message] => La operación de venta ha sido autorizada exitosamente
            [user_message] => Su compra ha sido exitosa.*/
        if ($charge) {
            $response = json_decode(json_encode($charge),true)["outcome"];
            if ($callback) {
                return $response;
            }else {
                echo json_encode(array("data"=>$response,"resultado"=>true));
            }
        }else{
            echo json_encode(array("resultado"=>false));
        }

    } 
}
