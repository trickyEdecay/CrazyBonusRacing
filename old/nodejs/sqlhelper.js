var sys = require('sys');
  
var mysql = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'kkvip',
  password : 'kkvipkkvip',
  database : '667332'
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