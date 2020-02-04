var Screen_Sending = {
  selector: '',
  sending_el: '',
  text: '',
  on: function (selector, text) {
    if (selector) this.selector= selector;
    if (text) this.text= text;

    $(this.selector).hide();
    this.sending_el = $('<div style="margin: 10px 0 10px 0; text-align: center;">'+this.text+'</div>');
    $(this.selector).parent().append(this.sending_el);
  },
  off: function () {
    $(this.selector).show();
    this.sending_el.remove();
  }
}