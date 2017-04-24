var mime = require('./mime').types
    , path = require('path');

function getRealPathName(pathname){
    var realPath = "";
    pathname = pathname == "/" ? "/projector.html" : pathname;
    
    var ext = pathname.split(".").pop().toLowerCase();
    ext = ext ? ext : 'unknown';
    
    var folder = pathname.split("/")[1];
    
    
    
    if(ext == "css" || ext == "less"){
        realPath = "../page/css"+pathname;
    }else if(ext == "jpg" || ext == "png"){
        realPath = "../page/img"+pathname;
    }else if(folder == "plugin" && ext == "js"){
        realPath = "../plugin/js"+pathname.replace(/plugin\//,"");
        console.log(realPath);
    }else if(ext == "html"){
        realPath = "./pages/projector/"+pathname;
    }else{
        realPath = "."+pathname;
    }
    console.log("GET From "+realPath);
    return realPath;
}


exports.getRealPathName = getRealPathName;