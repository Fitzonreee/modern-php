var gulp    = require('gulp'),
    sass    = require('gulp-sass'),
    rename  = require('gulp-rename'),
    cssmin  = require('gulp-minify-css'),
    concat  = require('gulp-concat'),
    uglify  = require('gulp-uglify'),
    jshint  = require('gulp-jshint'),
    csslint = require('gulp-csslint'),
    prefix  = require('gulp-autoprefixer'),
    size    = require('gulp-size');
    maps = require('gulp-sourcemaps');

gulp.task('scss', function() {
  return gulp.src('scss/all.scss')
    .pipe(maps.init())
    .pipe(sass())
    .pipe(size({ gzip: true, showFiles: true }))
    .pipe(prefix())
    .pipe(rename('styles.css'))
    .pipe(gulp.dest('dist/css'))
    .pipe(cssmin())
    .pipe(size({ gzip: true, showFiles: true }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('dist/css'));
});

gulp.task('csslint', function() {
  gulp.src('css/styles.css')
    .pipe(csslint({
      'compatible-vendor-prefixes': false,
      'box-sizing': false,
      'important': false,
      'known-properties': false
    }))
    .pipe(csslint.reporter());
});

gulp.task('js', function() {
  gulp.src('js/*.js')
    .pipe(uglify())
    .pipe(size({ gzip: true, showFiles: true }))
    .pipe(concat('global.js'))
    .pipe(gulp.dest('dist/js'));
});

gulp.task('jshint', function() {
  gulp.src('dist/js/global.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

gulp.task('watch', function() {
  gulp.watch('scss/*.scss', ['scss', 'csslint']);
  gulp.watch('js/*.js', ['jshint', 'js']);
});

gulp.task('default', ['scss', 'watch']);
// gulp.task('default', ['scss', 'csslint', 'js', 'jshint', 'watch']);
