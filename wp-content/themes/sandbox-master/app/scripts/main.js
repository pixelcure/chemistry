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
        superscrollorama : {
            deps: [
                'jquery'
            ]
        }
    },
    paths: {
        jquery: '../bower_components/jquery/jquery',
        backbone: '../bower_components/backbone/backbone',
        underscore: '../bower_components/underscore/underscore',        
        tweenmax: '../bower_components/greensock-js/src/minified/TweenMax.min',
        superscrollorama: '../bower_components/superscrollorama/js/jquery.superscrollorama'
    }
});

require([
    'underscore',
    'backbone',
    'jquery',
    'tweenmax',
    'superscrollorama'
], function (_, Backbone, $, tweenmax, superscrollorama) {

    var headerElements = $.superscrollorama({
        triggerAtCenter : true,
        playOutAnimations : true
    });


    // headElements.addTween('.logo', TweenMax.from('.logo', .5, {css:{left:-100}}));

    TweenMax.from('.logo', .5, {css:{left:-100}});

});
