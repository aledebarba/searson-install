const { task, watch, parallel, series } = require('gulp');
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();


let phpFiles = '**/*.php';
let jsFiles =  '**/*.js';
let jsxFiles = '**/*.jsx';
let tsFiles = '**/*.ts';
let sassFiles = '**/*.scss';
let htmlFiles = '**/*.html';


// complile scss into css 
function frontEndStyles() {
    return gulp.src('./modules/guts/styles/styles.scss')
    .pipe(sass())
    .pipe(gulp.dest('./modules/guts/styles').on('error', sass.logError))
    .pipe(browserSync.stream())
}

// task('reload', function(done){
//     browserSync.reload()
//     done();
// })

function watchFiles() {
    
    watch(phpFiles, series(reload));
    watch(jsFiles, series(reload));
    watch(jsxFiles, series(reload));
    watch(tsFiles, series(reload));
    watch(sassFiles, series(frontEndStyles, reload));
    watch(htmlFiles, series(reload));

}

function browser_sync() {
    browserSync.init({
        proxy: 'https://searsons.test',
        https: true,
        port: 3000
    });
}

function reload(done) {
    browserSync.reload();
    done();
}

// task('serve', function() {
//     browserSync.init(
//         {
//             port: 3000,
//             proxy: "searsons.test"
//         }
//     )    
//     gulp.watch('**/*.php').on('change', browserSync.reload );
//     gulp.watch('**/*.js').on('change', browserSync.reload );
//     gulp.watch('**/*.jsx').on('change', browserSync.reload );
//     gulp.watch('**/*.scss').on('change', (_path, _stats)=>{
//         frontEndStyles();
//         browserSync.reload;
//     });    
// })
/**
 * 
 * 
 * 
 * 
 * openssl req -new -x509 -sha512 -key mykeyfile.pem -out mycertfile.cer -days 999 -subj "/emailAddress=ale@alemacedo.com/CN=localhost/OU=My Organizational Unit/O=My Organization/L=Santo Andre/ST=Sao Paulo/C=br"
 */

task('default', parallel(watchFiles, browser_sync ));
