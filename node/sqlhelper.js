var sys = require('sys');
var mysql = require('mysql');
var config = require('../config/config.json');
var connection = mysql.createConnection({
  host     : config.db_host,
  user     : config.db_usr,
  password : config.db_pwd,
  database : config.db_table
});


exports.connect = function(){
    connection.connect();
    console.log("[mysql]connected");
}


exports.query = function(querystring,callback){
    connection.query(querystring, function(err, rows, fields) {
        if (err) throw err;
        callback = callback ? callback : function(){};
        callback(rows);
    });
}

exports.end = function(){
    connection.end();
    console.log("[mysql]disconnected");
}

//保持mysql在线
function keepalive() {
  connection.query('select 1', [], function(err, result) {
    if(err) return console.log(err);
  });
}
setInterval(keepalive, 1000*60*5);