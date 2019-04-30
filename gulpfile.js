var gulp = require('gulp');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var babelify = require('babelify');
var vueify = require('gulp-vueify');
 
gulp.task('vueify', function () {
  return gulp.src('js/componentes/portada/portada-index.vue')
    .pipe(vueify())
    .pipe(gulp.dest('./dist'));
});

gulp.task('js', () => {
    return browserify('js/vue/apps/app.js')
        .transform('babelify', {presets: ["@babel/preset-env",
        "@babel/preset-react"]})
        .bundle()
        .pipe(source('vueapps.js')) // Readable Stream -> Stream Of Vinyl Files
        .pipe(buffer()) // Vinyl Files -> Buffered Vinyl Files
        // Pipe Gulp Plugins Here
        .pipe(gulp.dest('dist'));
});
/*
gulp.task('watch', () => {
    gulp.watch('js/vue/apps/*.js', gulp.series('js'));
});*/

gulp.task('default', gulp.series('js','vueify'));