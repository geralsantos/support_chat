const subasta_export = {};
const mysql_cnn=require('./mysql.js');
function finalizar_temporada_subastas(liga_id){
    return "UPDATE temporada_liga t inner join ((select id from temporada_liga where liga_id="+liga_id+" order by id desc limit 1)) as v2 ON t.id = v2.id SET t.estado=5";
}
function existen_jugadores_subastas(liga_id){
    return "SELECT * FROM jugadores_subastas where estado=1 and liga_participantes_id IN ((SELECT lp.id as liga_participantes_id FROM liga_participantes lp where lp.liga_id="+liga_id+" and lp.estado=1))";
}
subasta_export.finalizar_temporada_subastas=function(liga_id){
    return finalizar_temporada_subastas(liga_id);
};
subasta_export.existen_jugadores_subastas=function(liga_id){
    return existen_jugadores_subastas(liga_id);
};
subasta_export.terminar_subasta = function(liga_id,jugadores_id)
{
    var data_subasta;
    mysql_cnn.getConnection(function(err, connection) 
    {
        if (err){
            console.log("error1",err);
            throw err;
            return false;
        }
        connection.query('DELETE FROM jugadores_subastas WHERE liga_participantes_id IN ((SELECT lp.id as liga_participantes_id FROM liga_participantes lp where lp.liga_id='+liga_id+' and lp.estado=1)) and jugadores_id='+jugadores_id+'', function(err,res){
            if (err){
                console.log("error2",err);throw err;
                return false;
            } 
            connection.query('UPDATE subastas SET estado=0 WHERE liga_id='+liga_id+' and jugador_id='+jugadores_id, function(err,res){
                if (err){
                    console.log("error3");
                    throw err
                    return false
                }
                connection.query(existen_jugadores_subastas(liga_id), function(err,res){
                    if (err){
                        console.log("error4");
                        throw err;
                        return false;
                    }
                    console.log(res);
                    console.log(isempty(res)?"true":"false");
                    if (isempty(res)) {
                        connection.query(finalizar_temporada_subastas(liga_id), function(err,res){
                            if (err){
                                console.log("error5");
                                throw err;
                                return false;
                            }
                            return true;
                        });
                    }
                    return true;
                });
                /*connection.query('UPDATE jugadores_valor SET valor="'+precio+'" WHERE id_jugador='+jugadores_id, function(err,res){
                    if (err){
                        console.log("error4");
                        throw err
                        return false
                    }
                    return true;
                });*/
                return true;
            });
            //return true;
        });
    });
    //return data_subasta
}
function isempty(str){
    return str === '' || str === undefined || str === null || typeof str === undefined || typeof str == undefined || typeof str === null || str.length === 0 || str == [];
  }
module.exports = subasta_export; 