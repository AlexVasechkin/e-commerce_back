const gulp = require('gulp');

const distPath = '../dist';

gulp.task('copy-src', () => gulp
  .src('./src/**/*.*')
  .pipe(gulp.dest(distPath + '/src'))
);

gulp.task('copy-composer.json', () => gulp
  .src('./composer.json')
  .pipe(gulp.dest(distPath))
);

gulp.task('copy-symfony.lock', () => gulp
    .src('./symfony.lock')
    .pipe(gulp.dest(distPath))
);

gulp.task('copy-config', () => gulp
  .src('./config/**/*.*')
  .pipe(gulp.dest(distPath + '/config'))
);

gulp.task('copy-migrations', () => gulp
  .src('./migrations/**/*.*')
  .pipe(gulp.dest(distPath + '/migrations'))
);

gulp.task('copy-public', () => gulp
  .src('./public/**/*.*')
  .pipe(gulp.dest(distPath + '/public'))
);

gulp.task('copy-templates', () => gulp
  .src('./templates/**/*.*')
  .pipe(gulp.dest(distPath + '/templates'))
);

gulp.task('copy-env', () => gulp
  .src('./.env*')
  .pipe(gulp.dest(distPath))
);

gulp.task('copy-storage', () => gulp
  .src('./storage/**/*.*')
  .pipe(gulp.dest(distPath + '/storage'))
);

gulp.task('watch', () => {
  gulp.watch('./src/**/*.*', gulp.parallel('copy-src'))
  gulp.watch('./composer.json', gulp.parallel('copy-composer.json'))
  gulp.watch('./symfony.lock', gulp.parallel('copy-symfony.lock'))
  gulp.watch('./config/**/*.*', gulp.parallel('copy-config'))
  gulp.watch('./migrations/**/*.*', gulp.parallel('copy-migrations'))
  gulp.watch('./public/**/*.*', gulp.parallel('copy-public'))
  gulp.watch('./templates/**/*.*', gulp.parallel('copy-templates'))
  gulp.watch('./.env*', gulp.parallel('copy-env'))
  gulp.watch('./storage/**/*.*', gulp.parallel('copy-storage'))
});

gulp.task('copy-all', gulp.parallel(
  'copy-src',
  'copy-composer.json',
  'copy-symfony.lock',
  'copy-config',
  'copy-migrations',
  'copy-public',
  'copy-templates',
  'copy-env',
  'copy-storage'
));

gulp.task('default', gulp.parallel('watch', 'copy-all'));
