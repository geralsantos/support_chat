<?php
date_default_timezone_set('America/Lima');

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
    public function list_modulos(){
      $modelo = new modeloPortada();

      $usuario = $modelo->getSesion($_SESSION["usuario"][0]["id"]);
      $_SESSION["usuario"] = $usuario;
      if( $_SESSION["usuario"][0]["activo"]==1){
        $modulos = "SELECT * FROM modulos WHERE estado = 1";
        $modulos = $modelo->executeQuery( $modulos );
        $tree = $this->buildTree($modulos);
        $treeHtml = $this->buildTreeHtml($tree);
        $tree2 = $this->buildTree($modulos);
        $treeHtml2 = $this->buildTreeHtml_2($tree2);

        $cabecera = $this->cabeceraPrincipalHtml();
      }else{
        $treeHtml = '';
        $cabecera ='';
        $treeHtml2 ='';
      }

      echo json_encode(array("resultado"=>true, "menu"=>$treeHtml, "menu_mobile"=>$treeHtml2, "cabecera"=>$cabecera));

    }
    public function cabeceraPrincipalHtml() {
      $temporada_numero = ($_SESSION["usuario"][0]["temporada_numero"]=='')?'': ' TEMPORADA N° '.$_SESSION["usuario"][0]["temporada_numero"];
      $cabecera = '<h2>PANEL ADMINISTRATIVO </h2>
      <p>LIGA ACTUAL: ' .$_SESSION["usuario"][0]["liga"] . '</p>
      <p>ESTADO: '. $_SESSION["usuario"][0]["estado_liga"]. $temporada_numero.'</p>
      <p>EQUIPO: '. $_SESSION["usuario"][0]["equipo"].'</p>';

      if($_SESSION["usuario"][0]["is_admin"]==1 && $_SESSION["usuario"][0]["temporada_liga_estado"]==2 ){
        $cabecera .= '<p class="rojo">Tienes que generar los partidos</p>';
      }
      if($_SESSION["usuario"][0]["solicitudes"]!='' ){
        $cabecera .= '<p class="rojo">'.$_SESSION["usuario"][0]["solicitudes"].'</p>';
      }

      return $cabecera;
    }

    public function buildTree($elements, $parentId = 0) {
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
    public function buildTreeHtml($elements,$opt=""){
      $branch = array();
      $li = '';
      foreach ($elements as $element){


          if(isset($element['children'])){


            if($element['admin']!=1){
              $li = $li  .  '<li >
              <a class="has-arrow" href="#" aria-expanded="false">
              <i class="' . $element["icon"] . '"></i> <span class="mini-click-non">' . $element['nombre'] .'</span>
              </a>
                <ul class="submenu-angle submenu-angle-inactive" style="display:none;" aria-expanded="false">
                    '. $this->buildTreeHtml($element['children'],'childs').'
                </ul>
                </li> ';
            }else{
              if($_SESSION["usuario"][0]["is_admin"] == $element['admin']){
                $li = $li  .  '<li >
                <a class="has-arrow" href="#" aria-expanded="false">
                <i class="' . $element["icon"] . '"></i> <span class="mini-click-non">' . $element['nombre'] .'</span>
                </a>
                  <ul class="submenu-angle submenu-angle-inactive" style="display:none;" aria-expanded="false">
                      '. $this->buildTreeHtml($element['children'],'childs').'
                  </ul>
                  </li> ';
              }
            }

          }else{
            if($element['admin']!=1){

              $li = $li  . '<li data-url="'.$element['url'].'">
                  <a title="Inbox" href="#'.$element['url'].'"><span class="mini-sub-pro">'.$element['nombre'].'</span>
                  </a>
                  </li>';
            }else{
                if($_SESSION["usuario"][0]["is_admin"] == $element['admin']){
                  $li = $li  . '<li data-url="'.$element['url'].'">
                  <a title="Inbox" href="#'.$element['url'].'"><span class="mini-sub-pro">'.$element['nombre'].'</span>
                  </a>
                  </li>';
                }
              }


          }

      }
      return $li;
    }
    public function buildTreeHtml_2($elements,$opt=""){
      $branch = array();
      $li = '';
      foreach ($elements as $element){


          if(isset($element['children'])){


            if($element['admin']!=1){
              $li = $li  .  '<li >
              <a data-toggle="collapse" data-target="#' . $element['parent_id'] .'" href="#" >' . $element['nombre'] .'</a>
                <ul id="' . $element['parent_id'] .'" class="collapse dropdown-header-top">
                    '. $this->buildTreeHtml($element['children'],'childs').'
                </ul>
                </li> ';
            }else{
              if($_SESSION["usuario"][0]["is_admin"] == $element['admin']){
                $li = $li  . '<li >
                <a data-toggle="collapse" data-target="#' . $element['parent_id'] .'" href="#" >' . $element['nombre'] .'</a>
                  <ul id="' . $element['parent_id'] .'" class="collapse dropdown-header-top">
                      '. $this->buildTreeHtml($element['children'],'childs').'
                  </ul>
                  </li> ';
              }
            }

          }else{
            if($element['admin']!=1){

              $li = $li  . '<li><a  href="#'.$element['url'].'">'.$element['nombre'].'</li>';

            }else{
                if($_SESSION["usuario"][0]["is_admin"] == $element['admin']){
                  $li = $li  . '<li><a  href="#'.$element['url'].'">'.$element['nombre'].'</li>';
                }
              }


          }

      }
      return $li;
    }

    public function crear_liga(){

      $modelo = new modeloPortada();
      if( $_POST['tabla'] && $_POST['insert_equipo'] && $_POST['insert_liga'] ){
        return $modelo->crear_liga( $_POST['tabla'],  $_POST['insert_equipo'],  $_POST['insert_liga']);
      }

    }

    public function mostrar_formulario_crear_liga(){

      $modelo = new modeloPortada();
      return $modelo->mostrar_formulario_crear_liga();
    }

    public function mostrar_formulario_unir_liga(){

      $modelo = new modeloPortada();
      return $modelo->mostrar_formulario_unir_liga();
    }

    public function unirse_liga(){

      $modelo = new modeloPortada();
      if($_POST['equipo'] && $_POST['liga'] ){
        return $modelo->unirse_liga($_POST['equipo'], $_POST['liga'] );
      }
    }

    public function cargar_teams(){

      $modelo = new modeloPortada();
      $query = "SELECT tea.* FROM teams tea where tea.overall_team < 72 AND tea.id not in ((select equi.teams_id from equipos equi WHERE equi.liga_id = ".$_POST["liga_id"]." AND equi.teams_id <> '')) ORDER BY tea.nombre ASC";
      $res = $modelo->executeQuery($query);

      if($res){
        echo json_encode(array("resultado"=>true, "teams"=>$res));
      }
    }
    public function cargar_jugadores(){
      $modelo = new modeloPortada();
      $res = $modelo->executeQuery("SELECT * FROM jugadores WHERE  estado =1 AND teams_id = ". $_POST["teams_id"]);
      if($res){
        echo json_encode(array("resultado"=>true, "jugadores"=>$res));
      }
    }
    public function cargar_jugadores_subastas($callback=false){

      $modelo = new modeloPortada();
      $idusuario=$_SESSION["usuario"][0]["id"];
      $subastas_ligas = isset($_POST["subastas_liga"])? " lig.usuario_id='$idusuario' ": " lp.usuario_id='$idusuario' " ; //para subastas-liga.php
      $iniciar_subasta = isset($_POST["liga_id"])?" and lp.liga_id='".$_POST['liga_id']."'":"";
      $case = "(CASE js.estado WHEN 0 THEN 'Finalizado' WHEN 1 THEN 'Por Iniciar' WHEN 2 THEN 'Entrar' END) as estado_subasta,(CASE js.estado WHEN 0 THEN '#6c757d' WHEN 1 THEN '#dc3545' WHEN 2 THEN '#28a745' END) as color_estado_subasta";
      $groupby="";
      if (isset($_POST["subastas_liga"])) {
        $case = "(CASE js.estado WHEN 1 THEN 'Iniciar Subasta' WHEN 2 THEN 'Subasta Iniciada' END) as estado_subasta,(CASE js.estado WHEN 1 THEN '#dc3545' WHEN 2 THEN '#28a745' END) as color_estado_subasta";
        $groupby = "group by lp.liga_id";//para subastas-liga.php
      }
      $res = $modelo->executeQuery("SELECT jug.*,$case, lp.id as liga_participantes_id, lp.liga_id, usu.id as usu_id, usu.nombre as usu_nombre,usu.apellido as usu_apellido, usu.id_fb, usu.correo as usu_correo, usu.usuario as usu_usuario, usu.clave, js.jugadores_id, jugval.valor as precio_base_jugador FROM  jugadores_subastas js
        inner join liga_participantes lp on(js.liga_participantes_id=lp.id)
        inner join liga lig on(lp.liga_id=lig.id)
        inner join temporada_liga temp_lig on(temp_lig.liga_id=lig.id)
        inner join jugadores jug on (js.jugadores_id=jug.id)
        inner join usuarios usu on (usu.id=lp.usuario_id)
        left join jugadores_valor jugval on (jug.id=jugval.id_jugador)
        where $subastas_ligas $iniciar_subasta and temp_lig.estado=4 and lig.estado=1 and js.estado in (1,2) and lp.estado =1 $groupby");

      if($res)
      {
        if ($callback)
        {
          return json_encode(array("resultado"=>true, "jugadores"=>$res));
        }else{
          echo json_encode(array("resultado"=>true, "jugadores"=>$res));
        }
      }
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
    public function iniciar_subasta(){
      try{
        $modelo = new modeloPortada();

        $modelo->beginTransaction();
        $liga_id = $_POST["liga_id"];
        $jugador_id = $_POST["jugador_id"];
        $precio_base_jugador = $_POST["precio_base_jugador"];
        $fecha = date("Y-m-d H:i:s");
        $Hora = Time() + (60 * 3);
        $fecha_termino = date('Y-m-d H:i:s',$Hora); // + 3 min
        $res = $modelo->executeQuery("SELECT * FROM subastas WHERE  liga_id =:liga_id AND estado=1 ",array("liga_id"=>$liga_id));
        if (!$res)
        { //si aún no se ha dado inicio a la subasta antes
          $datos_subasta = json_decode($this->cargar_jugadores_subastas(true),true)["jugadores"][0];//se carga a todos los jugadores en subasta de la liga activa y que pertenece el usuario
          $datos_subasta["data_jugadores"] = array(); //inicializamos el array de usuarios en la subasta a vacío
          $datos_subasta = json_encode($datos_subasta);

          $insert = $modelo->insertData("subastas",array("liga_id"=>$liga_id,"jugador_id"=>$jugador_id, "json_usuarios"=>$datos_subasta,"fecha_termino"=>$fecha_termino, "fecha_creacion"=>$fecha,"usuario_creacion"=>$_SESSION["usuario"][0]["id"],"usuario_edicion"=>$_SESSION["usuario"][0]["id"],"precio"=>$precio_base_jugador));

          if ($insert)
          {
            $update = $modelo->updateDataAll("jugadores_subastas", array("estado"=>2,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_participantes_id"=>array("liga_participantes_id","IN",("SELECT lp.id as liga_participantes_id FROM liga_participantes lp where lp.liga_id='$liga_id' and lp.estado=1")),"jugadores_id"=>array("jugadores_id",$jugador_id)));

            $finalizar_periodo_compras = $modelo->updateDataAll("temporada_liga", array("estado"=>4), array("liga_id"=>$liga_id));
            echo json_encode(array("resultado"=>true,"mensaje"=>"La subasta ha sido iniciada con éxito!."));
          }else{
            echo json_encode(array("resultado"=>false,"mensaje"=>"No se ha podido iniciar la subasta"));
          }
        }else {
          echo json_encode(array("resultado"=>false,"mensaje"=>"Hay subastas en cola, debe esperar a que termine"));
        }
        $modelo->commit();
      }catch(Exception $e) {
        $modelo->rollback();
        echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>"Error en registro", "accion"=>"warning"));
      }
    }
    public function cargar_ligas_usuario($callback=false){

      $modelo = new modeloPortada();
      $idusuario=$_SESSION["usuario"][0]["id"];
      $sql ="SELECT lig.nombre as nombre_liga, lig.id as liga_id  FROM liga_participantes lp
      inner join liga lig on (lp.liga_id=lig.id)
      inner join usuarios usu on (lp.usuario_id=usu.id)
      where lp.usuario_id='$idusuario' and lig.usuario_id='$idusuario' and lp.estado=1";

      $res = $modelo->executeQuery($sql);

      if($res){
        if ($callback) {
          return json_encode(array("resultado"=>true, "ligas"=>$res));
        }else{
          echo json_encode(array("resultado"=>true, "ligas"=>$res));
        }
      }
    }
    public function unirse_subasta(){

      $modelo = new modeloPortada();
      $liga_id = $_REQUEST["liga_id"];
      $jugador_id = $_REQUEST["jugador_id"];
      $precio_base_jugador = $_REQUEST["precio_base_jugador"];

      //obtener liga participante del usuario logueado
      //$sql = "SELECT * FROM liga_participantes lp WHERE lp.usuario_id=".$_SESSION["usuario"][0]["id"]." and lp.liga_id=".$liga_id."";
      $liga_participantes = $modelo->selectRowData("liga_participantes","*",array("usuario_id"=>$_SESSION["usuario"][0]["id"],"liga_id"=>$liga_id));

      //obtener último precio subastado por jugador en la subasta activa que se quiere unir el usuario logueado
      //$sql = "SELECT * FROM subastas su WHERE su.liga_id=".$liga_id." and su.jugador_id=".$jugador_id."";
      $subasta = $modelo->selectRowData("subastas","*",array("liga_id"=>$liga_id,"jugador_id"=>$jugador_id));

      //verificar saldo
      $saldo_disponible = $modelo->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$liga_participantes["id"]));

      if (floatval($saldo_disponible["saldo"]) < floatval($subasta["precio"])) {
        echo json_encode(array("resultado"=>false,"mensaje"=>("No haz podido unirte a la subasta porque no cuentas con saldo suficiente, necesitas al menos: ".floatval($subasta["precio"])." de saldo." ) ) );
        return false;
      }

      $res = $modelo->executeQuery("SELECT * FROM subastas WHERE  liga_id =:liga_id AND jugador_id =:jugador_id AND estado=1",array("liga_id"=>$_POST["liga_id"],"jugador_id"=>$_POST["jugador_id"]));
      if ($res) { //si la subasta sigue activa
        $data_mysql = json_decode($res[0]["json_usuarios"],true);
        $data_jugadores = $data_mysql["data_jugadores"];
        if (!$this->is_in_array($data_jugadores,"usu_id",$_SESSION["usuario"][0]["id"]))
        {//si el usuario que quiere ingresar sea su primera vez
          $data_mysql["data_jugadores"][] = array("usu_id"=>$_SESSION["usuario"][0]["id"],"nombre"=>$_SESSION["usuario"][0]["nombre"],"apellido"=>$_SESSION["usuario"][0]["apellido"],"id_fb"=>$_SESSION["usuario"][0]["id_fb"],"correo"=>$_SESSION["usuario"][0]["correo"],"usuario"=>$_SESSION["usuario"][0]["usuario"],"clave"=>$_SESSION["usuario"][0]["clave"]);
          $update = $modelo->updateData("subastas", array("json_usuarios"=>json_encode($data_mysql),"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$_POST["liga_id"],"jugador_id"=>$_POST["jugador_id"]));
          if ($update) { //agregando al usuario
            echo json_encode(array("resultado"=>true));
            return true;
          }else{
            echo json_encode(array("resultado"=>false,"mensaje"=>"No haz podido unirte a la subasta"));
            return false;
          }
        }else{
          echo json_encode(array("resultado"=>true));
          return true;
        }
      }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"La subasta no está activa"));
        return false;
      }
    }
    public function cargar_datos_subasta(){
      $modelo = new modeloPortada();
      //$this->inciar_subasta();
      $res = $modelo->executeQuery("SELECT sub.*,usu.id as usu_id, usu.nombre as usu_nombre,usu.apellido as usu_apellido, usu.id_fb, usu.correo as usu_correo, usu.usuario as usu_usuario, usu.clave, ((TIMESTAMPDIFF(SECOND, now(), sub.fecha_termino))) as tiempo_restante,(select usuario from usuarios where id=sub.usuario_ganador) as nombre_usuario_ganador FROM subastas sub
      inner join liga_participantes lp on(sub.liga_id=lp.liga_id)
      inner join usuarios usu on (usu.id=lp.usuario_id)
      WHERE lp.usuario_id =:usuario_id
      AND lp.estado=1 AND sub.estado=1 ",array("usuario_id"=>$_POST["usuario_id"]));
      if ($res)
      {//si el usuario tiene una subasta hay subasta en la liga
        //foreach ($res as $key => $value)
        //{

          $mysql = $res[0];
          /*if ($mysql["fecha_termino"]>=$mysql["fecha_creacion"] && $mysql["estado"]==1)
          {
            $update = $modelo->updateData("subastas", array("estado"=>0), array("id"=>$mysql["id"]));
          }*/
          $data_jugadores = json_decode($mysql["json_usuarios"],true)["data_jugadores"];
          if ($this->is_in_array($data_jugadores,"usu_id",$_POST["usuario_id"]))
          {
          //si el usuario ya pertenece a la subasta
            echo json_encode(array("resultado"=>true,"datos_subasta"=>$mysql));
          }else {
          //si el usuario recién está entrando a la subasta
            echo json_encode(array("resultado"=>false,"datos_subasta"=>$mysql));
          }
      }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"No existen subastas en tu liga por el momento."));
      }
    }
    public function subasta_pujar(){

      $modelo = new modeloPortada();
      $subasta_id = $_POST["subasta_id"];
      $jugador_id = $_POST["jugador_id"]; //no se usa por ahora
      $liga_id = $_REQUEST["liga_id"];
      $monto = $_POST["monto"];
      $usuario_id = $_POST["usuario_id"];
      $nombre_usuario = $_POST["nombre_usuario"];

      $res = $modelo->executeQuery("SELECT * FROM subastas WHERE id = :subasta_id AND estado=1",array("subasta_id"=>$subasta_id) );
      if ($res)//si la subasta sigue activa
      {
        //obtener liga participante del usuario logueado
        //$sql = "SELECT * FROM liga_participantes lp WHERE lp.usuario_id=".$_SESSION["usuario"][0]["id"]." and lp.liga_id=".$liga_id."";
        $liga_participantes = $modelo->selectRowData("liga_participantes","*",array("usuario_id"=>$_SESSION["usuario"][0]["id"],"liga_id"=>$liga_id));

        //obtener último precio subastado por jugador en la subasta activa que se quiere unir el usuario logueado
        //$sql = "SELECT * FROM subastas su WHERE su.id=".$subasta_id."";
        //$subasta = $modelo->selectRowData("subastas","*",array("id"=>$subasta_id));

        //verificar saldo
        $saldo_disponible = $modelo->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$liga_participantes["id"]));

        if (floatval($saldo_disponible["saldo"]) < floatval($monto)) {
          echo json_encode(array("resultado"=>false,"mensaje"=>("No puedes ofertar porque no cuentas con saldo suficiente, necesitas al menos: ".floatval($monto)." de saldo." ) ) );
          return false;
        }

        $subasta = $res[0];
        $fecha_actual = date('Y-m-d H:i:s');
        $Hora = strtotime($fecha_actual) + (60 * 3);
        $tiempo_restante = date('Y-m-d H:i:s',$Hora); // + 3 min
        $update = $modelo->updateData("subastas", array("precio"=>$monto,"usuario_ganador"=>$usuario_id,"fecha_termino"=>$tiempo_restante,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$subasta_id) );
        if ($update) { //actualizando los datos
          echo json_encode(array("resultado"=>true,"data_subasta"=>array("monto"=>$monto,"usuario_ganador"=>$usuario_id,"tiempo_restante"=>180, "liga_id"=>$subasta["liga_id"], "jugadores_id"=>$subasta["jugador_id"],"nombre_usuario"=>$nombre_usuario) ) );
        }else{
          echo json_encode(array("resultado"=>false,"mensaje"=>"No se ha podido subir tu monto en la subasta"));
        }
      }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"La subasta no está activa"));
      }
    }
    public function anunciar_ganador_subasta(){

      $modelo = new modeloPortada();
      //$this->iniciar_subasta();
      $res = $modelo->executeQuery("SELECT sub.*,IFNULL(usu.nombre,usu.usuario,usu.nombre) as usuario_nombre FROM subastas sub INNER JOIN usuarios usu on (usu.id=sub.usuario_ganador) WHERE  sub.liga_id =:liga_id AND sub.jugador_id =:jugador_id AND estado=1",array("liga_id"=>$_POST["liga_id"],"jugador_id"=>$_POST["jugador_id"]));
      if ($res) { //si la subasta sigue activa
        $datos_subasta = $res[0];
        echo json_encode(array("resultado"=>true,"datos_subasta"=>$datos_subasta));
      }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"No existe la subasta"));
      }
    }
    public function iniciar_periodo_subasta(){

      try{
        $modelo = new modeloPortada();

            $modelo->beginTransaction();
        $idusuario=$_SESSION["usuario"][0]["id"];

        $res = $modelo->executeQuery("SELECT count(*) as cantidad,js.id as jugadores_subastas_id,temp_lig.id as temporada_liga,jugval.valor as monto_jugador, lp.id as liga_participantes_id, jug.id as jugadores_id, lig.id as liga_id,usu.id as usuario_id FROM  jugadores_subastas js
        inner join liga_participantes lp on(js.liga_participantes_id=lp.id)
        inner join liga lig on(lp.liga_id=lig.id)
        inner join temporada_liga temp_lig on(temp_lig.liga_id=lig.id)
        inner join jugadores jug on (js.jugadores_id=jug.id)
        inner join usuarios usu on (usu.id=lp.usuario_id)
        left join jugadores_valor jugval on (jug.id=jugval.id_jugador)
        where lig.usuario_id='$idusuario' and lig.estado=1 and js.estado in (1,2) and lp.estado =1 group by js.jugadores_id");

        if ($res)
        { //si hay compras
          $columns = ["jugadores_id","fecha_creacion","usuario_creacion","usuario_edicion","monto","temporada_liga_id","liga_participantes_id"];
          $values=[];
          $where_ids=[];
          $jugadores_subastas=[];
          $liga_id="";
          foreach ($res as $key => $jugadores)
          {
            if ($jugadores["cantidad"]==1)
            {
              $where_ids[] = $jugadores["jugadores_subastas_id"];
              $values[] = "(".$jugadores["jugadores_id"].",now(),".$idusuario.",".$idusuario.",".$jugadores["monto_jugador"].",".$jugadores["temporada_liga"].",".$jugadores["liga_participantes_id"].")";
            }
            if ($jugadores["cantidad"]>=2)
            {
              $jugadores_subastas[] = $jugadores["jugadores_subastas_id"];
            }
          }
          if ($values)
          {
            $modelo->insertDataMasivo("compras",$columns, $values);
            $modelo->deleteDataMasivo("jugadores_subastas", array("id"=>array("id","IN",(implode(',',$where_ids)) ) ) );
          }
          if ($jugadores_subastas)
          {
            $res = $modelo->executeQuery("SELECT id as liga_id FROM  liga
              where usuario_id='$idusuario' and estado=1");

            $modelo->updateData("temporada_liga", array("estado"=>4,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$res[0]["liga_id"]) );
            echo json_encode(array("resultado"=>true,"mensaje"=>"El período de subasta ha sido iniciada!."));
          }else{
            echo json_encode(array("resultado"=>false,"mensaje"=>"No hay jugadores en subasta."));
          }
        }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"No hay jugadores en subasta"));
        }
            $modelo->commit();
      }catch(Exception $e) {
        $modelo->rollback();
        echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>"Error en registro", "accion"=>"warning"));
      }

    }
    public function guardar_jugadores(){

      $modelo = new modeloPortada();

      if($_POST['values'] && $_POST["where"] && $_POST["liga_participante"] && $_POST["liga"] && $_POST["jugadores"]  ){
        return $modelo->guardar_jugadores($_POST['values'], $_POST['where'], $_POST["liga_participante"], $_POST["liga"], $_POST["jugadores"]  );
      }
    }
    public function kickoff_paso_2(){

      if($_POST['refuerzo_1'] && $_POST["refuerzo_2"] && $_POST["jugador_estrella"] && $_POST["equipo"] && $_POST["liga"] && $_POST["liga_participante"] && $_POST["jugadores"]){
        $modelo = new modeloPortada();
        return $modelo->kickoff_paso_2($_POST['refuerzo_1'], $_POST['refuerzo_2'], $_POST['jugador_estrella'], $_POST['equipo'], $_POST['liga'], $_POST['liga_participante'], $_POST["jugadores"]);
      }
    }
    public function cargar_id_equipo(){

      $modelo = new modeloPortada();
      $res = $modelo->selectRowData('liga_participantes','*', array("usuario_creacion"=>$_SESSION["usuario"][0]["id"], "estado"=>1) );

      if($res){
        $liga_participante = $res["id"];
        $liga_id = $res["liga_id"];
        $equipos = $modelo->selectRowData('equipos','count(*) as equipos_cantidad', array("liga_id"=>$liga_id) );

        $res = $modelo->selectRowData('equipos','*', array("liga_participantes_id"=>$res["id"]) );
        if($res){
          $liga_sql = $modelo->selectRowData('liga','*', array("id"=>$liga_id) );
          if($liga_sql){
            echo json_encode(array("resultado"=>true, "equipo"=>$res, "id_equipo"=>$res["id"],"equipos_cantidad"=>$equipos["equipos_cantidad"], "liga"=>$liga_sql, "liga_participante_id"=> $liga_participante, "liga_id"=>$liga_id));
          }else{
            echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
          }
        }


      }else{
        echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
      }
    }
    public function cargar_refuerzos(){

      $jugadores = json_decode($_POST["jugadores_seleccionados"]);

      $valores = '(';
      foreach ($jugadores as $item) {
        $valores .= $item . ',';
      }

      $valores = substr($valores, 0, -1);
      $valores = $valores . ')';
      $modelo = new modeloPortada();

      $res = $modelo->executeQuery("SELECT ju.* FROM jugadores ju WHERE ju.id NOT IN ". $valores ." AND ju.overall <= 81 AND ju.estado =1 AND
      ju.id not in ((select plan.jugadores_id from plantilla_jugadores plan WHERE plan.liga_id = ".$_POST["liga_id"]." AND plan.jugadores_id <> '')) ORDER BY ju.nombre ASC");

      if($res){
        echo json_encode(array("resultado"=>true, "jugadores"=>$res));
      }
    }
    public function cargar_jugador_estrella(){
      $jugadores = json_decode($_POST["jugadores_seleccionados"]);
      $valores = '(';
      foreach ($jugadores as $item) {
        $valores .= $item . ',';
      }

      $valores = substr($valores, 0, -1);
      $valores = $valores . ')';
      $modelo = new modeloPortada();

      $res = $modelo->executeQuery("SELECT ju.* FROM jugadores ju WHERE ju.id NOT IN ". $valores ." AND ju.overall >= 84 AND ju.overall <= 85 AND ju.estado =1 AND
      ju.id not in ((select plan.jugadores_id from plantilla_jugadores plan WHERE plan.liga_id = ".$_POST["liga_id"]." AND plan.jugadores_id <> '')) ORDER BY ju.nombre ASC");

      if($res){
        echo json_encode(array("resultado"=>true, "jugadores"=>$res));
      }
    }
    public function cargar_ligas(){

        $modelo = new modeloPortada();
        $res = $modelo->executeQuery("SELECT a.*, (SELECT  c.numero FROM temporada_liga c WHERE c.liga_id = a.id ORDER BY id DESC LIMIT 1) AS temporada FROM liga a INNER JOIN liga_participantes b ON a.id = b.liga_id WHERE b.usuario_creacion = ". $_SESSION["usuario"][0]["id"] ."  GROUP BY a.id ");
        if($res){
          echo json_encode(array("resultado"=>true, "ligas"=>$res));

        }
    }
    public function cargar_ligas_all(){
      //lista todas las ligas donde haya participado y muestra si la está activa o no
      $modelo = new modeloPortada();
      $res = $modelo->executeQuery("SELECT a.*, (SELECT c.numero FROM temporada_liga c WHERE c.liga_id = a.id ORDER BY id DESC LIMIT 1) AS temporada FROM liga a INNER JOIN liga_participantes b ON a.id = b.liga_id WHERE b.usuario_id = ". $_SESSION["usuario"][0]["id"] ." GROUP BY a.id");
      foreach($res as $key => $item){
        if($item["estado"]==0){
          $res[$key]["estado_liga"] = 'Inactiva';
        }elseif($item["estado"]==1){
          $res[$key]["estado_liga"] = 'Activa';
        }elseif($item["estado"]==3){
          $res[$key]["estado_liga"] = 'En espera del sorteo';
        }elseif($item["estado"]==2){
          $res[$key]["estado_liga"] = 'En espera del kickoff';
        }else{
          $res[$key]["estado_liga"] = 'Activa';
        }
        if($item["fecha_creacion"]){
          $date=date_create($item["fecha_creacion"]);
          $res[$key]["fecha_creacion"] =  date_format($date,"d/m/Y");
        }
        if($item["temporada"]==''){

          $res[$key]["temporada"] =  'Aún no empieza la liga';
        }
      }



      if($res){
        echo json_encode(array("resultado"=>true, "data"=>$res));

      }
    }
    public function cargar_copas(){

      $modelo = new modeloPortada();
      $query = "SELECT c.*,(CASE c.estado WHEN 0 THEN 'Finalizada' WHEN 1 THEN 'Activa' END) as estado,max(tl.numero) as temporada, li.nro_participantes
      FROM copa c
      INNER JOIN temporada_liga tl ON c.temporada_liga_id = tl.id
      LEFT JOIN liga li ON li.id = tl.liga_id
      LEFT JOIN liga_participantes lp ON lp.liga_id = li.id
      WHERE lp.usuario_id = ". $_SESSION["usuario"][0]["id"] ." AND li.estado=1 GROUP BY c.id";

      $res = $modelo->executeQuery($query);
      if($res){
        echo json_encode(array("resultado"=>true, "copas"=>$res));

      }
    }
    public function cargar_copa_grafico(){

      $modelo = new modeloPortada();
      $res = $modelo->executeQuery("SELECT pc.*, li.nro_participantes
      FROM partidos_copa pc
      INNER JOIN temporada_liga tl ON pc.temporada_liga_id = tl.id
      LEFT JOIN liga li ON li.id = tl.liga_id
      LEFT JOIN liga_participantes lp ON lp.liga_id = li.id
      WHERE lp.usuario_id = ". $_SESSION["usuario"][0]["id"] ." AND li.estado=1 AND pc.estado=1 AND lp.estado=1");
      if($res){
        echo json_encode(array("resultado"=>true, "partidos_copa"=>$res));
      }
    }
    public function cargar_ligas_admin(){
      $modelo = new modeloPortada();
      $sql= "SELECT * FROM liga WHERE usuario_id = ".$_SESSION["usuario"][0]["id"] ." AND  estado<>0 ";
      $res = $modelo->executeQuery($sql);
        if($res){
          echo json_encode(array("resultado"=>true, "ligas"=>$res));

        }
    }
    public function cargar_ligas_kickoff(){
      $modelo = new modeloPortada();
      $sql= "SELECT * FROM liga WHERE usuario_id = ".$_SESSION["usuario"][0]["id"] ." AND  estado=3 ";

      $res = $modelo->executeQuery($sql);
        if($res){
          echo json_encode(array("resultado"=>true, "ligas"=>$res));

        }else{
          echo json_encode(array("resultado"=>false));
        }
    }
    public function mostrar_liga(){
      $modelo = new modeloPortada();
      //$res = $modelo->selectRowData('liga','*', array("id"=>$_POST["id"]) );
      $res = $modelo->executeQuery("SELECT a.*, (SELECT  c.numero FROM temporada_liga c WHERE c.liga_id = a.id ORDER BY c.id DESC LIMIT 1) AS temporada, (SELECT  c.estado FROM temporada_liga c WHERE c.liga_id = a.id ORDER BY c.id DESC LIMIT 1) AS estado_temporada FROM liga a WHERE a.id=" . $_POST["id"]);

      if($res){
            $res2 = $modelo->executeQuery("SELECT count(*) AS cont FROM liga_participantes a WHERE a.liga_id= ".$_POST["id"]." AND a.estado =1");
            if($res2){
              echo json_encode(array("resultado"=>true, "liga"=>$res[0], "participantes"=>$res2[0]["cont"]));
            }
          }
    }
    public function actualizar_datos(){
      if( $_POST['tabla'] && $_POST['campos'] && $_POST['where']  ){

        $modelo = new modeloPortada();
        $res = $modelo->selectRowData($_POST['tabla'],'*', $_POST['where']);
        if($res){

          $_POST["campos"]["usuario_edicion"] = $_SESSION["usuario"][0]["id"];
          $_POST["campos"]["fecha_edicion"] = date("Y-m-d H:i:s");
          $res = $modelo->updateData( $_POST['tabla'], $_POST['campos'], $_POST['where']);
          if($res){
            echo json_encode(array("resultado"=>true, "titulo"=>"Registro Exitoso", "mensaje"=>"Los campos se han actualizado", "accion"=>"success"));
          }

        }
      }
    }

    public function cargar_equipos(){
      $modelo = new modeloPortada();
      $res = $modelo->executeQuery("SELECT a.*, (SELECT  c.numero FROM temporada_liga c WHERE c.liga_id = a.liga_id ORDER BY id DESC LIMIT 1) AS temporada, (SELECT  d.id FROM temporada_liga d WHERE d.liga_id = a.liga_id ORDER BY d.id DESC LIMIT 1) AS temporada_id, (SELECT d.nombre FROM liga d WHERE d.id = a.liga_id LIMIT 1) AS nombre_liga FROM equipos a WHERE a.usuario_creacion = ". $_SESSION["usuario"][0]["id"] ." ");
        foreach($res as $key => $item){
          if($item["estado"]==0){
            $res[$key]["estado_equipo"] = 'Inactivo';
          }elseif($item["estado"]==1){
            $res[$key]["estado_equipo"] = 'Activo';
          }elseif($item["estado"]==2){
            $res[$key]["estado_equipo"] = 'En espera del kickoff';
          }else{
            $res[$key]["estado_equipo"] = 'Activo';
          }

        }

        if($res){
            echo json_encode(array("resultado"=>true, "equipos"=>$res));

        }
    }
    public function cargar_equipos_liga(){
      if( $_POST['datos_liga'] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_equipos_liga($_POST['datos_liga']);

      }

    }

    public function cargar_datos(){

      if( $_POST['tabla'] && $_POST['where'] &&  $_POST['campos'] ){
        $modelo = new modeloPortada();
        $res = $modelo->selectRowData($_POST['tabla'],$_POST['campos'], $_POST['where']);
          if($res){
            echo json_encode(array("resultado"=>true, "data"=>$res));

          }
      }
    }
    public function cargar_plantilla(){

      if( $_POST['jugadores'] && $_POST['datos_liga']  && $_POST['equipo_id'] ){

        $modelo = new modeloPortada();
        return $modelo->cargar_plantilla( $_POST['jugadores'],  $_POST['datos_liga']["liga_participantes_id"],  $_POST['datos_liga']["temporada_id"],  $_POST['equipo_id']);
      }

    }

    public function kickoff_sorteo(){
      if( $_POST['valores'] ){
        $modelo = new modeloPortada();
        return $modelo->kickoff_sorteo($_POST['valores']);

      }
    }

    public function cargar_equipos_kickoff(){
      if( $_POST['liga'] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_equipos_kickoff($_POST['liga']);

      }
    }

    public function generar_partidos_liga(){
      if( $_POST['liga'] && $_POST['temporada_liga_id']){
        $modelo = new modeloPortada();
        return $modelo->generar_partidos_liga($_POST['liga'], $_POST['temporada_liga_id']);
      }
    }

    public function cargar_equipos_casa(){
      if( $_POST['liga'] && $_POST['temporada_liga_id'] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_equipos_casa($_POST['liga'], $_POST['temporada_liga_id']);
      }
    }

    public function cargar_equipos_visitante(){
      if( $_POST['temporada_liga'] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_equipos_visitante($_POST['temporada_liga'], $_POST['equipo_casa']);
      }
    }

    public function guardar_resultados_liga(){
      if( $_POST['partido_liga'] && $_POST["valores"] && $_POST['temporada_id'] && $_POST["valores_1"] && $_POST["valores_2"]){
        $modelo = new modeloPortada();
        return $modelo->guardar_resultados_liga($_POST['partido_liga'], $_POST['valores'], $_POST['temporada_id'], $_POST["valores_1"], $_POST["valores_2"]);
      }
    }

    public function ver_tabla_clasificacion(){
      if( $_POST['liga'] ){
        $modelo = new modeloPortada();
        return $modelo->ver_tabla_clasificacion($_POST['liga']);
      }
    }

    public function mostrar_boton_negociaciones(){
      if( $_POST['liga'] ){
        $modelo = new modeloPortada();
        return $modelo->mostrar_boton_negociaciones($_POST['liga']);
      }
    }

    public function cargar_datos_usuario(){

        $modelo = new modeloPortada();
        return $modelo->cargar_datos_usuario($_SESSION["usuario"][0]["id"]);

    }
    public function cargar_teams_compra(){

        $modelo = new modeloPortada();
        return $modelo->cargar_teams_compra();

    }
    public function cargar_jugadores_compra(){

      if( $_POST['liga'] && $_POST["team"] && $_POST['temporada_liga_id'] && $_POST['liga_participantes_id']){
        $modelo = new modeloPortada();
        return $modelo->cargar_jugadores_compra($_POST['liga'], $_POST['team'], $_POST['temporada_liga_id'], $_POST['liga_participantes_id']);
      }

    }

    public function comprar_jugador(){

      if( $_POST['jugador'] && $_POST["liga_participantes"] && $_POST["temporada_liga_id"] && $_POST["datos_liga"]){
        $modelo = new modeloPortada();
        return $modelo->comprar_jugador($_POST['jugador'], $_POST['liga_participantes'], $_POST["temporada_liga_id"], $_POST["datos_liga"]);
      }
    }
    public function comprar_jugador_periodo_extemporaneo(){

      if( $_POST['jugador'] &&  $_POST["datos_liga"]){
        $modelo = new modeloPortada();
        return $modelo->comprar_jugador_periodo_extemporaneo($_POST['jugador'], $_POST['datos_liga']);
      }
    }
    public function comprar_otro_equipo_jugador(){

      if( $_POST['jugador'] && $_POST["datos_liga"]  && $_POST["equipo_id"]){
        $modelo = new modeloPortada();
        return $modelo->comprar_otro_equipo_jugador($_POST['jugador'], $_POST['datos_liga']['liga_participantes_id'], $_POST['datos_liga']["temporada_id"], $_POST["equipo_id"]);
      }
    }

    public function cargar_jugadores_ventas(){

      if( $_POST["liga_participantes"] && $_POST["temporada_liga_id"]){
        $modelo = new modeloPortada();
        return $modelo->cargar_jugadores_ventas($_POST['liga_participantes'], $_POST["temporada_liga_id"]);
      }
    }

    public function vender_mercado(){

      if( $_POST["jugador"] && $_POST["temporada_liga"] && $_POST["liga_participantes"] && $_POST["equipo"] && $_POST["liga_id"] && $_POST["nuevo_saldo"]){
        $modelo = new modeloPortada();
        return $modelo->vender_mercado( $_POST["jugador"], $_POST["temporada_liga"] , $_POST["liga_participantes"], $_POST["equipo"], $_POST["liga_id"], $_POST["nuevo_saldo"]);
      }
    }

    public function cargar_partidos_copa(){

      if( $_POST["temporada_liga"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_partidos_copa( $_POST["temporada_liga"]);
      }
    }
    public function cargar_goles_liga(){

      if( $_POST["partido_liga"]  ){
        $modelo = new modeloPortada();
        return $modelo->cargar_goles_liga( $_POST["partido_liga"]);
      }
    }

    public function buscar_equipos_tabla(){
      if( $_POST["id_liga"]  ){
        $modelo = new modeloPortada();
        return $modelo->buscar_equipos_tabla( $_POST["id_liga"]);
      }
    }
    public function cargar_plantilla_otro_equipo(){

      if( $_POST['equipo'] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_plantilla_otro_equipo( $_POST["equipo"]);
      }
    }

    public function cargar_datos_all(){

      if( $_POST['tabla'] && $_POST['where'] &&  $_POST['orderby'] ){
        $modelo = new modeloPortada();
        $res = $modelo->selectData($_POST['tabla'],$_POST['where'], $_POST['orderby']);
          if($res){
            echo json_encode(array("resultado"=>true, "data"=>$res));

          }
      }
    }
    public function buscar_equipos_tabla_by_temporada(){
      if( $_POST["liga_id"] && $_POST["temporada_id"] ){
        $modelo = new modeloPortada();
        return $modelo->buscar_equipos_tabla_by_temporada( $_POST["liga_id"], $_POST["temporada_id"]);
      }
    }

    public function cargar_equipos_historial(){

      $modelo = new modeloPortada();
      $res = $modelo->executeQuery("SELECT a.*, (SELECT  c.numero FROM temporada_liga c WHERE c.liga_id = a.liga_id ORDER BY id DESC LIMIT 1) AS temporada, (SELECT d.nombre FROM liga d WHERE d.id = a.liga_id LIMIT 1) AS nombre_liga FROM equipos a WHERE a.usuario_creacion = ". $_SESSION["usuario"][0]["id"] );
          if($res){
            echo json_encode(array("resultado"=>true, "equipos"=>$res));

          }
    }
    public function cargar_negociaciones(){
      if( $_POST["liga_participantes_id"] && $_POST["temporada_liga_id"]){
        $modelo = new modeloPortada();
        return $modelo->cargar_negociaciones( $_POST["liga_participantes_id"], $_POST["temporada_liga_id"] );
      }
    }

    public function buscar_equipo_estadisticas(){
      if( $_POST["liga_id"] && $_POST["temporada_id"] && $_POST["equipo_id"]){
        $modelo = new modeloPortada();
        return $modelo->buscar_equipo_estadisticas( $_POST["liga_id"], $_POST["temporada_id"],$_POST["equipo_id"]);
      }
    }

    public function cargar_partidos_liga(){
      if( $_POST["tabla"] && $_POST["temporada_liga_id"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_partidos_liga( $_POST["tabla"], $_POST["temporada_liga_id"] );
      }
    }

    public function cargar_premios_equipo(){
      if( $_POST["equipo_id"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_premios_equipo( $_POST["equipo_id"] );
      }
    }
    public function cargar_gastos_equipo(){
      if( $_POST["equipo_id"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_gastos_equipo( $_POST["equipo_id"] );
      }
    }
    public function guardar_resultados_copa(){
      if( $_POST["valores"] && $_POST["where"] && $_POST["temporada_liga_id"] && $_POST["liga_id"] && $_POST["liga_participantes_id"] && $_POST["partido"] ){
        $modelo = new modeloPortada();

        return $modelo->guardar_resultados_copa( $_POST["valores"], $_POST["where"], $_POST["temporada_liga_id"], $_POST["liga_id"], $_POST["liga_participantes_id"], $_POST["partido"]);
      }
    }
    public function actualizar_usuarios(){
      if( $_POST["campos"] && $_POST["where"]){
        $modelo = new modeloPortada();
        return $modelo->actualizar_usuarios( $_POST["campos"], $_POST["where"]);
      }else{
        echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error."));
      }
    }

    public function cargar_solicitudes_compra(){
      if( $_POST["datos_liga"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_solicitudes_compra( $_POST["datos_liga"]);
      }
    }

    public function vender_otro_jugador(){
      if( $_POST["datos_liga"] && $_POST["solicitud"] && $_POST["nuevo_equipo"]){
        $modelo = new modeloPortada();
        return $modelo->vender_otro_jugador( $_POST["datos_liga"], $_POST["solicitud"], $_POST["nuevo_equipo"]);
      }
    }

    public function configuracion_equipo(Type $var = null){

      if( $_POST["equipo_id"] && $_POST["equipo_nombre"] ){

        $nombre_archivo = 'equipo_avatar_' . $_SESSION["usuario"][0]["id"] . '_' . $_POST["equipo_id"] . '_'.$_FILES['archivo']['name'];

        $tipo_archivo   = $_FILES['archivo']['type'];
        $tamano_archivo = $_FILES['archivo']['size'];
        $tmp_archivo    = $_FILES['archivo']['tmp_name'];
        $extension		= pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $nombre_archivo_bd = 'equipo_avatar_' . $_SESSION["usuario"][0]["id"] . '_' . $_POST["equipo_id"] . '_.'.$extension;
        $result=[];
        $fichero_subido = DIR_CARGAS . basename($nombre_archivo_bd);

        if(file_exists($fichero_subido)) {
          chmod($fichero_subido,0755); //Change the file permissions if allowed
          $res = unlink($fichero_subido); //remove the file

        }

        if (move_uploaded_file($tmp_archivo, $fichero_subido)){
          $modelo = new modeloPortada();
          $res = $modelo->updateData('equipos',array("avatar"=>$nombre_archivo_bd, "nombre"=>$_POST["equipo_nombre"], "usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$_POST["equipo_id"]));

          if($res){
            echo json_encode(array("resultado"=>true, "avatar"=>$nombre_archivo_bd) ) ;
          }else{
            echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error."));
          }
        }

    }
    }

    public function cargar_plantilla_2(){
      if( $_POST['jugadores'] ){
        $valores = '(';
        foreach ($_POST["jugadores"] as $item) {
          $valores .= $item . ',';
        }
        $valores = substr($valores, 0, -1);
            $valores = $valores . ')';
        $modelo = new modeloPortada();
        $res = $modelo->executeQuery("SELECT a.*, b.valor valor_compra FROM jugadores a LEFT JOIN jugadores_valor b ON a.id = b.id_jugador WHERE a.id IN ".$valores ." AND a.estado=1");
            if($res){
              echo json_encode(array("resultado"=>true, "plantilla"=>$res));
            }

      }
    }

    public function cancelar_venta(){
      if( $_POST["solicitud"] ){
        $modelo = new modeloPortada();
        return $modelo->cancelar_venta( $_POST["solicitud"]);
      }
    }

    public function iniciar_periodo_subastas(){
      if( $_POST["datos_liga"] ){
        $modelo = new modeloPortada();
        return $modelo->iniciar_periodo_subastas( $_POST["datos_liga"]);
      }
    }
    public function finalizar_temporada(){
      if( $_POST["datos_liga"] ){
        $modelo = new modeloPortada();
        return $modelo->finalizar_temporada( $_POST["datos_liga"]);
      }
    }
    public function cargar_equipos_copa_grafico(){
      if( $_POST["datos_liga"] && $_POST["temporada_liga_id"] ){
        $modelo = new modeloPortada();
        return $modelo->cargar_equipos_copa_grafico( $_POST["datos_liga"],  $_POST["temporada_liga_id"]);
      }
    }
    public function eliminar_equipo(){
      if( $_POST["equipo"] ){
        $modelo = new modeloPortada();
        return $modelo->eliminar_equipo( $_POST["equipo"]);
      }
    }
    public function rendirse_subasta(){

      $modelo = new modeloPortada();
      $liga_id = $_REQUEST["liga_id"];
      $jugador_id = $_REQUEST["jugador_id"];

      $res = $modelo->executeQuery("SELECT * FROM subastas WHERE  liga_id =:liga_id AND jugador_id =:jugador_id AND estado=1",array("liga_id"=>$liga_id,"jugador_id"=>$jugador_id));
      if ($res) { //si la subasta sigue activa
        $data_mysql = json_decode($res[0]["json_usuarios"],true);
        $data_jugadores = $data_mysql["data_jugadores"];
        if ($this->is_in_array($data_jugadores,"usu_id",$_SESSION["usuario"][0]["id"]))
        {//si el usuario pertenece a la subasta
          $valores_new_usuario = $this->removeElementWithValue($data_jugadores,"usu_id",$_SESSION["usuario"][0]["id"]);
          $data_mysql["data_jugadores"]=array();
          $data_mysql["data_jugadores"]=$valores_new_usuario;
          $update = $modelo->updateData("subastas", array("json_usuarios"=>json_encode($data_mysql),"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$_POST["liga_id"],"jugador_id"=>$_POST["jugador_id"]));

          if ($update) { //actualizando al usuario
            $liga_participantes = $modelo->selectRowData("liga_participantes","*", array("liga_id"=>$liga_id,"usuario_id"=>$_SESSION["usuario"][0]["id"]));

            $res = $modelo->deleteData('jugadores_subastas', array("liga_participantes_id"=>$liga_participantes["id"], "estado"=>2));
            if ($res) {
              if (count($valores_new_usuario)==1) {
                $jugadores_subastas = $modelo->executeQuery("SELECT * FROM jugadores_subastas js inner join liga_participantes lp on(js.liga_participantes_id=lp.id)
                WHERE lp.liga_id =:liga_id AND lp.usuario_id = ".$valores_new_usuario[0]["usu_id"]." AND js.jugadores_id =:jugadores_id AND js.estado=2",array("liga_id"=>$_POST["liga_id"],"jugadores_id"=>$jugador_id));
                echo json_encode(array("resultado"=>true,"ganador_default"=>true,"mensaje"=>"Ganaste la subasta por que se retiraron los demas ofertadores.","data"=>$jugadores_subastas));
                //mensaje para el que se quedó solo en la subasta
              }else {
                echo json_encode(array("resultado"=>true));
              }
              return true;
            }

          }else{
            echo json_encode(array("resultado"=>false,"mensaje"=>"No haz podido salir de la subasta"));
            return false;
          }
        }else{
          echo json_encode(array("resultado"=>false,"mensaje"=>"No perteneces a la subasta"));
          return false;
        }
      }else {
        echo json_encode(array("resultado"=>false,"mensaje"=>"La subasta no está activa"));
        return false;
      }
    }
}
