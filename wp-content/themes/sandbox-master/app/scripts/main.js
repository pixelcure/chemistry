require([
    'underscore',
    'jquery',
    'backbone',
    'tweenmax',
    'scrollmagic',
    'global/global.main',
    'global/portfolio.main',
    'global/homePageAnimations.main'
], function (_, $, Backbone, Tweenmax, ScrollMagic, global, portfolio, homePage) {

    global.init();
    homePage.init();
    portfolio.init();

});
