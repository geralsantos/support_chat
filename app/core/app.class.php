<?php /**/

abstract class App {

  var $modulo;

  var $accion;

  var $vista;

  var $id;

  var $var;

  var $off = FALSE;


  function __construct($modulo, $accion, $id=NULL) {

    $this->modulo = $modulo;
        $this->accion = $accion;

    $this->id = $id;

    $this->var = new stdClass();

    $this->vista = new Plantilla($modulo, $accion, $id);

  }


    function __destruct() {

      if(!$this->vista->reenvio){
        if (!isset($_GET["view"])) {
          $this->vista->render($this->var);
        }else{
          if ($_GET["view"]=="file_get_contents") {
            $this->vista->setView($this->var);
          }
        }
      }
    }
    function addLibreria($libreria){

      if (file_exists(APP . DS . 'libreria' . DS . $libreria . ".php")) {

        include(APP . DS . 'libreria' . DS . $libreria . ".php");

      }

    }




  }
