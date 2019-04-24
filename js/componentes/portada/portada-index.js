var portada_index = {
    template: '#portada-index',
    data:()=>({
      equipos:[],
      equipo:[],
      jugadores:[],
      plantilla:[],
      datos_liga:[],
      mensaje:null,
      mostrar:false,
      imagen:false,
      usuario_activo:null,
    }),
    created:function(){
    },
    mounted:function(){
     // this.cargar_datos_usuario();

    },
    methods:{
        cargar_datos_usuario(){
            this.$http.post('cargar_datos_usuario?view',{}).then(function(response){
                if( response.body.resultado ){
                    this.datos_liga = response.body.data;


                    console.log(response.body.usuario)
                    this.jugadores = JSON.parse(this.datos_liga.jugadores);
                    this.ver_platilla();
                    this.imagen = false;
                    this.mostrar= true;
                }else{
                    this.cargar_usuario();


                }
            });

        },
        cargar_usuario(){
            this.$http.post('cargar_usuario?view',{}).then(function(response){
                if( response.body.resultado ){
                   if(response.body.data.activo==0){
                    this.usuario_activo =0;

                    this.mensaje = "No perteneces a ninguna Liga. Crea o únete a una liga para empezar!"
                   }else{
                    this.usuario_activo =1;
                    this.imagen = true;
                    this.mensaje = "No perteneces a ninguna Liga. Crea o únete a una liga para empezar!"
                   }
                }
            });
        },
        ver_platilla(){
            this.$http.post('cargar_plantilla_2?view',{jugadores:this.jugadores}).then(function(response){
                if( response.body.resultado ){
                   this.plantilla = response.body.plantilla;
                }
            });
        },
      cargar_equipos(){
        this.$http.post('cargar_equipos_historial?view',{}).then(function(response){
            if( response.body.resultado ){
                this.equipos= response.body.equipos;
                for(var i= 0; i <  this.equipos.length; i++) {
                    let jugadores = JSON.parse(this.equipos[i].jugadores);
                    let num =(jugadores.length != undefined)?jugadores.length:0;
                    this.equipos[i].jugadores_num =num;
                }
                console.log(this.equipos[0]["jugadores"])
                this.equipo=this.equipos[0];
                this.jugadores = JSON.parse(this.equipos[0]["jugadores"]);
                this.cargar_plantilla();
            }else{
           // swal("Error", "Un error ha ocurrido", "danger");
            }
        });
    },

        ver_equipo(e){

            this.jugadores = JSON.parse(this.equipo.jugadores);
            this.cargar_plantilla();
            let data = [
                ['Ganados',10],
                ['Perdidos', 3],
                ['Empates', 3],

            ]
            this.ver_grafico(data)

        },
        cargar_plantilla(){

            this.$http.post('cargar_plantilla?view',{jugadores:this.jugadores}).then(function(response){
                if( response.body.resultado ){

                    console.log( response.body.plantilla);
                    this.plantilla = response.body.plantilla;

                }else{
             //   swal("Error", "Un error ha ocurrido", "danger");
                }
            });
        },
        ver_grafico(){
            Highcharts.chart('grafico-portada', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                title: {
                    text: 'Estadísticas',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 40
                },
                subtitle:{
                  text: this.equipo.nombre,
                  align: 'center',
                    verticalAlign: 'middle',
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y} partidos</b>'
                },
                credits:{
                  enabled:false
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            }
                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '75%'],
                        size: '110%'
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Partidos',
                    innerSize: '50%',
                    data: [
                        ['Ganados',10],
                        ['Perdidos', 3],
                        ['Empates', 3],

                    ]
                }]
            });
        },
        openCulqi(){
            appVue.culqi='&renovar_plan_culqi'; //url
            appVue.openCulqi();
        }
    }
  }