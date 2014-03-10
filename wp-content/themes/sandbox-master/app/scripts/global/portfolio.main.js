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


      var PortfolioView = Backbone.View.extend({
        initialize : function () {

        },

        events : {
          'mouseover ul.projects-container li.item' : 'itemOver',
          'click ul.projects-container li.item' : 'showInfo',
          'click .hidden-clone .closeItemDescription' : 'hideInfo'
        },
        itemOver : function (e) {

          var $that = $(e.currentTarget);
          var thatW = $that.width() - 15;
          var description = $('span');

          $that.find(description).width(thatW).slideDown();



        },
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


          $(document.body).append($clone).find('.hidden-clone').fadeIn('fast');
          $itemsContainer.css('opacity', .5);

        },
        hideInfo : function (e) {

          var $that = $(e.currentTarget);
          var $itemsContainer = $('ul.projects-container');

          $itemsContainer.css('opacity', 1);
          $that.parent().remove();

        }
      }); // End Global View


      var portView = new PortfolioView({el : 'body'});

  };

  return {
    init : init
  }

});
