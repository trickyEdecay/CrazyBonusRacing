var fs = require('fs')
    , http = require('http')
    , socketio = require('socket.io')
    , path = require('path')
    , url = require('url')
    , mime = require('./mime').types
    , router = require('./router')
    , mysql = require('./sqlhelper');


var port = 8080;

function serverStarted(){
    console.log('Listening at: http://localhost:8080');
    console.log('这里用作投影幕上的显示,采用服务器推送的形式减轻服务器的压力');
    mysql.connect();
}

function onRequest(req,res){
    
    var pathname = url.parse(req.url).pathname;

    pathname = pathname == "/" ? "/index.html" : pathname;
    
    var realPath = router.getRealPathName(pathname);
    
    var ext = pathname.split(".").pop().toLowerCase();
    ext = ext ? ext : 'unknown';
    var contentType = mime[ext] || "text/plain";
    
    fs.exists(realPath, function (exists) {
       if(!exists){
            res.writeHead(404, {'Content-Type': 'text/plain'});
            res.write("This request URL " + pathname + " was not found on this server.");
            res.end();
       }else{
            fs.readFile(realPath,"binary",function(err,file){
                if(err){
                    res.writeHead(500, {'Content-Type': contentType});
                    res.write(err);
                    res.end();
                }else{
                    res.writeHead(200, {'Content-Type': contentType});
                    res.write(file,"binary");
                    res.end();
                }
            });
       }
    });
                
                
    
    
    
}

var server = http.createServer(onRequest).listen(port, serverStarted);







socketio.listen(server).on('connection', function (socket) {
    
    
    
    
    
    socket.on('showsponsor',function(msg){
        console.log('Request for ShowSponsor');
        
        
        

        var packs = {};
        var rankinglistpack = {};

        mysql.query("select * from question_config where keyname='rankinglistpack' limit 1",function(rows){
            var rankinglistpack = {};
            rankinglistpack = JSON.parse(rows[0].value);

            packs.rankinglistpack = rankinglistpack;
            socket.broadcast.emit('showsponsor', packs);
        });

    });
    
    
    
    
    socket.on('getrankinglist',function(data,fn){
        console.log('Request for GetRankingList');
        
        mysql.query("select * from question_config where keyname='rankinglistpack' limit 1",function(rows){
            var rankinglistpack = {};
            rankinglistpack = JSON.parse(rows[0].value);
            fn(rankinglistpack);
        });
        
    });
    
    
    
    
    socket.on('showidc',function(msg){
        console.log('Request for ShowIdc');
        
        mysql.query("select * from question_config where keyname='idc' limit 1",function(rows){
            var idcpack = {};
            idcpack.idc = rows[0].value;
            socket.broadcast.emit('showidc', idcpack);
        });
    });
    
    
    
    socket.on('showquestion',function(msg){
        console.log('Request for ShowQuestion');
        
        mysql.query("select * from question_config where keyname='questionpack' limit 1",function(rows){
            var questionpack = {};
            questionpack = JSON.parse(rows[0].value);
            socket.broadcast.emit('showquestion', questionpack);
        });
    });
    
    
    
    socket.on('showkey',function(msg){
        console.log('Request for ShowKey');
        socket.broadcast.emit('showkey', msg);
    });
    
    socket.on('showhonor',function(msg){
        console.log('Request for ShowHonor');
        var tempdate = new Date();
        var year = tempdate.getFullYear();
        
        mysql.query("select score,name from question_people where isbanned=0 and lastactiveyear='"+year+"' order by ranking asc",function(rows){
            var honorpack = {};
            honorpack.people = [];
            var row;
            var i =0;
            var limitsum = 1000;
            var currentsum = 0;
            for(i;i<rows.length;i++){
                row = rows[i];
                if(currentsum+row.score<=limitsum){
                    honorpack.people[i] = {};
                    honorpack.people[i].name = row.name;
                    honorpack.people[i].score = row.score;
                    currentsum = currentsum+row.score;
                }else{
                    if(currentsum<limitsum){
                        honorpack.people[i] = {};
                        honorpack.people[i].name = row.name;
                        honorpack.people[i].score = limitsum-currentsum;
                    }
                    break;
                }
                
            }
            socket.broadcast.emit('showhonor', honorpack);
        });
        
    });
    
    
    
    
    
    
    
    
    
    
});