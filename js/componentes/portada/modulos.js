Vue.component('rowsmodulos',{
  
  data:()=>({
    rowsmodulos:'',
  }),
  mounted:function(){
    this.listar_menu();
  },
  methods:{
    listar_menu(){
      console.log("menu-rincipal-portada-index");
      let self = this;
      axios.get('list_modulos?view').then(function(response){
          console.log(response.data)
          self.rowsmodulos=response.data;
        
      });
  },
  },
  template:`<div>
  <template>
  {{rowsmodulos}}
      </template> 
  </div>`,
}) 