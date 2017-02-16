var gulp         = require('gulp');
var minifyCss    = require('gulp-minify-css');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('site', function(){
    return gulp.src('web/scss/site/site.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer('last 4 versions'))
        // .pipe(minifyCss())
        .pipe(gulp.dest('web/css'))
});

gulp.task('admin', function(){
    return gulp.src('web/scss/admin/admin.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer('last 4 versions'))
        .pipe(minifyCss())
        .pipe(gulp.dest('web/css'))
});


gulp.task('watcher',function(){
    gulp.watch('web/scss/*.scss', ['css']);

    gulp.watch('web/scss/admin/*.scss', ['admin']);
    gulp.watch('web/scss/admin/partials/*.scss', ['admin']);

    gulp.watch('web/scss/site/*.scss', ['site']);
    gulp.watch('web/scss/site/partials/*.scss', ['site']);
});

//____________________________
gulp.task('default', ['watcher']);