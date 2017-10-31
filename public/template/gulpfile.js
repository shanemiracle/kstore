'use strict';
// 引入gulp
var gulp = require('gulp'),
    less = require('gulp-less'),
    connect=require('gulp-connect'),
    concat=require('gulp-concat'),
    change=require('gulp-changed'),
    uglify=require('gulp-uglify'),
    watch = require('gulp-watch'),
    // jshint=require('gulp-jshint'),
    rename=require('gulp-rename'),
    gutil=require('gulp-util'),
    sourcemaps = require('gulp-sourcemaps'),
    queue = require('gulp-sequence'),   
    css = './static/css',
    lessAll=['static/less/main.less'],
    path={js:'./static/**/**/*.js'};

gulp.task("less",function(){
    return gulp.src(lessAll)
     .pipe(change(css))
     .pipe(less())
     .pipe( gulp.dest( css ));
});

// gulp.task('concat',function(){
//     return gulp.src(path.js)
//           // .pipe(jshint())
//           // .pipe(jshint.reporter('default'))
//           .pipe(concat('all.js'))
//           .pipe( gulp.dest('./static/js/'))
//           // .pipe(rename({ suffix: '.min' }))
//           // .pipe(uglify())
//           .pipe( gulp.dest('./static/js/'));
//     });

gulp.task('connect', function() {
    connect.server({
        root: './',
        livereload: true,
        port: 9797
    });
});

gulp.task("watch",function(event){
    gulp.watch('./view/*.html',function(){
        gulp.src('./').pipe(connect.reload());
    });
    gulp.watch('static/less/**/*.less',['less']);
  
    // gulp.watch('static/js/**/*.js', ['concat']);
});


gulp.task("dev", function(cb){
    queue('less','connect','watch')(cb)
});