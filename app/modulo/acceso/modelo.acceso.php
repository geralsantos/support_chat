<?php
class modeloAcceso extends MySQL{

    public function getSesion($usuario, $clave){
        $usuario = $this->executeQuery("select * from usuarios where usuario=:usuario and clave=:clave",array("usuario"=>$usuario,"clave"=>$clave));
        return $usuario;
    }
    public function getSesionFacebook($id_facebook){
        $usuario = $this->executeQuery("select * from usuarios where id_fb=:id_fb",array("id_fb"=>$id_facebook));
        return $usuario;
    }
    public function registro($plan_id, $usuario, $email, $clave, $nombre, $apellido){
      $values=array("plan_id"=>$plan_id,"nombre"=>$nombre,"apellido"=>$apellido,"usuario"=>$usuario,"correo"=>$email,"clave"=>$clave,"fecha_creacion"=>date("Y-m-d H:i:s"));
      $usuario = $this->insertData("usuarios",$values);
      return $usuario;
    }
    public function registroFacebook($plan_id,$name, $email, $id_facebook){
      $values=array("plan_id"=>$plan_id,"nombre"=>$name,"correo"=>$email,"id_fb"=>$id_facebook,"fecha_creacion"=>date("Y-m-d H:i:s"));
      $usuario = $this->insertData("usuarios",$values);
      return $usuario;
    }
}
