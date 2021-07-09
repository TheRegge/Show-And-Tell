;(function ($) {
  'use strict'

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(function () {
    // Block submit button on click
    $('form.show-and-tell-form')
      .first()
      .submit(function (e) {
        if ($(this).hasClass('form-submitted')) {
          e.preventDefault()
          return
        }
        $(this).addClass('form-submitted')
      })
    // var options = {
    //   success: function () {
    //     alert("Thank you for submitting!");
    //   },
    //   uploadProgress: function (event, position, total, percentComplete) {
    //     console.log("Progress: ", percentComplete, "%");
    //   },
    //   resetForm: true,
    // };
    // $("form.show-and-tell-form").ajaxForm(options);

    // Customize upload image button
    $('.inputfile').each(function () {
      var $input = $(this),
        $label = $input.next('label'),
        labelVal = $label.html()

      $input.on('change', function (e) {
        var fileName = ''

        if (e.target.value) fileName = e.target.value.split('\\').pop()

        if (fileName) $label.find('span').html(fileName)
        else $label.html(labelVal)
      })

      // Firefox bug fix
      $input
        .on('focus', function () {
          $input.addClass('has-focus')
        })
        .on('blur', function () {
          $input.removeClass('has-focus')
        })
    })
  })
})(jQuery)
