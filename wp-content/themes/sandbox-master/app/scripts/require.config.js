/*global require*/
'use strict';

require.config({
    shim: {
        underscore: {
            exports: '_'
        },
        backbone: {
            deps: [
                'underscore',
                'jquery'
            ],
            exports: 'Backbone'
        },
        tweenmax: {
            deps: [
                'jquery'
            ],
            exports: 'Tweenmax'
        },
        scrollmagic : {
            deps: [
                'jquery'
            ],
            exports: 'ScrollMagic'
        },        
        mousewheel : {
            deps: [
                'jquery'
            ],
            exports: 'Mousewheel'
        }
    },
    paths: {
        underscore : '../bower_components/underscore/underscore-min',
        jquery: '../bower_components/jquery/jquery.min',
        backbone: '../bower_components/backbone/backbone-min',
        tweenmax: '../bower_components/gsap/src/minified/TweenMax.min',
        scrollmagic: '../bower_components/ScrollMagic/js/jquery.scrollmagic.min',
        mousewheel: '../bower_components/jquery-mousewheel/jquery.mousewheel'
    }
});
