/*global define*/
define([
  'underscore',
  'backbone',
  'jquery',
  'tweenmax',
  'superscrollorama'
  ], function(_, Backbone, $, tweenmax, superscrollorama){
	"use strict";

  function init () {


      var GlobalView = Backbone.View.extend({
        initialize : function () {

          this.responsiveMenu();

        },

        events : {
          'mouseover ul.projects-container li.item' : 'itemOver'
        },

        // Responsive Menu

        responsiveMenu : function () {

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

      }); // End Global View


      var globalView = new GlobalView({el : 'body'});

  };

  return {
    init : init
  }

});
