var gulp = require("gulp");
var uglify = require("gulp-uglify");
var htmlmin = require("gulp-htmlmin");
var filter = require('gulp-filter');
var useref = require("gulp-useref");
var revReplace = require('gulp-rev-replace');
var rev = require('gulp-rev');
var revCollector = require('gulp-rev-collector');
var through = require('through2');
var less = require('gulp-less');
var csso = require('gulp-csso');
var LessPluginCleanCSS = require('less-plugin-clean-css'),
    cleanCSSPlugin = new LessPluginCleanCSS({advanced: true});
// var gutil = require('gulp-util');
// var rename = require("gulp-rename");
var replace = require('gulp-replace');
// var changed = require('gulp-changed');
var del = del = require('del');
var gulpif = require('gulp-if');
var runSequence = require('run-sequence');
var config = require('./config/config.json');

var debug = config.debug;

var paths = {
    src: {
        pages: ["front/**/*.php","front/**/*.html"],
        assets: "front/assets",
        js: "front/assets/js/**/*.js",
        style: ["front/assets/style/**/*.css","front/assets/style/**/*.less"],
        img: "front/assets/img/**/*.*",
        font: "front/assets/fonts/**/*.*"
    },
    dist:{
        pages: "pages/",
        assets: "assets",
        js: "assets/js",
        css: "assets/css",
        img: "assets/img",
        font: "assets/fonts"
    },
    rev:{
        all: "rev/**/*.json",
        js: "rev/js",
        css: "rev/css"
    }
};

//构建系统
gulp.task("build",function(){

    if(debug){
        //开发模式下
        return runSequence("clean:assets","build:img","build:font","clean:pages","build:scripts","build:styles","build:pages","watch");
    }else{
        //产品模式下
        return runSequence("clean:assets","clean:pages","build:production");
    }

});
//构建系统
gulp.task("build:all",function(){

    if(debug){
        //开发模式下
        return runSequence("clean:assets","build:img","build:font","clean:pages","build:scripts","build:styles","build:pages");
    }else{
        //产品模式下
        return runSequence("clean:assets","clean:pages","build:production");
    }

});

gulp.task('watch',function(){
    gulp.watch(paths.src.js,function(){return runSequence("build:scripts","build:pages");});
    gulp.watch(paths.src.style,function(){return runSequence("build:styles","build:pages");});
    gulp.watch(paths.src.img,function(){return runSequence("build:img");});
    gulp.watch(paths.src.pages,function(){return runSequence("build:pages");});
});

gulp.task("build:scripts",function(){
    return gulp.src(paths.src.js)
        .pipe(rev())
        .pipe(gulp.dest(paths.dist.js))
        .pipe(rev.manifest())
        .pipe(gulp.dest(paths.rev.js));
});

gulp.task("build:styles",function(){
    return gulp.src(paths.src.style)
        .pipe(rev())
        .pipe(devCssUrls('\/front',config.base_dir))
        .pipe(less())
        .pipe(gulp.dest(paths.dist.css))
        .pipe(rev.manifest())
        .pipe(gulp.dest(paths.rev.css));
});

gulp.task("build:img",function(){
    return gulp.src(paths.src.img)
        .pipe(gulp.dest(paths.dist.img));
});
gulp.task("build:font",function(){
    return gulp.src(paths.src.font)
        .pipe(gulp.dest(paths.dist.font));
});

gulp.task("build:pages",function(){
    var manifest = gulp.src(paths.rev.all);
    return gulp.src(paths.src.pages)
        .pipe(revReplace({
            manifest: manifest,
            replaceInExtensions: ['.js', '.css', '.html', '.hbs','.php']
        }))
        .pipe(devRevUrls('\/front',config.base_dir))
        .pipe(replace(/assets\/style/g, 'assets\/css'))
        .pipe(gulp.dest(paths.dist.pages));
});

gulp.task('clean:assets',function(){
    del([paths.dist.assets+'/*']);
});
gulp.task('clean:pages',function(){
    del([paths.dist.pages+'/*']);
});


