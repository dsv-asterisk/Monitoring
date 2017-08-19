const gulp 		= require("gulp");
const sass 		= require("gulp-sass");
const notify 	= require("gulp-notify");
const connect	= require("gulp-connect");
//const php2html	= require("php2html"); //https://www.npmjs.com/package/php2html

//*-----------------------*\
// Monitorar estes arquivos
//*-----------------------*/
var arquivos = ['index.html','css/style.css'];

//*-----------------------*\
// Processa o Sass
//*-----------------------*/
gulp.task("sass", function(){
	return gulp.src(['./source/scss/style.scss'])
				.pipe(sass()).on("error", notify.onError({title:"Erro ao compilar", message:"<%= error.message %>"}))
				.pipe(gulp.dest("./css"))
});

//*-----------------------*\
// Monitorando os Arquivos
//*-----------------------*/
gulp.task('monitorando', function(){
	gulp.src(arquivos)
	.pipe(connect.reload())
});

//*-----------------------*\
// CRIANDO SERVIDOR: localhost:8181
//*-----------------------*/
gulp.task('connect', function(){
	connect.server({ livereload: true, port: 8181 });
});

//*-----------------------*\
// 	WATCH
//*-----------------------*/
gulp.task("sass:watch", function(){
	gulp.watch("./source/**/*.scss", ['sass']);
	gulp.watch(arquivos, ['monitorando']);
});

//*-----------------------*\
// DEFAULT.
//*-----------------------*/
gulp.task("default",['sass', 'connect','sass:watch']);