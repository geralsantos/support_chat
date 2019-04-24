{
  'use strict';
  //Vue.http.options.emulateJSON=true; // http client
  Vue.use(VueRouter);
  //Vue.http.options.emulateJSON=true; // http client
  const router = new VueRouter({
    mode: 'hash',
    routes: [
      { path: '/portada-index', component: portada_index },
    ]
});
Vue.component('bar', {
  name: 'bar',
  template: '#bar',
  props: ['test']
}); 
  var appVue = new Vue({
    el:'#vue_app', /* container vue */
    router,
    data: () => ({
      title_modulo:null,
      sidenavopen:"true",
      drawer: true,
        mini: true,
        right: null,
        rowsmodulos:[],
    }),
    created:function(){
    },mounted:function(){
      this.listar_menu();
    },
    watch:{
     
    },
    methods: {
      listar_menu(){
        console.log("menu-rincipal-portada-index");
        let self = this;
        axios.get('list_modulos?view').then(function(response){
            console.log(response.data)
            self.rowsmodulos=response.data;
        });
    },
      goBar:function() {
        this.theComponent = 'bar';
      },
      modulos_sidenav:function(){
        console.log("menu-rincipal-app-js");
        this.$http.post('list_modulos?view',{}).then(function(response){
          this.rowsmodulos=response.body.menu;
          document.getElementById('menu1').innerHTML=this.rowsmodulos;

          document.getElementById('menu-mobile').innerHTML=response.body.menu_mobile;
          document.getElementById('breadcome-area-cabecera').innerHTML=response.body.cabecera;
        });
      }  ,
      form_submit:function(){
        var data = new FormData(document.querySelector('#login-form'));
        this.$http.post('captcha?view',data).then(function(response){
            if (response.body.success) {
              document.querySelector('#login-form').submit();
            }else{
              let _error = {_code:response.body['error-codes'][0]},error_default=[["missing-input-response","Tiene que completar el CAPTCHA"],["timeout-or-duplicate","Ha duplicado o expirado el CAPTCHA, actualice su navegador."]],response_=[];
              for (var i = 0; i < error_default.length; i++) {
                if (error_default[i][0]==_error._code) {
                  response_ = error_default[i][1];
                }
              }
              swal({
                title: "Ha ocurrido un problema!",
                text: response_+"\ncode_error: "+_error._code,
                icon: "warning",
                button: "Aceptar",
              });
            }
        });
      },
      downloadXML : function(serie_cor,monto,fecha_emision,id_empresa){
        this.$http.post('xml?view',{serie_cor:serie_cor,monto:monto,fecha_emision:fecha_emision,id_empresa:id_empresa}).then(function(response){
            let data = response.body;
            if (isempty(data.xml)) {
              swal("Lo sentimos.", "No existe un XML relacionado a este comprobante!", "warning");
            }else{
              download((data.ruc+"-"+"01"+data.serie+"-"+data.numdoc+".xml"),b64_to_utf8(data.xml));
            }
        });
      },
      toggleMenu () {
        this.menuVisible = !this.menuVisible;
      },
      changeview(val){
        this.currentView=val;
          //document.querySelector('.comprobantesestado').style.display= (val!="portadaindex"?'none':'');
      },
      postCulqi(data){
        this.sweetmodal(true);
        let urlculqi = (this.culqi).substring(1);
        if (urlculqi=="registro_facebook") {
          data.culqi_confirm = true;
        }
        this.$http.post((urlculqi+'?view'),data).then(function(response){
            let data = response.body;
            if (data.resultado) {
              this.sweetmodal(false);
              swal("","Ha sido registrado, ingresando a "+NOMBRE_PAGINA+"....","success");
              setTimeout(() => {
                window.location.hash = "";
                //console.log(SITE_URL_DIR+"/portada/index");
                window.location.assign(SITE_URL_DIR+"/portada/index");
              }, 2000);
            }else{
              swal("Error","No se podido realizar la transacción","warning");
             /* setTimeout(() => {
                window.location.hash = "";
                //console.log(SITE_URL_DIR+"/portada/index");
                window.location.assign(SITE_URL_DIR+"/portada/index");
              }, 2000);*/
            }
        })
      },
      sweetmodal(open){
        if (open) {
          const wrapper = document.createElement('div');
            wrapper.innerHTML =`<div class="row" >
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="loader" ></div>
                <br>
                <div class="text-loader"><span style="color:black;">Procesando...</span></div>
                <br>
                <div class="text-loader"><span style="color:black;">Registrando tus datos al sistema, por favor no recargar la página hasta que se termine el proceso.</span></div>
            </div>
        </div>`;
            swal({
              title: "",
              text: "",
              content:wrapper,
              button:false,
              closeOnClickOutside: false,
              closeOnEsc: false,

            });
        }else{
          $('.swal-overlay.swal-overlay--show-modal').remove();
        }
      },
      openCulqi(){
        this.$http.post('getplans?view').then(function(response){
          let data = response.body.data; 
          
          /*swal-button--cancel*/
          const wrapper = document.createElement('div');
          var html_buttons = ""
          for (let index = 0; index < data.length; index++) {
            html_buttons+=`<div class="swal-button-container swal-button-plan-login"> 
            <button class="swal-button swal-button--roll" data-id="`+data[index]["id"]+`" data-nombre="`+data[index]["nombre"]+`" data-monto="`+data[index]["precio"]+`" id="`+(data[index]["temporadas"]==5?`temporada_basico`:`temporada_premium`)+`">`+data[index]["nombre"].split(" ")[1] +`: `+data[index]["temporadas"] +` temporadas!</button><div class="swal-button__loader">  <div></div><div></div><div></div> </div></div>`;
          }
          wrapper.innerHTML =html_buttons;
            
          swal({
            title: "Plan de Subscripción ",
            text: "Para disfrutar de "+NOMBRE_PAGINA+" debes eligir un plan 😄",
            icon: "warning",
            content:wrapper,
            button:false,
          });

          $(".swal-button-plan-login").click(function(){
            let plan = $(this).children().attr("id"),
            nombre_plan=$(this).children().attr("data-nombre"),
            monto_plan=$(this).children().attr("data-monto");
            $(this).addClass("activated");
            if (plan==null) {
              return false;
            }
            Culqi.publicKey = PUBLIC_KEY_CULQI_PRODUCTION;
            // Abre el formulario con la configuración en Culqi.settings
            Culqi.settings({
              title: (NOMBRE_PAGINA+' - '+nombre_plan),
              currency: 'PEN',
              description: 'PAGO DE INSCRIPCIÓN',
              amount: monto_plan
            });
            Culqi.open();
            e.preventDefault();
          });
        });
      },
      autopenCulqi(){
        if (!isempty(this.culqi)) {
          this.openCulqi();
        }
      }
    }
  });

}
