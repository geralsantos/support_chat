<?php
 require_once ROOT . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => API_ID_FACEBOOK,
  'app_secret' => API_ID_SECRET_FACEBOOK,
  'default_graph_version' => 'v2.6',
  "persistent_data_handler"=>"session"
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
$culqi = isset($_SESSION["culqi_url"])?("&".$_SESSION["culqi_url"]):null;
$loginUrl = $helper->getLoginUrl(LOGIN_URL_FACEBOOK."?tipo_acceso=registro_facebook".$culqi, $permissions);
unset($_SESSION["culqi_url"]);

 ?>
<div class="container-fluid" style="overflow:auto;height:100%;">
    <div class="row">

        <br>
        <br>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12">
            <div class="text-center custom-login blanco">
            <h3>PULPO MASTERS</h3>
                <h3>Formulario de Registro</h3>
                <p></p>
            </div>
            <div class="hpanel">

                <div class="panel-body">

                    <form action="<?php $this->url('registro') ?>" id="loginForm" method="post">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label style="color:#737373">Nombre</label>
                                <input class="form-control" name="nombre" id="nombre" v-model="nombre" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label style="color:#737373">Apellido</label>
                                <input class="form-control" name="apellido" id="apellido" v-model="apellido" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label style="color:#737373">Usuario</label>
                                <input class="form-control" name="usuario" id="usuario" v-model="usuario">
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color:#737373">Contraseña</label>
                                <input type="password" name="contrasena" id="contrasena" v-model="contrasena" class="form-control">
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color:#737373">Repita Contraseña</label>
                                <input type="password" v-model="repita_contrasena" class="form-control">
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color:#737373">Email</label>
                                <input type="email" class="form-control" v-model="email" name="email" id="email">
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color:#737373">Repita Email</label>
                                <input type="email" class="form-control" v-model="repita_email">
                            </div>

                        </div>
                        <div class="text-center">
                            <a @click="registro_login()" class="btn btn-success loginbtn" style="color:white;">Registrar</a>
                            <a href="<?php $this->url('index') ?>" class="btn btn-primary">Regresar</a>

                        </div>

                        <div class="form-group col-lg-12">
                            <div class="social-button">
                              <a href="<?php echo $loginUrl; ?>"> <button type="button" class="btn social facebook btn-flat btn-addon mb-3">
                                    <i class="fa fa-facebook"></i> Registrar con facebook</button></a>
                            </div>
                        </div>


                    </form>

                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
    </div>
    <div class="row">
        <br>
        <br>

      </div>
      <input type="hidden" id="culqi" data-id="<?php echo $culqi?>" v-model="culqi" value="<?php echo $culqi ?>">
</div>
