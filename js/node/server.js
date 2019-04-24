var fs = require('fs');
var express = require('express');
var options = { 
  key: fs.readFileSync('/root/certs/pulpomasters.com/key.key'), 
  cert: fs.readFileSync('/root/certs/pulpomasters.com/crt.crt'), 
};

/*
var options = { 
  key: fs.readFileSync('/c/xampp/htdocs/portal-liga/file.pem'), 
  cert: fs.readFileSync('/c/xampp/htdocs/portal-liga/file.crt'), 
};*/
var app = require('https').createServer(options,express())
var io = require('socket.io')(app);
const mysql_cnn=require('./mysql.js');

var port = 8095;
var LocalStorage = require('node-localstorage').LocalStorage;/*
const DataUserTmp = new LocalStorage("C:/xampp/htdocs/portal-liga");
const ruta_subasta_liga = "C:/xampp/htdocs/portal-liga";*/
const ruta_subasta_liga = "/home/admin/public_html/pulpomasters.com/subastas_rooms";
const DataUserTmp = new LocalStorage(ruta_subasta_liga); //para linux

const subasta = io.of('/subasta');
const lista_subasta = io.of('/lista_subasta');
const subastaData=require('./subasta.js');
const tiempo_subasta = 180000; //3 minutos para setinterval ya que maneja otro formato
const tiempo_subasta_normal = 180 ; //3 minutos en segundos para la vista
const interval = {};

