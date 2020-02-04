function default_value(selector, value, blurStyle, focusStyle) {
  if (typeof selector == 'string') selector = $(selector);
  if (!blurStyle) blurStyle = {};
  if (!focusStyle) focusStyle = {};

  selector.each(function () {
    if (!$(this).val()) {
      $(this).val(value).css(blurStyle);
    }

    $(this).focus(function () {
      if ($(this).val() == value) {
        $(this).val('').css(focusStyle);
      }
    })
    .blur(function () {
      if ($(this).val() == '') {
        $(this).val(value).css(blurStyle);
      }
    }).keydown(function () {
      if ($(this).val() == value) {
        $(this).val('').css(focusStyle);
      }
    }).keyup(function () {
      if ($(this).val() == '') {
        $(this).val(value).css(blurStyle);
      }
    });
  });
}