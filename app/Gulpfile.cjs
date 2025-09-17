const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));

gulp.task('sass', function () {
  return gulp.src('resources/sass/**/*.scss')
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(gulp.dest('public/css'));
});

gulp.task('watch', function () {
  gulp.watch('resources/sass/**/*.scss', gulp.series('sass'));
});
