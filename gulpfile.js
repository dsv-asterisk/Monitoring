const gulp 		= require("gulp");
const sass 		= require("gulp-sass");
const notify 	= require("gulp-notify");
const connect	= require("gulp-connect");
const uglifycss = require('gulp-uglifycss');
const recursiveConcat = require('gulp-recursive-concat');
const cleanCSS = require('gulp-clean-css');

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
				.pipe(cleanCSS({compatibility: 'ie8'}))								
				.pipe(uglifycss({
					"maxLineLen": 80,
					"uglyComments": true
				  }))				
				.pipe(gulp.dest("./css"))
});

//*-----------------------*\
// Processa o JS
//*-----------------------*/
gulp.task('js', function(){
	return gulp.src('source/**/*.js')
	.pipe(recursiveConcat({extname: ".min.js", outside: true}))
	.pipe(gulp.dest('js/'));
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

gulp.task("js:watch", function(){
	gulp.watch("./source/**/*.js", ['js']);
	gulp.watch(arquivos, ['monitorando']);
});

//*-----------------------*\
// DEFAULT.
//*-----------------------*/
gulp.task("default",['sass', 'js', 'connect','sass:watch','js:watch']);