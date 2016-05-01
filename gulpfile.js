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
var ini = require('ini');
var fs = require('fs');
var config = ini.parse(fs.readFileSync('./config.ini', 'utf-8'));

/********************************************************
* SASS                                                  *
********************************************************/
gulp.task('sass', function() {
  var stylesExport = '.' + config.styles.export;

  return gulp.src('.' + config.styles.import)
    .pipe(sass().on('error', sass.logError))
    .pipe(rename(config.styles.main))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest(stylesExport))
    .pipe(rename(config.styles.min))
    .pipe(minifyCss())
    .pipe(gulp.dest(stylesExport))
    .pipe(browserSync.stream());
});

/********************************************************
* SCRIPTS                                               *
********************************************************/
gulp.task('scripts', function() {
  var javascriptsExport = '.' + config.javascripts.export;

  return browserify('.' + config.javascripts.import)
    .bundle() // Compile the js
    .pipe(source(config.javascripts.main)) //Pass desired output filename to vinyl-source-stream
    .pipe(gulp.dest(javascriptsExport)) // Output the file
    .pipe(buffer()) // convert from streaming to buffered vinyl file object
    .pipe(rename(config.javascripts.min)) // Rename the minified version
    .pipe(uglify()) // Minify the file
    .pipe(gulp.dest(javascriptsExport)); // Output the minified file
});

/********************************************************
* SETUP BROWSER SYNC                                    *
********************************************************/
gulp.task('browsersync', function() {
  browserSync.init(null, {
    proxy: config.environment.url,
    files: ['.' + config.dirs.public + '/**/*.*'],
  });
});

// create a task that ensures the `js` task is complete before
// reloading browsers
gulp.task('scripts-watch', ['scripts'], browserSync.reload);

/********************************************************
* WATCH TASKS                                           *
********************************************************/
gulp.task('watch', function() {
  gulp.watch(['.' + config.styles.dir + '/**/*.scss'], ['sass']);
  gulp.watch(['.' + config.javascripts.dir + '/**/*.js'], ['scripts-watch']);
  gulp.watch([
    '.' + config.dirs.models + '/**/*',
    '.' + config.dirs.views + '/**/*',
    '.' + config.dirs.helpers + '/**/*',
    '.' + config.dirs.routes + '/**/*',
    '.' + config.dirs.media + '/**/*',
    '.' + config.dirs.public + '/*',
    '.' + config.dirs.public + '/.*',
    './app.php',
    './config.ini',
  ]).on('change', browserSync.reload);
});

/********************************************************
* DEFAULT TASKS                                         *
********************************************************/
gulp.task('default',['watch', 'browsersync']);