gulp.task("build:production",function(){

    var options = {
        removeComments: true,  //清除HTML注释
        collapseWhitespace: false,  //压缩HTML
        collapseBooleanAttributes: true,  //省略布尔属性的值 <input checked="true"/> ==> <input checked />
        removeEmptyAttributes: true,  //删除所有空格作属性值 <input id="" /> ==> <input />
        removeScriptTypeAttributes: true,  //删除<script>的type="text/javascript"
        removeStyleLinkTypeAttributes: true,  //删除<style>和<link>的type="text/css"
        minifyJS: true,  //压缩页面JS
        minifyCSS: true  //压缩页面CSS
    };


    var jsFilter = filter("**/*.js", { restore: true });
    var cssFilter = filter(["**/*.css","**/*.less"], { restore: true });
    var phpFilter = filter(paths.src.pages, { restore: true });
    return gulp.src(paths.src.pages,{cwd:"./"})
        .pipe(useref({
            searchPath: "./"

        }))
        .pipe(jsFilter)
        .pipe(uglify())
        .pipe(gulp.dest("./"))
        .pipe(jsFilter.restore)
        .pipe(cssFilter)
        .pipe(devCssUrls('\/front',config.cdn_host))
        .pipe(less())
        .pipe(csso())
        .pipe(gulp.dest("./"))
        .pipe(cssFilter.restore)
        .pipe(phpFilter)
        .pipe(devRevUrls('\/front\/assets','\/assets'))
        .pipe(devRevUrls('\/assets',config.cdn_host+"\/assets"))
        .pipe(htmlmin(options))
        .pipe(gulp.dest(paths.dist.pages));
});

