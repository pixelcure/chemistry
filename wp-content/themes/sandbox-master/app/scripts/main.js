require([
    'underscore',
    'jquery',
    'backbone',
    'tweenmax',
    'superscrollorama',
    'global/global.main',
    'global/portfolio.main'
], function (_, $, Backbone, tweenmax, superscrollorama, global, portfolio) {

    global.init();
    portfolio.init();

});
