var mysql_export = {};
var mysql = require('mysql');
var mysql_cnn;
  var db_config = {
    connectionLimit: 100, //important
    host:'localhost',
    user:'admin_portalliga',
    password:'pulpomasters',
    database:'admin_portal-liga4',
    debug: false
  };
  mysql_cnn = mysql.createPool(db_config);
mysql_export = mysql_cnn;
module.exports = mysql_export; 