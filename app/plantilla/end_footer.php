  <script type="text/javascript">  <?php $this->getReady(); ?></script>
  <script type="text/javascript" src="<?php echo JS ?>/utils.js"></script>
  <script type="text/javascript" src="<?php echo JS ?>/variable_entorno.js"></script>

  <script src="<?php echo JS ?>/vue/vue.min.js"></script>
  <script src="<?php echo JS ?>/vue/axios.min.js"></script>
  <script src="<?php echo JS ?>/vue/vuerouter.min.js"></script>
  <script src="<?php echo JS ?>/vue/vuetify.min.js"></script>
  <script src="<?php echo JS ?>/emojis.min.js"></script>

  <?php foreach (glob(JS_ROOT . DS . 'componentes' . DS ."portada/*.js") as $filename): ?>
    <script type="text/javascript" src="<?php echo JS . '/componentes/portada/'. basename($filename); ?>"></script>
  <?php endforeach;?>
  <script type="text/javascript" src="<?php echo JS ?>/vue/app_login.js"></script>
  <script type="text/javascript" src="<?php echo JS ?>/vue/app.js"></script>
  <script type="text/javascript">
    if ("<?php echo $_SESSION["mensaje_alerta"]["tipo"] ?>"=="error") {
      swal("Error", "<?php echo $_SESSION["mensaje_alerta"]["texto"] ?>", "warning");
      <?php unset($_SESSION['mensaje_alerta']);unset($_SESSION['mensaje_alerta']) ?>
    }
  </script>
<footer>
<div class="footer-text"> <p>Copyright</p>
  </div>
</footer>
<template id="bar">
              <div>
                Barxd {{test}}
              </div>
            </template>
</body>

</html>
