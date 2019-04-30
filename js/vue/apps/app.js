import readingTime from "reading-time"; 
window.calcRT = ev => {
 var stats = readingTime(ev.value).text;
document.getElementById("readingTime").innerText = stats;
};
  //Vue.http.options.emulateJSON=true; // http client
  Vue.use(VueRouter);
  //Vue.http.options.emulateJSON=true; // http client
  const router = new VueRouter({
    mode: 'hash',
    routes: [
      { path: '/portada-index', component: require('../../componentes/portada/portada-index.js').default },
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
  });
