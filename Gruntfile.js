module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-symlink');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-less');
    //grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.file.mkdir('app/Resources/public/images/');

    // properties are css files
    // values are less files
    var filesLess = {};

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            bundles: {
                files: filesLess
            }
        },
        /*symlink: {
            app: {
                relativeSrc: '../../app/Resources/public/',
                dest: 'web/bundles/app',
                options: {type: 'dir'}
            },
             // Gestion de FontAwesome
             font_awesome: {
             dest: 'app/Resources/public/fonts/awesome',
             relativeSrc: '../../../../web/vendor/font-awesome/font/',
             options: {type: 'dir'}
             }
        },
        */
        copy: {
            app: {
                expand: true,
                cwd: 'app/Resources/public/',
                src: ['**'],
                dest: 'web/bundles/app'
            },
            font_awesome: {
                expand: true,
                cwd: 'web/vendor/font-awesome/fonts/',
                src: ['**'],
                dest: 'web/bundles/app/fonts/awesome'
            }
        },
        concat: {
            dist: {
                src: [
                    'web/vendor/jquery/jquery.js',
                    'web/bundles/app/js/hoaro.js'
                ],
                dest: 'web/built/app/js/hoaro.js'
            }
        },
        watch: {
            css: {
                files: ['web/bundles/*/less/*.less'],
                tasks: ['css']
            },
            javascript: {
                files: ['web/bundles/app/js/*.js'],
                tasks: ['javascript']
            }
        },
        uglify: {
            dist: {
                files: {
                    'web/built/app/js/hoaro.min.js': ['web/built/app/js/hoaro.js']
                }
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'web/built/arthurhoarorsscruncheruser/css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'web/built/app/css',
                    ext: '.min.css'
                }]
            }
        },
        jshint: {
            options: {
                curly: true,
                eqeqeq: true,
                eqnull: true,
                browser: true,
                undef: true,
                unused: true,
                bitwise: true,
                camelcase: true,
                forin: true,
                immed: true,
                latedef: true,
                newcap: true,
                quotmark: 'single',
                strict: true,
                maxparams: 4,
                maxdepth: 2,
                maxcomplexity: 3,
                globals: {
                    'jQuery': true,
                    '$': true,
                    '_': true,
                    'Mustache': true
                }
            },
            dist: {
                src: ['web/bundles/app/js/*.js']
            }
        }
    });

    // Default task(s).
    grunt.registerTask('default', ['css', 'javascript']);
    grunt.registerTask('css', ['copy', 'less:discovering', 'less', 'cssmin']);
    grunt.registerTask('javascript', ['jshint', 'concat', 'uglify']);
    grunt.registerTask('assets:install', ['symlink']);
    grunt.registerTask('deploy', ['assets:install', 'default']);
    grunt.registerTask('less:discovering', 'This is a function', function() {
        // LESS Files management
        // Source LESS files are located inside : bundles/[bundle]/less/
        // Destination CSS files are located inside : built/[bundle]/css/
        var mappingFileLess = grunt.file.expandMapping(
            ['*/less/*.less', '*/less/*/*.less'],
            'web/built/', {
                cwd: 'web/bundles/',
                rename: function(dest, matchedSrcPath, options) {
                    return dest + matchedSrcPath.replace(/less/g, 'css');
                }
            });

        grunt.util._.each(mappingFileLess, function(value) {
            // Why value.src is an array ??
            filesLess[value.dest] = value.src[0];
        });
    });
};
