/*global define*/
define([
  'underscore',
  'backbone',
  'jquery',
  'tweenmax',
  'scrollmagic'
  ], function(_, Backbone, $, tweenmax, ScrollMagic){
	"use strict";

  function init () {


      var GlobalView = Backbone.View.extend({

        initialize : function () {

          // invoke Responsive Menu
          
          this.responsiveMenu();
          
        },

        events : {
          'mouseover ul.projects-container li.item' : 'itemOver',
          'click div.top-button a[title=top]' : 'scrollUp'
        },

        // Responsive Menu
        responsiveMenu : function () {

            var $trigger = $('#navTrigger');
            var $menu = $('.header .nav-outer');
            var screenHeight = $(document).height();


            $(window).resize(function(){

                var width = $(this).width();

                if(width > 0){
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

        },

        // Footer Scroll Up
        scrollUp : function (e) {
          e.preventDefault();
        
          $(document.body).animate({
          
            scrollTop: '0'
          
          }, 1000);

        }

      }); // End Global View

      
      // instantiate new Global View

      var globalView = new GlobalView({el : 'body'});

  };

  return {
    init : init
  }

});
