  <script type="text/javascript">  <?php $this->getReady(); ?></script>
  <script type="text/javascript" src="<?php echo JS ?>/utils.js"></script>
  <script type="text/javascript" src="<?php echo JS ?>/variable_entorno.js"></script>
  <script src='http://code.jquery.com/jquery-1.11.3.min.js'></script>

  <script src="<?php echo JS ?>/vue/vue.min.js"></script>
  <script src="<?php echo JS ?>/vue/axios.min.js"></script>
  <script src="<?php echo JS ?>/vue/vuerouter.min.js"></script>
  <script src="<?php echo JS ?>/vue/vuetify.min.js"></script>
  <script src="<?php echo JS ?>/emojis.min.js"></script>

 

  <script type="text/javascript" src="../dist/portada-index.js"></script>
  <script type="text/javascript" src="../dist/vueapps.js"></script>
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
</body>

</html>
