var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var browserSync = require('browser-sync');
var autoprefixer = require('gulp-autoprefixer');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');

/********************************************************
* SASS                                                  *
********************************************************/
gulp.task('sass', function() {
  var stylesheetExport = './public/styles';

  return gulp.src('./styles/import.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(rename('style.css'))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest(stylesheetExport))
    .pipe(rename('style.min.css'))
    .pipe(minifyCss())
    .pipe(gulp.dest(stylesheetExport))
    .pipe(browserSync.stream());
});

/********************************************************
* SCRIPTS                                               *
********************************************************/
gulp.task('scripts', function() {
  var javascriptsExport = './public/js';

  return browserify('./javascripts/import.js')
    .bundle() // Compile the js
    .pipe(source('script.js')) //Pass desired output filename to vinyl-source-stream
    .pipe(gulp.dest(javascriptsExport)) // Output the file
    .pipe(buffer()) // convert from streaming to buffered vinyl file object
    .pipe(rename('script.min.js')) // Rename the minified version
    .pipe(uglify()) // Minify the file
    .pipe(gulp.dest(javascriptsExport)); // Output the minified file
});

/********************************************************
* SETUP BROWSER SYNC                                    *
********************************************************/
gulp.task('browsersync', function() {
  browserSync.init(null, {
    server: {
      baseDir: "./public"
    }
  });
});

/********************************************************
* WATCH TASKS                                           *
********************************************************/
gulp.task('watch', function() {
  gulp.watch(['./styles/**/*.scss'], ['sass']);
  gulp.watch(['./javascripts/**/*.js'], ['scripts']);
  // gulp.watch(['./javascripts/**/*.js'], ['scripts']); // Reload when function and twig files change
  // Reload when styles or js changes
  // gulp.watch("app/*.html").on('change', browserSync.reload);
});

/********************************************************
* DEFAULT TASKS                                         *
********************************************************/
gulp.task('default',['watch', 'browsersync']);
