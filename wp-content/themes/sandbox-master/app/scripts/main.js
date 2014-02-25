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


    function responsiveMenu(){
        var $trigger = $('#navTrigger');
        var $menu = $('.header .nav-outer');
        var screenHeight = $(document).height();

        
        $(window).resize(function(){

            var width = $(this).width();
            
            if(width > 599){
                $menu.show();
            }
        });

        $trigger.on('click', function(){

            if(!$(this).hasClass('active') ){
    
               $trigger.addClass('active').find('img.open').hide();
               $trigger.find('img.close').show();

               $menu.css('height', screenHeight).fadeIn();
            } else {

                $trigger.removeClass('active').find('img.close').hide();
                $trigger.find('img.open').show();

                $menu.hide();

            }

        });
    
    }

    responsiveMenu();


    var $body = $("html");
    var $imgScroll = $('#pixelScroll');

    $imgScroll.on('click', function(){
        $body.animate({scrollTop:0}, '500');    

    });




    var callOutElements = $.superscrollorama({
        triggerAtCenter : 'top',
        playOutAnimations : true
    }); // end call out elements

    var beakerElements = $.superscrollorama({
        triggerAtCenter : 'bottom',
        playOutAnimations : true
    }); // end beaker elements


    var footerElements = $.superscrollorama({
        triggerAtCenter : false,
        playOutAnimations : true
    }); // end footer elements

    var innerElements = $.superscrollorama({
        triggerAtCenter : false,
        playOutAnimations : true
    }); // end inner elements



    // beakerElements.addTween('#devContents',
    //         TweenMax.from( $('#devContents'), .5, 
    //         {
    //             css : { 

    //                 opacity: .3 

    //             }
    //         } // end tm from
    //     ), 0, -800
    // ); // end dev Contents Fade In

    // beakerElements.addTween('#beakerDev',
    //         TweenMax.from( $('#beakerDev'), .5, 
    //         {
    //             css : { 

    //                 right: -300 

    //             }
    //         } // end tm from
    //     ), 0, -500
    // ); // end Dev Beaker    

    // beakerElements.addTween('#devCopy',
    //         TweenMax.from( $('#devCopy'), .5, 
    //         {
    //             css : { 

    //                 left: -400 

    //             }
    //         } // end tm from
    //     ), 0, -500
    // ); // end Content
    

});
