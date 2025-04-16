import { src, dest, watch, series } from 'gulp';
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass';
import terser from 'gulp-terser';
//para que gulp no se pare con los errores
import plumber from 'gulp-plumber';

const sass = gulpSass(dartSass)

// const paths = {
//     scss: 'src/scss/**/*.scss',
//     js: 'src/js/**/*.js'
// }

export function css( done ) {
    src('src/scss/**/*.scss', {sourcemaps: true})
        .pipe(plumber()) //para que gulp no se pare con los errores
        .pipe( sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError) )
        .pipe( dest('./public/build/css', {sourcemaps: '.'}) );
    done()
}

export function js( done ) {
    src('src/js/**/*.js')
      .pipe(plumber()) //para que gulp no se pare con los errores
      .pipe(terser())
      .pipe(dest('./public/build/js'))
    done()
}

export function dev() {
    watch( 'src/scss/**/*.scss', css );
    watch( 'src/js/**/*.js', js );
}

export default series( js, css, dev )