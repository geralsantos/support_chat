  //Vue.http.options.emulateJSON=true; // http client
  Vue.use(VueRouter);
  //Vue.http.options.emulateJSON=true; // http client
  var router = new VueRouter({
    mode: 'hash',
    routes: [
      { path: '/portada-index', component: portada_index },
    ]
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
       
      toggleMenu () {
        this.menuVisible = !this.menuVisible;
      }
    }
  }).$mount('#vue_app');
