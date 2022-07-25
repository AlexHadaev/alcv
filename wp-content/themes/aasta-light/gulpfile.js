var gulp         = require('gulp');
var sass         = require('gulp-sass');
var browserSync    = require('browser-sync').create();
var autoprefixer   = require('gulp-autoprefixer');
var plumber      = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps'),
  concat  = require('gulp-concat'),
  uglify  = require('gulp-uglify'),
  rename  = require('gulp-rename');

const config = require('./projectConfig.json');
const devDomain = config.devDomain;
const srcScripts = config.srcScripts;

gulp.task('browser-sync', function(done) {
  browserSync.init({
    // server: {
    //     baseDir: './'
    // },
    notify: false,
    open: false,
    port: 5050,
    https: true,
    files: ['{inc,template-parts,page-templates}/**/*.php', '*.php'],
    // reloadDelay: 5000,
    proxy: devDomain,
    snippetOptions: {
      whitelist: ['/wp-admin/admin-ajax.php'],
      blacklist: ['/wp-admin/**']
    }
  });
  // browserSync.watch('./').on('change', browserSync.reload);
  gulp.watch('./assets/scripts/*.js', gulp.series('js')).on('change', browserSync.reload);
  gulp.watch(['./assets/scss/*/*.scss', './assets/scss/*.scss'], gulp.series('sass'));
  done()
});

gulp.task('sass', function(done){
  gulp.src('./assets/scss/main.scss', {
    sourcemaps: true
  })
    .pipe(plumber({
      errorHandler : function(err) {
        console.log(err);
        this.emit('end');
      }
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({errLogToConsole: true}))
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(rename({
      // dirname: "main/text/ciao",
      // basename: "aloha",
      // prefix: "uptop-",
      // extname: ".md"
      suffix: '.min'
    }))
    .pipe(autoprefixer({
      // browsers: ['last 4 versions'],
      cascade: false
    }))
    .pipe(sourcemaps.write('/'))
    .pipe(gulp.dest('./assets/dist/'))
    .pipe(browserSync.reload({stream: true}));
  done()
});

gulp.task('js', function () {
  return gulp
    .src(srcScripts, {
      sourcemaps: true
    })
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(concat('main.min.js'))
    .pipe(sourcemaps.write('/'))
    .pipe(gulp.dest('./assets/dist/'));
});

gulp.task('watch', gulp.series('browser-sync', function(done) {
  done()
}));