// //所有相关的路径
// var paths = {
//     //以下路径均相对于 gulpfile.js
//     //源代码路径
//     src:"src",
//     //适用于前端测试的生成代码路径
//     dev:"dist",
//     //用于后端的生成代码路径
//     build:"build",
//     scripts: ["src/**/assets/js/*.js"],
//     img: ["src/**/assets/img/*.*"],
//     style: ["src/**/assets/style/*.style"],
//     rev:['rev/**/*.json'],
//     html:["src/**/*.html"],
//     lib:["src/lib/**"]
//
// };
//
// //生产环境中,所有的静态文件(js/css/img)都会被拷贝到这个文件夹下面.(Javaweb中的静态文件分离)
// var buildstaticpath = "/static";
//
// //开发环境中的根目录路径
// var devwebbase = "/qingsongshuiwu/dist";
//
// //前端中连接后端的浏览器访问地址
// var frontendurl = "http://localhost:8080/"
//
// //开发模式(false) 和 生产模式(true)
// var build = false;
//
// //设置为 生产模式
// gulp.task("set_env_build",function(){
//     gutil.log(gutil.colors.green("本过程以 生产模式 进行"));
//     build = true;
// });
//
// //设置为 开发模式
// gulp.task("set_env_debug",function(){
//     build = false;
// });
//
// //取得 生成路径(html/php/..)
// function getBuildFolder(){
//     return build?"build":"dist";
// }
//
// //取得 静态文件(js/css/img) 的生成路径
// function getBuildStaticFolder(){
//     return build?getBuildFolder()+buildstaticpath:getBuildFolder();
// }
//
// //显示错误 使用方法: logerror(someplugin())
// function logerror(stream){
//     stream.on("error",function(e){
//         gutil.log(gutil.colors.red('[error] ')+e);
//         this.emit("end");
//     });
//     return stream;
// }
//
// //显示信息
// function info(options){
//     var _options = {
//         singleflag : false,
//         taskname : "none",
//         msg : function(){return "";}
//     }
//     var _msg = "";
//     for(var key in options){
//         _options[key] = options[key];
//     }
//     if(_options.singleflag){
//         if(typeof _options.msg === "string"){
//             _msg = _options.msg
//         }else if(typeof _options.msg === "function"){
//             _msg = _options.msg("");
//         }
//         gutil.log(gutil.colors.gray("["+_options.taskname+"] " + _msg));
//     }
//     return through.obj(function (file, enc, cb) {
//         if(!_options.singleflag){
//             if(typeof _options.msg === "string"){
//                 _msg = _options.msg
//             }else if(typeof _options.msg === "function"){
//                 _msg = _options.msg(file);
//             }
//             gutil.log(gutil.colors.gray("["+_options.taskname+"] " + _msg));
//         }
//         this.push(file);
//         cb();
//     });
// }
//
// //进行less操作
// gulp.task("build:css",function(){
//     return gulp.src(paths.style,{base:paths.src})
//         .pipe(info({taskname:"build:css",msg:"开始处理less",singleflag:true}))
//         .pipe(changed(getBuildStaticFolder()))
//         .pipe(info({taskname:"build:css",msg:function(f){return "构建"+f.history;}}))
//         .pipe(rev())
//         .pipe(gulpif(build,buildCssUrls(),devCssUrls()))
//         .pipe(logerror(style()))
//         .pipe(rename(function (path) {
//             path.dirname = path.dirname.replace("\style","\css");
//         }))
//         .pipe(gulp.dest(getBuildStaticFolder()))
//         .pipe(rev.manifest())
//         .pipe(replace(/(.*)css(.*\.style.*)/g, '$1less$2'))
//         .pipe(delCssRevs())
//         .pipe(gulp.dest('./rev/css'));
// });
//
// //压缩js
// gulp.task("build:js",function(){
//     return gulp.src(paths.scripts,{base:paths.src})
//         .pipe(info({taskname:"build:js",msg:"开始处理js",singleflag:true}))
//         .pipe(info({taskname:"build:js",msg:function(f){return "构建"+f.history;}}))
//         .pipe(info("script",function(f){return "开始压缩script"}))
//         .pipe(rev())
//         .pipe(changed(getBuildStaticFolder()))
//         .pipe(gulpif(build,buildajaxurl()))
//         .pipe(logerror(uglify()))
//         .pipe(gulp.dest(getBuildStaticFolder()))
//         .pipe(rev.manifest())
//         .pipe(delJsRevs())
//         .pipe(gulp.dest('./rev/js'));
// });
//
// //处理图片
// gulp.task("build:img",function(){
//     return gulp.src(paths.img,{base:paths.src})
//         .pipe(info({taskname:"build:img",msg:"搬运图片~",singleflag:true}))
//         .pipe(gulp.dest(getBuildStaticFolder()));
// });
//
//
// //生成html
// gulp.task('build:html', function(){
//     var options = {
//         removeComments: true,  //清除HTML注释
//         collapseWhitespace: true,  //压缩HTML
//         collapseBooleanAttributes: true,  //省略布尔属性的值 <input checked="true"/> ==> <input checked />
//         removeEmptyAttributes: true,  //删除所有空格作属性值 <input id="" /> ==> <input />
//         removeScriptTypeAttributes: true,  //删除<script>的type="text/javascript"
//         removeStyleLinkTypeAttributes: true,  //删除<style>和<link>的type="text/css"
//         minifyJS: true,  //压缩页面JS
//         minifyCSS: true  //压缩页面CSS
//     };
//     return gulp.src(paths.rev.concat(paths.html))
//         .pipe(revCollector({
//             replaceReved: true,
//             dirReplacements: {
//                 '': function(manifest_value) {
// //                        if(build){
// //                            return buildstaticpath+'/'+manifest_value;
// //                        }else{
// //                        }
//                     return manifest_value;
//
//                 }
//             }
//         }))
//         .pipe(info({taskname:"build:html",msg:"开始处理html",singleflag:true}))
//         .pipe(info({taskname:"build:html",msg:function(f){return "压缩/替换url: "+f.history;}}))
//         .pipe(gulpif(build,revstatic(),devRevUrls()))
//         .pipe(gulpif(build,buildroute()))
//         .pipe(gulpif(build,buildajaxurl()))
//         .pipe(htmlmin(options))
//         .pipe(gulp.dest(getBuildFolder()));
// });
//
//
// //复制项目中使用的依赖(lib)
// gulp.task('copylib',function(){
//     return gulp.src(paths.lib,{base:paths.src})
//         .pipe(info({taskname:"copylib",msg:"搬运lib",singleflag:true}))
//         .pipe(gulp.dest(getBuildStaticFolder()))
// });
//
//
// //清空多余的css版本文件
// function delCssRevs(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         var deletearr = [];
//         var pathprefix = build?paths.build:paths.dev;
//         deletearr.push(getBuildFolder()+"/**/assets/css/*.css");
//
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 var revfile = JSON.parse(src);
//                 for(var key in revfile){
//                     deletearr.push("!"+pathprefix+"/"+revfile[key]);
//                 }
//             }
//             this.push(f);
//         },this);
//         del(deletearr);
//         cb();
//     });
// }
//
// //清空多余的js版本文件
// function delJsRevs(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         var deletearr = [];
//         var pathprefix = build?paths.build:paths.dev;
//         deletearr.push(getBuildFolder()+"/**/assets/js/*.js");
//
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 var revfile = JSON.parse(src);
//                 for(var key in revfile){
//                     deletearr.push("!"+pathprefix+"/"+revfile[key]);
//                 }
//             }
//             this.push(f);
//         },this);
//         del(deletearr);
//         cb();
//     });
// }
//
//
// //生产环境中使用这个来 替换 html中所有 使用了 lib文件夹 内容的路径
// function revstatic(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 var r ={
//                     regexp : new RegExp('\(<link\|<img\|<script\)\(\.\*\)\(href\|src\)\(="\)\/src\(\.\*\)\(>\)','g'),
//                     replacement: '$1$2$3$4'+buildstaticpath+'$5$6'
//
//                 };
//                 src = src.replace(r.regexp, r.replacement);
// //            console.log("'"+'(<link|<script)(.*)(href|src)(=")lib(.*)(>)'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
//                 f.contents = new Buffer(src);
//             }
//             this.push(f);
//         },this);
//         cb();
//     });
// }
//
// //开发环境中使用这个来 替换 html中所有 文件路径
function devRevUrls(reg,replacement){
    return through.obj(function (file, enc, cb) {
        var mutable = [];
        mutable.push(file);
        mutable.forEach(function (f){
            if (!f.isNull()) {
                var src = f.contents.toString('utf8');

                //用这个来替换node部分的链接，以保证node部分没有端口号的静态资源请求
                if(f.history[0].replace(/\//g,'\\').indexOf("\\front\\projector")>=0 && debug){
                    if(replacement.indexOf("//localhost")<0){
                        replacement = "//localhost"+replacement;
                    }
                }else{
                    if(replacement.indexOf("//localhost")>=0 && debug){
                        replacement = replacement.replace("//localhost","");
                    }
                }

                var r ={
                    regexp : new RegExp('\(<link\|<img\|<a\|<script\)\(\.\*\)\(href\|src\)\(="\)'+reg+'\(\.\*\)\(>\)','g'),
                    replacement: '$1$2$3$4'+replacement+'$5$6'

                };
                src = src.replace(r.regexp, r.replacement);
//            console.log("'"+'(<link|<script)(.*)(href|src)(=")lib(.*)(>)'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
                f.contents = new Buffer(src);
            }
            this.push(f);
        },this);
        cb();
    });
}
//
//开发环境中使用这个来 替换 css中的 url
function devCssUrls(reg,replacement){
    return through.obj(function (file, enc, cb) {
        var mutable = [];
        mutable.push(file);
        mutable.forEach(function (f){
            if (!f.isNull()) {
                var src = f.contents.toString('utf8');

                //用这个来替换node部分的链接，以保证node部分没有端口号的静态资源请求
                if(f.history[0].replace(/\//g,'\\').indexOf("\\style\\projector")>=0 && debug){
                    if(replacement.indexOf("//localhost")<0){
                        replacement = "//localhost"+replacement;
                    }
                }else{
                    if(replacement.indexOf("//localhost")>=0 && debug){
                        replacement = replacement.replace("//localhost","");
                    }
                }

                var r ={
                    regexp : new RegExp('\(url\.\*\\(\.\*\)'+reg+'\(\.\*\)','g'),
                    replacement: '$1'+replacement+'$2'

                };
                src = src.replace(r.regexp, r.replacement);
           // console.log("'"+'(url.*\()/src(.*)(\))'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
                f.contents = new Buffer(src);
            }
            this.push(f);
        },this);
        cb();
    });
}
//
// //生产环境中使用这个来 替换 css中的 url
// function buildCssUrls(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 var r ={
//                     regexp : new RegExp('/src(.*)(\)','g'),
//                     replacement: ''+buildstaticpath+'$1$2'
//
//                 };
//                 src = src.replace(r.regexp, r.replacement);
// //            console.log("'"+'(<link|<script)(.*)(href|src)(=")lib(.*)(>)'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
//                 f.contents = new Buffer(src);
//             }
//             this.push(f);
//         },this);
//         cb();
//     });
// }
//
// //生产环境中使用这个来替换 html中所有使用了 ajax 的 url
// function buildajaxurl(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 var r ={
//                     regexp : new RegExp('(url.*:.*")('+frontendurl+')(.*)(")','g'),
//                     replacement: '$1\/$3$4'
//
//                 };
//                 src = src.replace(r.regexp, r.replacement);
// //            console.log("'"+'(<link|<script)(.*)(href|src)(=")lib(.*)(>)'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
//                 f.contents = new Buffer(src);
//             }
//             this.push(f);
//         },this);
//         cb();
//     });
// }
//
// //使用 routermap 来替换前端的a标签中href变为后端路径
// function buildroute(){
//     return through.obj(function (file, enc, cb) {
//         var mutable = [];
//         mutable.push(file);
//         mutable.forEach(function (f){
//             if (!f.isNull()) {
//                 var src = f.contents.toString('utf8');
//                 for(var srcroute in routermap){
//                     var r ={
//                         regexp : new RegExp('\(<a\)\(\.\*\)\(href\|src\)\(="\)'+srcroute.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+'\(\.\*\)\(>\)','g'),
//                         replacement: '$1$2$3$4'+routermap[srcroute]+'$5$6'
//
//                     };
//                     src = src.replace(r.regexp, r.replacement);
//                 }
//
// //            console.log("'"+'(<link|<script)(.*)(href|src)(=")lib(.*)(>)'.replace(/[\-\[\]\{\}\(\)\*\+\?\.\^\$\|\/\\]/g, "\\$&")+"'");
//                 f.contents = new Buffer(src);
//             }
//             this.push(f);
//         },this);
//         cb();
//     });
// }
//
// //清除 生产环境 目录
// gulp.task('clean:build',function(){
//     del([paths.build+'/*']);
// });
//
//
// //清除 开发环境 目录
// gulp.task('clean:dev',function(){
//     del([paths.dev+'/*']);
// });
//
// gulp.task('watch',function(){
//     gulp.watch(paths.scripts,function(){return runSequence("build:js","build:html");});
//     gulp.watch(paths.style,function(){return runSequence("build:css","build:html");});
//     gulp.watch(paths.img,function(){return runSequence("build:img");});
//     gulp.watch(paths.html,function(){return runSequence("build:html");});
// });
//
// gulp.task('build:dev',function(){
//     return runSequence("clean:dev","build:css","build:js","build:img","build:html","copylib");
// });
//
// gulp.task('build:production',function(){
//     return runSequence("set_env_build","clean:build","build:css","build:js","build:img","build:html","copylib");
// });
//
// gulp.task('default',function(){return runSequence("build:dev","watch");});