lista_subasta.on('connection',function( socket ){
  socket.emit("connected");
   /* interval[index] = setInterval(function(e) {
      console.log(interval[index]);
    }, app2[index]);*/
});
subasta.on('connection',function( socket ){
  socket.emit("connected");
  socket.on("room",function(response){
    let code_room = response.liga_id+"-"+response.id; //response.id=id jugador
    let get_data_subasta = JSON.parse(DataUserTmp.getItem(code_room));
    socket.join(code_room);
  });
  //subastas-liga.js
  socket.on("iniciar_subasta",function(response){
    let code_room = response.liga_id+"-"+response.jugadores_id;
    let data_jugadores = [];
    response.data_jugadores = data_jugadores;
    response.tiempo_restante = tiempo_subasta_normal; //siempre en segundos basado en el formato normal
    DataUserTmp.setItem(code_room,JSON.stringify(response));
    console.log("Iniciado subasta con archivo: ",response);
    clearInterval(interval[code_room]);
    interval[code_room] = setInterval(function(e) 
    {
      //subastaData.terminar_subasta(response.liga_id,response.jugadores_id,response.precio_base_jugador);
      console.log("Eliminar archivo: "+code_room);
      usuario_ganador(response.liga_id,response.jugadores_id,"sinpujarsubasta") 
      fs.unlinkSync(ruta_subasta_liga+"/"+code_room); //eliminar archivo de la liga
      clearInterval(interval[code_room]);
    }, tiempo_subasta);
  });
  socket.on("rendirse_subasta",function(response){
    console.log("rendirse_subasta");
    let mensaje = response.mensaje;

    response = response.data[0];
    console.log(response);
    let code_room = response.liga_id+"-"+response.jugadores_id;
    subastaData.terminar_subasta(response.liga_id,response.jugadores_id);

    usuario_ganador(response.liga_id,response.jugadores_id,"",mensaje) 
    fs.unlinkSync(ruta_subasta_liga+"/"+code_room); //eliminar archivo de la liga
    clearInterval(interval[code_room]);
  });
  //subasta.js
  socket.on("subasta_pujar",function(response){
    let subasta_response = response;
    let code_room = subasta_response.liga_id+"-"+subasta_response.jugadores_id;
    console.log(subasta_response);
    let data_subasta = JSON.parse(DataUserTmp.getItem(code_room));
    data_subasta.precio_base_jugador = subasta_response.monto; 
    data_subasta.tiempo_restante = subasta_response; //siempre en segundos basado en el formato normal
    DataUserTmp.setItem(code_room,JSON.stringify(data_subasta));
    let data = {nombre_usuario:subasta_response.nombre_usuario,liga_id:subasta_response.liga_id,jugador_id:subasta_response.jugadores_id,monto:subasta_response.monto,tiempo_restante:tiempo_subasta_normal};
    subasta.in(code_room).emit('subasta_pujar',data);
    console.log("reseteo tiempo, monto y usuario subasta con archivo: "+code_room);
    console.log(data);


    clearInterval(interval[code_room]);//reiniciando el tiempo de vida de la subasta
    interval[code_room] = setInterval(function(e) 
    {
      subastaData.terminar_subasta(subasta_response.liga_id,subasta_response.jugadores_id,subasta_response.monto,subasta_response.usuario_ganador);
      usuario_ganador(subasta_response.liga_id,subasta_response.jugadores_id) 
      console.log("Eliminar archivo: ",code_room);
      fs.unlinkSync(ruta_subasta_liga+"/"+code_room); //eliminar archivo de la liga
      clearInterval(interval[code_room]);
    }, tiempo_subasta);
  });
  //subasta.js
  socket.on("unirse_subasta",function(response){
    let datos_subasta = response.datos_subasta, 
    code_room = datos_subasta.liga_id+"-"+datos_subasta.jugador_id,
    data_subasta = JSON.parse(DataUserTmp.getItem(code_room));
    console.log(data_subasta);
    var id_usu=datos_subasta.usu_id,nombre=datos_subasta.usu_nombre,apellido=datos_subasta.usu_apellido,id_fb=datos_subasta.id_fb,correo=datos_subasta.usu_correo,usuario=datos_subasta.usu_usuario,clave=datos_subasta.clave;
    let usuario_existe = false, data_usuario = {usu_id:id_usu,nombre:nombre,apellido:apellido,id_fb:id_fb,correo:correo,usuario:usuario,clave:clave};
    if (data_subasta.data_jugadores.length>0)
    {//si un usuario ya ha entrado

      for(var key in data_subasta.data_jugadores)
      {//recorremos todos los usuarios que ya entraron a la subasta
          if(data_subasta.data_jugadores[key].usu_id==id_usu) //si el usuario ya entró la primera vez
          usuario_existe= true;
          break;
      }
    }
   /* var rooms = io.nsps['/subasta'].adapter.rooms;
    let tokenusuario_list = []
    for (roo in rooms) {
        console.log(roo)
    }*/
    console.log(usuario_existe);
    if (!usuario_existe) {//si el usuario no está, se inserta
      data_subasta.data_jugadores.push(data_usuario);
      DataUserTmp.setItem(code_room,JSON.stringify(data_subasta));
    }
    socket.join(code_room);
  });

  //socket.join(room);

  socket.on('emit_and_response',function(response){
    subasta.emit('responseserver',"hola del server");
  });

  socket.on('enviarmensaje',(response)=>{
    console.log(response);
    let code_room = response.liga_id+"-"+response.jugador_id;
    //console.log("enviarmensaje: "+code_room);
    subasta.in(code_room).emit('mostrarmensaje',response);
  });
 function user_connected(token)
 {
     var privatedatauser_ = JSON.parse(DataUserTmp.getItem(token));
     let dom_ = {privatedatauser:privatedatauser_};
     return dom_;
 }
 function MayusPrimera(string){
     string = string.toLowerCase();
   return string.charAt(0).toUpperCase() + string.slice(1);
 }
 
  function usuario_ganador(liga_id,jugadores_id,option="",mensaje="") 
  {
    mysql_cnn.getConnection(function(err, connection) 
    {
        if (err){
            console.log("error1",err);
            throw err;
            return false;
        }
        if (option=="sinpujarsubasta") {
          connection.query('DELETE FROM jugadores_subastas WHERE estado=2 and jugadores_id='+jugadores_id+' and liga_participantes_id IN ((SELECT lp.id as liga_participantes_id FROM liga_participantes lp where lp.liga_id='+liga_id+' and lp.estado=1))', function(err,res){
            if (err){
                console.log("error2",err);throw err;
                return false;
            }
            connection.query('DELETE FROM subastas WHERE estado=1 and liga_id='+liga_id+' and jugador_id='+jugadores_id, function(err,res){
              if (err){
                  console.log("error: DELETE FROM subastas.");
                  throw err
                  return false
              }
              connection.query(subastaData.existen_jugadores_subastas(liga_id), function(err,res){
                if (err){
                    console.log("error4");
                    throw err;
                    return false;
                }
                console.log(res);
                console.log(isempty(res)?"true":"false");
                if (isempty(res)) {
                    connection.query(subastaData.finalizar_temporada_subastas(liga_id), function(err,res){
                        if (err){
                            console.log("error5");
                            throw err;
                            return false;
                        }
                        let objresponse={mensaje:'Ningún equipo a ofertado, el jugador regreserá al mercado para ser comprado en la fase: "Compra Extemporánea".'};
                        subasta.in((liga_id+"-"+jugadores_id)).emit('anunciar_ganador_subasta',objresponse);
                        return true;
                    });
                }
                return true;
              });
              
            })
          })
        }else{
          connection.query('SELECT sub.*,IFNULL(usu.nombre,usu.usuario) as usuario_nombre,usu.id as usuario_id,lp.id as liga_participantes_id, eq.jugadores as equipo_jugadores, eq.id as equipo_id, jugval.valor,(select id from temporada_liga where liga_id='+liga_id+' order by id desc limit 1 ) as temporada_liga_id  FROM subastas sub INNER JOIN usuarios usu on (usu.id=sub.usuario_ganador) INNER JOIN liga_participantes lp on (lp.usuario_id=sub.usuario_ganador) INNER JOIN equipos eq ON (eq.liga_participantes_id=lp.id) INNER JOIN jugadores_valor jugval ON (sub.jugador_id=jugval.id_jugador) INNER JOIN liga lig ON(lig.id=lp.liga_id) WHERE eq.liga_id='+liga_id+' AND sub.liga_id ='+liga_id+' AND lp.liga_id ='+liga_id+' AND sub.jugador_id ='+jugadores_id, function(err,resUsuario){
            if (err){
                console.log("error: select subastas sub.");
                throw err;
                return false;
            }
            console.log("geral");
            console.log(resUsuario);
            connection.query('INSERT INTO plantilla_jugadores (fecha_creacion,jugadores_id,usuario_creacion,usuario_edicion,liga_id,liga_participantes_id) values (now(),'+jugadores_id+','+resUsuario[0].usuario_id+','+resUsuario[0].usuario_id+','+liga_id+','+resUsuario[0].liga_participantes_id+')', function(err,res){
              if (err){
                console.log("error: plantilla_jugadores.");
                throw err;
                return false;
              }
              var equipo_jugadores = isempty(resUsuario[0].equipo_jugadores) ? [] : JSON.parse(resUsuario[0].equipo_jugadores);
              equipo_jugadores.push(jugadores_id);
              connection.query("UPDATE equipos SET jugadores='"+JSON.stringify(equipo_jugadores)+"' WHERE id="+resUsuario[0].equipo_id, function(err,res){
                if (err){
                  console.log("error: equipos.");
                  throw err;
                  return false;
                }
               
                connection.query("INSERT INTO compras (jugadores_id,fecha_creacion,usuario_creacion,usuario_edicion,monto,temporada_liga_id,liga_participantes_id,equipo_id) VALUES("+jugadores_id+",now(),"+resUsuario[0].usuario_id+","+resUsuario[0].usuario_id+","+resUsuario[0].precio+","+resUsuario[0].temporada_liga_id+","+resUsuario[0].liga_participantes_id+","+resUsuario[0].equipo_id+")  ", function(err,res){
                  if (err){
                    console.log("error: compras.");
                    throw err;
                    return false;
                  }
                  connection.query("SELECT * FROM gastos WHERE codigo='subastas' limit 1", function(err,resGastos){
                    if (err){
                      console.log("error: gastos");
                      throw err;
                      return false;
                    }
                    connection.query("INSERT INTO gastos_historial (fecha_creacion,usuario_creacion,usuario_edicion,monto,temporada_liga_id,equipo_id,liga_id,gastos_id) values (now(),"+resUsuario[0].usuario_id+","+resUsuario[0].usuario_id+","+resUsuario[0].precio+","+resUsuario[0].temporada_liga_id+","+resUsuario[0].equipo_id+","+liga_id+","+resGastos[0].id+")", function(err,res){
                      if (err){
                        console.log("error: INSERT INTO gastos_historial");
                        throw err;
                        return false;
                      }
                      console.log("UPDATE saldo_disponible set saldo ="+resUsuario[0].precio+" WHERE liga_participantes_id="+resUsuario[0].liga_participantes_id+"");
                      connection.query("UPDATE saldo_disponible set saldo =saldo-CONVERT(SUBSTRING_INDEX("+resUsuario[0].precio+",'-',-1),UNSIGNED INTEGER) WHERE liga_participantes_id="+resUsuario[0].liga_participantes_id+"", function(err,res){
                        if (err){
                          console.log("error: UPDATE saldo_disponible");
                          throw err;
                          return false;
                        }
                        let objresponse={mensaje:('La subasta ha terminado, ganador(a) de la subasta es: '+resUsuario[0].usuario_nombre)};
                        if (!isempty(mensaje)) {
                          objresponse = {mensaje:mensaje};
                        }
                        subasta.in((liga_id+"-"+jugadores_id)).emit('anunciar_ganador_subasta',objresponse);
                        return true;
                      });
                    });
                  });
                });
              });
            });
          });
        }
        
    });
  }
    function isempty(str){
    return str === '' || str === undefined || str === null || typeof str === undefined || typeof str == undefined || typeof str === null || str.length === 0 || str == [];
  }
});

//subastaData.list();
app.listen(port,function(){
  console.log("servidor corriendo en http://localhost:"+port);
 
});
