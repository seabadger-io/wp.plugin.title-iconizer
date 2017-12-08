(function ($, window, document) {
  'use strict';
  console.log('Loading');
  $(document).ready(function () {
  console.log('Ready');
    $('#sbti_custom_icon').change(function () {
		console.log('Changed');
      $('#sbti_custom_icon_preview').html('<i class="fa fa-' + $('#sbti_custom_icon').val() + '"></i>');
    });
  });
}(jQuery, window, document));
