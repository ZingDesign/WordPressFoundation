module.exports = function(grunt) {
    "use strict";

    var devMode = true;

    var bowerComponentsPath = './fnd/bower_components/';

    var foundationScriptPath = bowerComponentsPath + 'foundation/js/foundation';

    var clientScripts = './js/client/**/*.js';

    var stylesheets = './scss/**/*.scss';

    var plugins = [
        bowerComponentsPath + 'slick-carousel/slick/slick.js'
    ];

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        banner: '/*!\n' +
            ' * Zing Design <%= pkg.version %> (<%= pkg.homepage %>)\n' +
            ' * Copyright <%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
            ' */\n\n',
        jqueryCheck: 'if (typeof jQuery === "undefined") { throw new Error("Foundation requires jQuery") }\n\n',

        clean: {
            dist: ['dist', 'src', '.sass-cache']
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
        },

        concat: {
            options: {
                banner: '<%= banner %><%= jqueryCheck %>',
                stripBanners: false
            }
            ,foundation: {
                src: [
                    'fnd/bower_components/fastclick/lib/fastclick.js',
                    //Comment unused scripts out here for extra optimisingness
                    foundationScriptPath + '/foundation.js',
                    foundationScriptPath + '/foundation.abide.js',
                    foundationScriptPath + '/foundation.accordian.js',
                    foundationScriptPath + '/foundation.alert.js',
                    foundationScriptPath + '/foundation.clearing.js',
                    foundationScriptPath + '/foundation.dropdown.js',
                    foundationScriptPath + '/foundation.equalizer.js',
                    foundationScriptPath + '/foundation.interchange.js',
                    foundationScriptPath + '/foundation.joyride.js',
                    foundationScriptPath + '/foundation.magellan.js',
                    foundationScriptPath + '/foundation.offcanvas.js',
                    // ORBIT replaced by Slick
                    //foundationScriptPath + '/foundation.orbit.js',
                    foundationScriptPath + '/foundation.reveal.js',
                    foundationScriptPath + '/foundation.tab.js',
                    foundationScriptPath + '/foundation.tooltip.js',
                    foundationScriptPath + '/foundation.topbar.js',
                    plugins,
                    clientScripts
                ],
                dest: 'src/js/<%= pkg.name %>.js'
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

        },

        compass: {
            dist: {
                options: {
                    sassDir: 'scss',
                    cssDir: 'css',
                    javascriptsDir: 'js',
                    imagesDir: 'images',
                    environment: '<%= pkg.environment %>'
                }
            }
        },

        copy: {
            style: {
                expand: true,
                cwd: 'css/',
                src: ['zd.css'],
                dest: './style.css'
            }
        },

        watch: {
            jsWatch: {
                files: [clientScripts],
                tasks: ['jshint:client', 'concat', 'uglify']
            },
            sassWatch: {
                files: [stylesheets],
                tasks: ['compass']
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

    grunt.registerTask('default', ['clean', 'jshint', 'concat', 'uglify', 'compass']);
    grunt.registerTask('js', ['jshint:client', 'concat', 'uglify']);

};