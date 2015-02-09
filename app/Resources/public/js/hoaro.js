// Do code JavaScript qui fait quelque chose de sympa
(function () {
    'use strict';

    var onHashChange = function() {
        jQuery('#element').css('background', 'blue');
    };

    window.addEventListener('hashchange', onHashChange);
}());