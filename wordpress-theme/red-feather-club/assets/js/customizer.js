/* Live preview for the Customizer's title and tagline fields. */
(function ($) {
  'use strict';

  wp.customize('blogname', function (value) {
    value.bind(function (to) {
      $('.nav__title').contents().first().replaceWith(to + ' ');
      $('.footer__brand-name').text(to);
    });
  });

  wp.customize('blogdescription', function (value) {
    value.bind(function (to) {
      $('.nav__title span').text(to);
      $('.footer__brand-sub').text(to);
    });
  });
})(jQuery);
