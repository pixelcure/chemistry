/*global define*/
define([
  'underscore',
  'backbone',
  'jquery',
  'tweenmax',
  'scrollmagic'
  ], function(_, Backbone, $, Tweenmax, ScrollMagic){
	'use strict';

  function init () {

      var HomePageView = Backbone.View.extend({
        initialize : function () {
          
          // invove Tweens
          this.scrollCallOutTween();
          // this.theDoctorCallOutTween();
          
          this.introTween();

          // invoke Beakers
          this.devBeaker();
          this.designBeaker();
          this.creativeBeaker();

          // invoke mousewheel show Scene Func

          this.$el.on('mousewheel', function(e) {
            e.preventDefault();

            // have we scrolled yet?
            var hasScrolled = false;

            // lets click the scroll callout / invoker
            var $scrollNow = $('.callouts .callout.scroll-now a');

            
            if(!hasScrolled){
  
              $scrollNow.click();
            }

          });

          // Blink our Divider (pizaz)
          TweenMax.from( $('.home-divider'), 1, { css: { opacity: 0 } } );

          // Blink our Intro Container
          TweenMax.from( $('body.home .row.top'), 1, { css: { opacity: 0 } } );

        },

        events : {
          'click #pixelScroll' : 'scrollDown',             
          'click #scrollHiddenText' : 'scrollDown',
        },

        // Scroll Callout Tween

        scrollCallOutTween : function () {

          // scroll Callout Contents
          var $scrollCallOut = $('.callouts .callout.scroll-now');
          var $scrollNow = $('.callouts .callout.scroll-now a h1');          
          var $scrollNowImg = $('#pixelScroll img');


          // image Scroll Image Tween
          TweenMax.from($scrollNowImg, 1, { delay: 1, css: { opacity : 0, y : -25 } });

          // header Scroll
          TweenMax.from($scrollNow, 1, { delay: 1.5, css: { opacity : 0, x : 100 }, ease:Elastic });          

        },

        // Intro Tween
        introTween : function () {

          // the Intro Callout
          var $intro = $('.intro.col');

          // image Scroll Image Tween
          TweenMax.to($intro, 1, { css: { opacity : 1 }, ease:Elastic });

        },

        // the Doctor Callout Tween

        theDoctorCallOutTween : function () {

          // the Doctor Callout
          var $theDoctor = $('.callouts .callout.the-doctor');

          // image Scroll Image Tween
          TweenMax.from($theDoctor, 1, { css: { opacity : 0, x: -500 }, ease:Elastic });          

        },

        // show Lower Scene

        showScene : function (event) {
            // return false;
            event.preventDefault();

            var client = event.clientY;

            $(document.body).animate({
            
              scrollTop: '0'
            
            }, 100);

        },   

        // Pixel Cross Scroll Down

        scrollDown : function (e) {

          // prevent Default
          e.preventDefault();
          

          // body and footer vars
          var $body = this.$el;
          var $footer = $('.footer');
          var $beakerRow = $('.beaker-row');

          
          // fade in our beakers, begin experience
          $beakerRow.fadeIn();

          // current scrollPosition
          var scrollTop = 0;

          // if scrollTop is 0
          if( scrollTop == 0 ){

            // first transtion, new point of position
            scrollTop = 771;

            // animate to first beaker
            $body.stop(true, true).animate({
       
              'scrollTop' : scrollTop
       
            }, 3000, function(){

              // Blink our Divider (more pizaz)
              TweenMax.from( $('.home-divider'), 1, { css: { opacity: 0 } } );

            });

          } else if (scrollTop == 771) {

            // first transtion, new point of position
            scrollTop = 1155;

            $body.stop(true, true).animate({
           
              'scrollTop' : scrollTop
           
            }, 3000);

          } // end if Scroll / Click

          // turn off bind of Mouse Wheel
          this.$el.off('mousewheel');

          // Show Footer
          $footer.fadeIn();

        },

        // animate Scene Dev Beaker

        devBeaker : function() {

          // controller
          var devController = new ScrollMagic();

          // beaker
          var $devBeaker = $('#beakerDev');

          // dev Beaker Animation
          var devBeaker = new TimelineMax({repeat: 0})
            .add(TweenMax.from($devBeaker, 1, { opacity: 0.8, x: '+=400' }));    


          // Dev Beaker Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 222
            })
            .setTween(devBeaker)
            .addTo(devController);           


          // dev Contents Var

          var $devContent = $('#devCopy');

          // dev Beaker Contents Animation
          var devContent = new TimelineMax({repeat: 0})
            .add(TweenMax.from($devContent, 1, { z: 0.1, x: '-=1000', ease: 'Power4.easeInOut' }));           

          // Dev Beaker Content Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 222
            })
            .setTween(devContent)
            .addTo(devController);              

          // dev Table Var

          var $devTable = $('#devTable');

          // dev Table Animation
          var devTable = new TimelineMax({repeat: 0})
            .add(TweenMax.from($devTable, 1, { css: { opacity: 0.8, x: '+=1000' } }));           

          // Dev Table Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 222
            })
            .setTween(devTable)
            .addTo(devController);          
        
            // Dev Beaker Conents Fade Loop

            var $beakerContents = $('#devContents');

            // Dev Beaker Animation (repeated)
            TweenMax.from($beakerContents, .5,  { repeat: -1, delay: 3, yoyo: true, opacity: 0.4, ease: Power2.easeInOut });

        },


        designBeaker : function () {
          
          // controller
          var designController = new ScrollMagic();

          // beaker
          var $designBeaker = $('#beakerDesign');

          // design Beaker Animation
          var designBeaker = new TimelineMax({repeat: 0})
            .add( TweenMax.from($designBeaker, 100, { x: '-=1000' }) );    


          // design Beaker Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 823
            })
            .setTween(designBeaker)
            .addTo(designController);           


          // design Contents Var

          var $designContent = $('#designCopy');

          // design Beaker Contents Animation
          var designContent = new TimelineMax({repeat: 0})
            .add(TweenMax.from($designContent, 7, { x: '+=1000' }));           

          // design Beaker Content Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 823
            })
            .setTween(designContent)
            .addTo(designController);              

          // design Table Var

          var $designTable = $('#designTable');

          // design Table Animation
          var designTable = new TimelineMax({repeat: 0})
            .add(TweenMax.from($designTable, 7, { css: { opacity: 0, x: '-=1000'} }));           

          // design Table Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 823
            })
            .setTween(designTable)
            .addTo(designController);           

          // Design Beaker Conents Fade Loop

          var $beakerContents = $('#designContents');

          // Design Beaker Animation (repeated)
          TweenMax.from($beakerContents, .5,  { repeat: -1, delay: 3, yoyo: true, opacity: 0.4, ease: Power2.easeInOut });

        },

        creativeBeaker : function () {

          // controller
          var creativeController = new ScrollMagic();
          
          // beaker
          var $creativeBeaker = $('#beakerCreative');

          // Creative Beaker Animation
          var creativeBeaker = new TimelineMax({repeat: 0})
            .add(TweenMax.from($creativeBeaker, 1, { opacity: 0.8, x: '+=400' }));            

          // Creative Beaker Scene
           new ScrollScene({
              duration: 300,
              offset: 1200
            })
            .setTween(creativeBeaker)
            .addTo(creativeController);

          // creative Contents Var

          var $creativeContent = $('#creativeCopy');

          // creative Beaker Contents Animation
          var creativeContent = new TimelineMax({repeat: 0})
            .add(TweenMax.from($creativeContent, 7, { x: '+=1000' }));           

          // creative Beaker Content Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 823
            })
            .setTween(creativeContent)
            .addTo(creativeController);              

          // creative Table Var

          var $creativeTable = $('#creativeTable');

          // creative Table Animation
          var creativeTable = new TimelineMax({repeat: 0})
            .add(TweenMax.from($creativeTable, 7, { css: { opacity: 0, x: '-=1000'} }));           

          // creative Table Movement Scene
          new ScrollScene({
              duration: 300,
              offset: 823
            })
            .setTween(creativeTable)
            .addTo(creativeController);                 

          // Creative Beaker Conents Fade Loop

          var $beakerContents = $('#creativeContents');

          // Creative Beaker Animation (repeated)
          TweenMax.from($beakerContents, .5,  { repeat: -1, delay: 3, yoyo: true, opacity: 0.4, ease: Power2.easeInOut });

        }

      }); // End Global View


      var homePageView = new HomePageView({el : 'body'});

  };

  return {
    init : init
  }

});
