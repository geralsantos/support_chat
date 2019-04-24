<?php
ini_set('mysql.connect_timeout', 1000);
ini_set('default_socket_timeout', 1000); 
class acceso extends App{
    public function index(){
        if(isset($_SESSION["usuario"])){
            $this->vista->reenviar("index", "portada");
        }

        if(isset($_POST["usuario"]) and $_POST["usuario"]!="" and isset($_POST["contrasena"]) and $_POST["contrasena"]!=""){

            $modelo = new modeloAcceso();
            $usuario = $modelo->getSesion($_POST["usuario"], $_POST["contrasena"]);

            if(!empty($usuario)){
                $_SESSION["usuario"] = $usuario;
                $this->vista->reenviar("index", "portada");
            }else{
                $this->vista->MensajeAlerta("Usuario no válido.","error");
            }
        }
    }
    public function registro(){

      if(isset($_SESSION["usuario"])){
          $this->vista->reenviar("index", "portada");
      }
        if(isset($_POST["usuario"]) and $_POST["usuario"]!="" and isset($_POST["contrasena"]) and $_POST["contrasena"]!="" and isset($_POST["email"]) and $_POST["email"]!=""){
            $modelo = new modeloAcceso();
            
            $usuario = $modelo->getSesion($_POST["usuario"], $_POST["contrasena"]);
            if(!empty($usuario)){
              $this->vista->MensajeAlerta("El usuario ya existe.","error");
            }else{
                if ($this->inscripcion_pago(true)) {
                    $modelo = new modeloAcceso();

                    $modelo->registro($_POST["id_plan"],$_POST["usuario"], $_POST["email"], $_POST["contrasena"], $_POST["nombre"], $_POST["apellido"]);
                    $_SESSION["usuario"] =  $modelo->getSesion($_POST["usuario"], $_POST["contrasena"]);
                    echo json_encode(array("resultado"=>true));
                    //$this->vista->reenviar("index", "portada");
                }
            }
        }
    }
    public function getplans($callback=false,$where=array()){
        $modelo = new modeloAcceso();
        //plan
        $_where = !empty($where)?" where id=:id":"";
        $res = $modelo->executeQuery(("select * from plan ".$_where),$where);
        if ($res) {
            if ($callback) {
                return $res[0];
            }else {
                echo json_encode(array("data"=>$res,"resultado"=>true));
            }
        }
    }
    public function facebook(){
      require_once ROOT . '/vendor/autoload.php';
      $fb = new Facebook\Facebook([
        'app_id' => API_ID_FACEBOOK,
        'app_secret' => API_ID_SECRET_FACEBOOK,
        'default_graph_version' => 'v2.6',
        "persistent_data_handler"=>"session"
        ]);
      $helper = $fb->getRedirectLoginHelper();
      try {
        $accessToken = $helper->getAccessToken();
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
      if (isset($accessToken)) {
          // Logged in!
          $_SESSION['facebook_access_token'] = (string) $accessToken;

          $postURL = siteURL()."/portal-liga/acceso/registro";
        //  echo '<a href="' . $postURL . '">Post Image on Facebook!</a>';
        	$response = $fb->get('/me?locale=en_US&fields=name,email,likes', $_SESSION['facebook_access_token'] );
        	$userNode = $response->getGraphUser();
            $tipo_acceso= $_REQUEST["tipo_acceso"];
          //print_r($userNode["name"]);
          if (isset($tipo_acceso)) {
            if ($tipo_acceso=="registro_facebook") {
            $culqi_confirm= isset($_REQUEST["culqi"]);
              $this->registro_facebook($userNode["name"], $userNode["email"], $userNode["id"],$culqi_confirm);
            }else if($_REQUEST["tipo_acceso"]=="login_facebook"){
              $this->login_facebook($userNode["name"], $userNode["email"], $userNode["id"]);
            }
          }
        // Now you can redirect to another page and use the
        // access token from $_SESSION['facebook_access_token']
        //aquí va la integración de facebook
    }
  }
  public function login_facebook($name, $email, $id_facebook){
      if(isset($_SESSION["usuario"])){
          $this->vista->reenviar("index", "portada");
      }
      if(isset($id_facebook) && $id_facebook!=""){
          $modelo = new modeloAcceso();
          $usuario = $modelo->getSesionFacebook($id_facebook);
          if(!empty($usuario)){
              $_SESSION["usuario"] = $usuario;
              $this->vista->reenviar("index", "portada");
          }else{
            $this->vista->MensajeAlerta("El usuario no existe, favor de registrarse.","error");
            $this->vista->reenviar("registro", "acceso");

          }
      }
  }
    public function registro_facebook($name="", $email="", $id_facebook="",$culqi_confirm=false){

        if(isset($_SESSION["usuario"])){
            $this->vista->reenviar("index", "portada");
        }
        $name = isset($_SESSION["name_facebook"])?$_SESSION["name_facebook"]:$name;
        $email = isset($_SESSION["email_facebook"])?$_SESSION["email_facebook"]:$email;
        $id_facebook = isset($_SESSION["id_facebook"])?$_SESSION["id_facebook"]:$id_facebook;
        $culqi_confirm = isset($_REQUEST["culqi_confirm"]);
        $id_plan = isset($_REQUEST["id_plan"])?$_REQUEST["id_plan"]:"";

        if(isset($id_facebook) && $id_facebook!=""){
            $modelo = new modeloAcceso();
            $usuario = $modelo->getSesionFacebook($id_facebook);
            if(!empty($usuario)){
                $this->vista->MensajeAlerta("El usuario ya existe.","error");
                $this->vista->reenviar("registro", "acceso");
            }else{
                if ($culqi_confirm) {
                    if ($this->inscripcion_pago(true)) {
                        $modelo = new modeloAcceso();
                        $usuario = $modelo->registroFacebook($id_plan,$name, $email, $id_facebook);
                        $_SESSION["usuario"] = $modelo->getSesionFacebook($id_facebook);

                        unset($_SESSION["name_facebook"]);
                        unset($_SESSION["email_facebook"]);
                        unset($_SESSION["id_facebook"]);
                        echo json_encode(array("resultado"=>true));
                        //$this->vista->reenviar("index", "portada");
                    }
                }else{
                    $_SESSION["name_facebook"]=$name;
                    $_SESSION["email_facebook"]=$email;
                    $_SESSION["id_facebook"]=$id_facebook;
                    $_SESSION["culqi_url"] = "registro_facebook";
                    $this->vista->reenviar("registro", "acceso");
                }
                
            }
        }
    }
    public function inscripcion_pago($callback=false)
    {
        include_once APP.DS.'libreria'.DS.'Requests-Culqi/library/Requests.php';
        Requests::register_autoloader();
        include_once APP.DS.'libreria'.DS.'culqi.php';
        $culqi = new Culqi\Culqi(array('api_key' => SECRET_KEY_CULQI_PRODUCTION));
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
    public function cerrar(){
        unset($_SESSION);
        session_destroy();
        $this->vista->reenviar("index");
    }



}
