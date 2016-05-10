var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var modernizr = require('gulp-modernizr');
var browserSync = require('browser-sync');
var autoprefixer = require('gulp-autoprefixer');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var ini = require('ini');
var fs = require('fs');
var config = ini.parse(fs.readFileSync('./config.ini', 'utf-8'));
var sassImportJson = require('gulp-sass-import-json');
var svgstore = require('gulp-svgstore');
var svgmin = require('gulp-svgmin');
var replace = require('gulp-replace');
var validator = require('html-validator');
var request = require('request');
var sitemap = require('sitemapper');

// Module wide vars
var sassFiles = '.' + config.styles.dir + '/**/*';
var jsFiles = '.' + config.javascripts.dir + '/**/*';
var javascriptsExport = '.' + config.javascripts.export;

function logPageErrors(url) {
  var options = {format: 'json'};

  request(url, function(error, response, body) {
    if (!error && response.statusCode == 200) {
      options.data = body;

      validator(options, function(err, data) {
        if (err) {
          throw err;
        }

        if (data.messages.length) {
          data.url = url;
          console.log(data.url);
        }
      });
    }
  });
}

gulp.task('validatePages', function() {
  var sitemapVar = config.environment.url + '/sitemap.xml';

  sitemap.getSites(sitemapVar, function(err, sites) {
    if (!err) {
      for (var i = 0; i < sites.length; i++) {
        logPageErrors(sites[i]);

        if (i > 5) {
          break;
        }
      }
    } else {
      console.log(err);
    }
  });
});

// Strip unnecessary tags from svgs
gulp.task('svgCombine', function() {
  return gulp.src('./icons/illustrator/*.svg')
    .pipe(replace(/<\/*switch>/g, ''))
    .pipe(replace(/ id=".+?"/g, ''))
    .pipe(replace(/ class=".+?"/g, ''))
    .pipe(svgmin({
      js2svg: {
        pretty: false
      },
      plugins: [{
        removeDoctype: true,
      }, {
        collapseGroups: true,
      }, {
        removeDimensions: true,
      }, {
        removeStyleElement: true,
      }, {
        removeAttrs: 'id',
      }]
    }))
    .pipe(svgstore())
    .pipe(replace(/<\?xml.+?"\?>/g, ''))
    .pipe(replace(/<!.+?>/g, ''))
    .pipe(rename('icons.svg'))
    .pipe(gulp.dest('./icons'));
});

/********************************************************
* MODERNIZR                                             *
********************************************************/

gulp.task('modernizr', function() {
  gulp.src([sassFiles, jsFiles])
    .pipe(modernizr())
    .pipe(uglify()) // Minify the file
    .pipe(gulp.dest(javascriptsExport));
});

/********************************************************
* SASS                                                  *
********************************************************/
gulp.task('sass', function() {
  var stylesExport = '.' + config.styles.export;

  return gulp.src('.' + config.styles.import)
    .pipe(sassImportJson())
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
  return browserify('.' + config.javascripts.import)
    .bundle()
    .on('error', function(err) {
      console.log(err.message);
      this.emit('end');
    })
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
  gulp.watch([sassFiles], ['sass']);
  gulp.watch([jsFiles], ['scripts-watch']);
  gulp.watch('./icons/illustrator/**/*', ['svgCombine']);
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
