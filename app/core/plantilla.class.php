<?php /**/
class Plantilla {

    var $html = HTML;
    var $json = FALSE;
    var $modulo;
    var $accion;
    var $id;
    var $reenvio = FALSE;

    function __construct($modulo, $accion, $id = NULL) {
        $this->modulo = $modulo;
        $this->accion = $accion;
        $this->id = $id;
    }

    function getHeader() {
        if ($this->html and !$this->json and file_exists(APP . DS . 'plantilla' . DS . "header.php")) {
            if ( isset($_SESSION["usuario"] ) ){
                include(APP . DS . 'plantilla' . DS . "header.php");
            }else{
                        include(APP . DS . 'plantilla' . DS . "header_login.php");
                }
            }
            
    }

    function getFooter() {
        if ($this->html and !$this->json and file_exists(APP . DS . 'plantilla' . DS . "footer.php")) {
            include(APP . DS . 'plantilla' . DS . "footer.php");
                foreach (glob(APP . DS . 'plantilla' . DS ."templates/*.php") as $filename)
                {
                    include($filename );
                }
            include(APP . DS . 'plantilla' . DS . "end_footer.php");
        }
    }

    function getMenu($menu = 'menu') {
        if (file_exists(APP . DS . 'plantilla' . DS . $menu . ".php")) {
            include(APP . DS . 'plantilla' . DS . $menu . ".php");
        }
    }

    function getTitleHead() {
        if (isset($this->title) and $this->title != '') {
            return $this->title." | ".TITULO_DEFAULT;
        } else {
            return TITULO_DEFAULT;
        }
    }

    function getTitle() {
        if (isset($this->title) and $this->title != '') {
            return $this->title;
        } else {
            return TITULO_DEFAULT;
        }
    }

    function getSubTitle() {
        if (isset($this->subTitle) and $this->subTitle != '') {
            $tabla = '<table class="table table-vertical-middle table-striped tabla-subTitle" style=""><thead>';
            foreach ($this->subTitle AS $item => $value){
                $tabla.='<tr>
                            <td>'. $item . '</td>
                            <td>'.$value.'</td>
                        </tr>';
            }
            $tabla .= '</table></thead>';
            return $tabla;
        } else {
            return ;
        }
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setSubTitle($subTitle) {
        $this->subTitle = $subTitle;
    }
    function get_js_module($value='')
    {
      if (file_exists(PUBLICO . DS . 'js' . DS . $this->modulo . ".js")) {
          print '<link type="text/javascript" src="'.BASE.'js/' . $this->modulo . '.js" />' . "\n";
      }
    }

    function getReferencia() {
        global $CSS;
        $str = '';
        /*
        if (is_array($CSS)) {
            foreach ($CSS as $css) {
                if (file_exists(PUBLICO . DS . 'css' . DS . $css . ".css")) {
                    print '<link type="text/css" rel="stylesheet" href="'.BASE.'css/' . $css . '.css" />' . "\n";
                }
            }
        }
        */
        if (file_exists(PUBLICO . DS . 'css' . DS . $this->modulo . ".css")) {
            print '<link type="text/css" rel="stylesheet" href="'.BASE.'css/' . $this->modulo . '.css" />' . "\n";
        }
        /*
        global $JS;
        $str = '';
        if (is_array($JS)) {
            foreach ($JS as $js) {
                if (file_exists(PUBLICO . DS . 'js' . DS . $js . ".js")) {
                    print '<script type="text/javascript" src="'.BASE.'js/' . $js . '.js"></script>' . "\n";
                }
            }
        }
        if (file_exists(PUBLICO . DS . 'js' . DS . $this->modulo . ".js")) {
            print '<script type="text/javascript" src="'.BASE.'js/' . $this->modulo . '.js"></script>' . "\n";
        }
        */
    }

    function getReady() {
        global $READY;
        if (is_array($READY)) {
            foreach ($READY as $ready) {
                print $ready . "\n";
            }
        }
    }

    public function addJS($ruta) {
        global $JS;
        array_push($JS, $ruta);
    }

    public function addCSS($ruta) {
        global $CSS;
        array_push($CSS, $ruta);
    }

    public function addREADY($codigo) {
        global $READY;
        array_push($READY, $codigo);
    }

    public function url($url, $modulo = NULL){
        if(is_null($modulo)){ $modulo = $this->modulo; }
        if(URL_AMIGABLE){

            echo BASE.$modulo.DS.$url;
        }else{

            echo BASE.'index.php?modulo='.$modulo.'&accion='.$url;
        }
    }

    public function printImagen($file, $alt="", $title=""){
        echo '<img src="' . IMG . DS . $file . '" alt="'.$alt.'" title="'.$alt.'" />';
    }

    public function getImagen($file){
        return IMG . DS . $file;
    }

    public function reenviar($url, $modulo=NULL) {
        $this->reenvio = TRUE;
        if(is_null($modulo)){ $modulo = $this->modulo; }
        if(URL_AMIGABLE){
            header("Location: " . BASE . $modulo . DS . $url);
        }else{
            $url = "&accion=" . $url;
            header("Location: index.php?modulo=" . $modulo . $url);
        }
    }

    public function alerta($val, $si='win', $no='fail'){
        if($val){
            $this->alertaExito($si);
        }else{
            $this->alertaError($no);
        }
    }

    public function alertaError($text){

        $_SESSION['alerta']['tipo'] = 'danger';
        $_SESSION['alerta']['texto'] = $text;
    }

    public function alertaExito($text){
        $_SESSION['alerta']['tipo'] = 'success';
        $_SESSION['alerta']['texto'] = $text;
    }

    public function render($params = NULL) {
        if (!is_null($params)) {
            extract(get_object_vars($params));
        }
        $this->getHeader();
        if (file_exists(APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php")) {
            include(APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php");
        }else{
            die('Invalid view.'.APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php");
        }
        $this->getFooter();
    }
    public function setView($params = NULL) {
        if (!is_null($params)) {
            extract(get_object_vars($params));
        }
        if (file_exists(APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php")) {
            include(APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php");
        }else{
            die('Invalid view.'.APP . DS . 'modulo' . DS . $this->modulo . DS . "vista." . $this->modulo . '.' . $this->accion . ".php");
        }
    }
/*
    public function MensajeAletaExito($text){
        $_SESSION['mensaje_alerta_exito']['tipo'] = 'success';
        $_SESSION['mensaje_alerta_exito']['texto'] = $text;
    }

    public function MensajeAletaError($text){
        $_SESSION['mensaje_alerta_error']['tipo'] = 'success';
        $_SESSION['mensaje_alerta_error']['texto'] = $text;
    }
*/
    public function MensajeAlerta( $text, $tipo ){

        if( $tipo == 'exito' ){
            $_SESSION['mensaje_alerta']['tipo'] = 'exito';
            $_SESSION['mensaje_alerta']['texto'] = $text;
        }else if ( $tipo == 'error' ){
            $_SESSION['mensaje_alerta']['tipo'] = 'error';
            $_SESSION['mensaje_alerta']['texto'] = $text;
        }

    }
    public function reenviar2($url, $modulo=NULL,$parametro) {
      $this->reenvio = TRUE;
      if(is_null($modulo)){ $modulo = $this->modulo; }
      if(URL_AMIGABLE){
        echo BASE . $modulo . DS . $url . $parametro;
      }else{
        $url = "&accion=" . $url;
        echo BASE.'index.php?modulo='.$modulo.'&accion='.$url;
      }
    }

}
