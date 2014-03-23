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


      var PortfolioView = Backbone.View.extend({
      
        // initialize portfolio view
        initialize : function () {

        },

        // events triggered on portfolio items
        events : {
          'mouseover ul.projects-container li.item' : 'itemOver',
          'click ul.projects-container li.item' : 'showInfo',
          'click .hidden-clone .closeItemDescription' : 'hideInfo'
        },
      
        // Hover over Portfolio Item, Slide Down, then Slide up on mouseOut
        itemOver : function (e) {

          var $that = $(e.currentTarget);
          var thatW = $that.width() - 15;
          var description = $('span');
    
          $that.find(description).width(thatW).stop(true, true).slideDown();
    
          $that.mouseout(function(){
            $(this).find(description).stop(true, false).slideUp();
          });

        },

        // Show Hidden Description of Portfolio Item
        showInfo : function (e) {
          var $that = $(e.currentTarget);
          var $hiddenDescription = $that.find('.hidden-description');
          var $clone = $hiddenDescription.clone().removeClass('hidden-description').addClass('hidden-clone');
          var $itemsContainer = $('ul.projects-container');
          var windowWidth = $(window).width();

          $clone.width(windowWidth -100);

          $(window).resize(function(){
            $clone.width($(this).width() - 100);
          });


          $(document.body).append($clone).find('.hidden-clone').fadeIn(500);
          $(document.body).animate({
              scrollTop : '403px'
            }
            , 500);
          
          $itemsContainer.css('opacity', .5);

        },
        
        // Hide Hidden Description of Portfolio Item
        hideInfo : function (e) {

          var $that = $(e.currentTarget);
          var $itemsContainer = $('ul.projects-container');

          $itemsContainer.css('opacity', 1);
          
          $that.parent().animate({
          
            top : '-1000px'
          
          }, 500, function(){
          
            $(this).remove();
          
          });

        }
      }); // End Global View


      // institiate new PortfolioView
      var portView = new PortfolioView({el : 'body'});

  };

  return {
    init : init
  }

});
