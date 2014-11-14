module.exports = function(grunt) {
    'use strict';

    //var wpConfig = grunt.file.read('../../../wp-config.php');

    var devMode = true;

    if( devMode ) {
        console.log('-----------------------------------');
        console.log('Dev mode enabled');
        console.log('-----------------------------------');
    }

    var environment = devMode ? 'development' : 'production';

    var bowerComponentsPath = './fnd/bower_components/';

    var foundationScriptPath = bowerComponentsPath + 'foundation/js/foundation';

    var clientScripts = './js/client/**/*.js';

    var clientStylesheet = './css/zing-unprefixed.css';
    var clientStylesheetPrefixed = './css/zing.css';
    var stylesheets = './scss/*.scss';

    var plugins = [
        bowerComponentsPath + 'slick-carousel/slick/slick.js'
        //,bowerComponentsPath + 'raygun4js/dist/raygun.js'
    ];

    var polyfills = [
        bowerComponentsPath + 'jquery-placeholder/jquery.placeholder.js',
        bowerComponentsPath + 'respond/src/respond.js',
        bowerComponentsPath + 'selectivizr/selectivizr.js'

    ];

    var cssTasks = ['compass', 'autoprefixer'];

    var preScript = 'if (typeof jQuery === "undefined") { throw new Error("Foundation requires jQuery") }';

    if(devMode) {
        preScript += '\n\n(function(){window.devMode = true})();';
    }

    //if( ! devMode ) {
    //    cssTasks.push('cssmin');
    //}

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        banner: '/*!\n' +
            ' * Zing Design <%= pkg.version %> (<%= pkg.homepage %>)\n' +
            ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
            ' */\n\n',
        jqueryCheck: preScript,

        clean: {
            dist: ['dist', 'src', '.sass-cache', 'css']
        },

        jshint: {
            options: {
                jshintrc: 'js/.jshintrc'
            }
            ,gruntfile: {
                src: 'Gruntfile.js'
            }
            ,foundation: {
                src: [foundationScriptPath + '/*.js']
            }
            ,client: {
                src: [clientScripts]
            }
            ,zdPlugins: {
                src: ['./js/zd-plugins/*.js']
            }
        },

        concat: {
            options: {
                banner: '<%= banner %><%= jqueryCheck %>',
                stripBanners: false
            }
            ,client: {
                src: [
                    'fnd/bower_components/fastclick/lib/fastclick.js',
                    bowerComponentsPath + 'bootstrap-sass/js/affix.js',
                    //Comment out unused scripts here for extra optimisingness
                    foundationScriptPath + '/foundation.js',
                    //foundationScriptPath + '/foundation.abide.js',
                    //foundationScriptPath + '/foundation.accordian.js',
                    //foundationScriptPath + '/foundation.alert.js',
                    //foundationScriptPath + '/foundation.clearing.js',
                    //foundationScriptPath + '/foundation.dropdown.js',
                    //foundationScriptPath + '/foundation.equalizer.js',
                    //foundationScriptPath + '/foundation.interchange.js',
                    //foundationScriptPath + '/foundation.joyride.js',
                    //foundationScriptPath + '/foundation.magellan.js',
                    //foundationScriptPath + '/foundation.offcanvas.js',
                    // ORBIT replaced by Slick
                    //foundationScriptPath + '/foundation.orbit.js',
                    foundationScriptPath + '/foundation.reveal.js',
                    //foundationScriptPath + '/foundation.tab.js',
                    foundationScriptPath + '/foundation.tooltip.js',
                    //foundationScriptPath + '/foundation.topbar.js',
                    plugins,
                    clientScripts
                ],
                dest: 'src/js/<%= pkg.name %>-client.js'
            }
            ,polyfill: {
                src: polyfills,
                dest: 'src/js/<%= pkg.name %>-polyfill.js'
            }
        },

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                sourceMap: devMode
            }
            ,build: {
                src: 'src/js/<%= pkg.name %>-client.js',
                dest: 'dist/js/<%= pkg.name %>-client.min.js'
            }
            ,buildPolyfill: {
                src: 'src/js/<%= pkg.name %>-polyfill.js',
                dest: 'dist/js/<%= pkg.name %>-polyfill.min.js'
            },
            ajaxPagination: {
                src: 'js/zd-plugins/ajax-page-loading.js',
                dest: 'dist/js/ajax-page-loading.min.js'
            },
            imageModalSlider: {
                src: 'js/zd-plugins/image-modal-slider.js',
                dest: 'dist/js/image-modal-slider.min.js'
            }

        },

        compass: {
            dist: {
                options: {
                    sassDir: 'scss',
                    cssDir: 'css',
                    javascriptsDir: 'js',
                    imagesDir: 'images',
                    environment: environment
                }
            }
        },

        copy: {
            //style: {
            //    expand: true,
            //    cwd: 'css/',
            //    src: ['zd.css'],
            //    dest: './style.css'
            //},
            fontAwesomeFonts: {
                expand: true,
                cwd: bowerComponentsPath + 'font-awesome/fonts/',
                src: ['*'],
                dest: './fonts'
            },
            slickFonts: {
                expand: true,
                cwd: bowerComponentsPath + 'slick-carousel/slick/fonts/',
                src: ['*'],
                dest: './fonts'
            }
        },


        autoprefixer: {
            options: {
                // Task-specific options go here.
                browsers: ['last 2 versions', 'ie 9']
            },
            clientCSS: {
                options: {
                    // Target-specific options go here.
                },
                src: clientStylesheet,
                dest: clientStylesheetPrefixed
            }
        },

        cssmin: {
            add_banner: {
                options: {
                    banner: '/* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */'
                },
                files: {
                    'css/<%= pkg.name %>.min.css': ['css/**/*.css']
                }
            }
        },

        watch: {
            clientJsWatch: {
                files: [clientScripts],
                tasks: ['jshint:client', 'concat:client', 'uglify:build']
            },
            zdPluginWatch: {
                files: ['./js/zd-plugins/*.js'],
                tasks: ['jshint:zdPlugins', 'uglify:ajaxPagination' , 'uglify:imageModalSlider' ]
            },
            polyfillJsWatch: {
                files: [polyfills],
                tasks: ['concat:polyfill', 'uglify:buildPolyfill']
            },
            sassWatch: {
                files: [stylesheets],
                tasks: cssTasks
            },
            cssWatch: {
                files: [clientStylesheet],
                tasks: ['autoprefixer']
            },
            fontWatch: {
                files: [
                    bowerComponentsPath + 'font-awesome/fonts/*',
                    bowerComponentsPath + 'slick-carousel/slick/fonts/'
                ],
                tasks: ['copy:fonts']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-autoprefixer');

    grunt.registerTask('default', ['clean', 'jshint:client', 'jshint:zdPlugins', 'concat', 'uglify', 'compass', 'copy']);
    grunt.registerTask('css', cssTasks);
    grunt.registerTask('js', ['jshint:client', 'jshint:zdPlugins', 'concat', 'uglify']);
    grunt.registerTask('w', ['css', 'js', 'watch']);
    grunt.registerTask('copyFonts', ['copy:fontAwesomeFonts']);
};