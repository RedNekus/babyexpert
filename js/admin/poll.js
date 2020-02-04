var Poll = {
  counter: 1,
  data_poll_in_json: '',
  list_added_id : '',
  list_remove_id: '',
  list_change_id: '',

  init: function() {
    if (!Poll.getPollID())
      Poll.addVariant();
    else
      Poll.loadPoll();

    $('#poll_form').submit(function() {
      if (Poll.getPollID())
        Poll.savePoll();
      else
        Poll.addPoll();

      return false;
    });

    $('.add_variant_btn').click(function() {
      Poll.addVariant();
    });
  },

  getPollID: function() {
    return $('input[name="poll[id]"]').val();
  },

  bindHandlerRemoveItem: function(selector, id) {
    $(selector).click(function() {
      $(this).parents('tr').remove();

      if (Poll.list_added_id.indexOf(id) != -1)
        Poll.list_added_id = Poll.list_added_id.replace(id+'|', '');
      else
        Poll.list_remove_id += id+'|';
    })
  },

  loadPoll: function () {
    $.getJSON("/admin/poll/getPollInJSON/",{
      id: Poll.getPollID()
    }, function (data) {
      Poll.data_poll_in_json = data;
      $('input[name="poll[header]"]').val(data.header.header);
      Poll.counter = data.max_id;

      $.each(data.variants, function(i, variant) {
        Poll.addVariant (
          variant.id,variant.type, variant.variant, variant.amount)
      });
    });
  },

  addVariant: function(id, type, text, amount) {
    if (!id) {
      id = ++Poll.counter;
      Poll.list_added_id += id+'|';
    }

    if (!text) text = '';
    if (!amount) amount = 0;

    var
      select_attr_name = 'poll[variant]['+id+'][type]',
      text_attr_name = 'poll[variant]['+id+'][text]',
      amount_attr_name = 'poll[variant]['+id+'][amount]',
      amount_selector = 'input[name="'+amount_attr_name+'"]',
      remove_btn_id = 'remove_btn_'+id;

    $variant = $('<tr><td><select name="'+select_attr_name+'"><option value="radio">Переключатель</option><option value="checkbox">Флажок</option><option value="text">Текстовое поле</option></select></td><td><input type="text" name="'+text_attr_name+'" value="'+text+'" maxlength="255" /></td><td><input type="text" name="'+amount_attr_name+'" value="'+amount+'" maxlength="10" /></td><td><div class="button_icon_remove" id="'+remove_btn_id+'" title="Удалить"></div></td></tr>');
    $variant.find('option[value="'+type+'"]').attr('selected', 'selected');

    $('#variants > tbody').append($variant);

    digit_filter(amount_selector);
    default_value(amount_selector, amount);

    Poll.bindHandlerRemoveItem('#'+remove_btn_id, id);
  },

  addPoll: function() {
    if (!Poll.checkForm()) return false;
    Poll.sendingForm('/admin/poll/addAjax/', '/admin/poll/');
  },

  savePoll: function() {
    if (!Poll.checkForm()) return false;

    Poll.searchChange();
    Poll.sendingForm('/admin/poll/saveAjax/', '/admin/poll/');
  },

  sendingForm: function(url, location_href) {
    Screen_Sending.on('#poll_conteiner > div', 'Отправка...');
    $.post(url, $('#poll_form').serialize(), function(response) {
      if (response == 'success') {
        if (location_href)
          location.href = location_href;
        else
          Screen_Sending.off();
      } else {
        Screen_Sending.off();
        alert('Ошибка во время сохранения опроса!');
      }
    });
  },

  searchChange: function () {
    Poll.list_change_id = '';
    var change_header = '';
    if ($('input[name="poll[header]"]').val() != Poll.data_poll_in_json.header.header) {
      change_header = '1';
    }

    $.each(Poll.data_poll_in_json.variants, function (i, variant) {
      if (Poll.list_remove_id.indexOf(variant.id) == -1) {
        if (variant.type != $('select[name$="['+variant.id+'][type]"]').val() ||
            variant.variant != $('input[name$="['+variant.id+'][text]"]').val() ||
            variant.amount != $('input[name$="['+variant.id+'][amount]"]').val()
        ) {
          Poll.list_change_id += variant.id+'|';
        }
      }
    });

    $('input[name="poll[change_header]"]').val(change_header);
    $('input[name="poll[list_added_id]"]').val(Poll.list_added_id);
    $('input[name="poll[list_remove_id]"]').val(Poll.list_remove_id);
    $('input[name="poll[list_change_id]"]').val(Poll.list_change_id);
  },

  checkForm: function() {
    if ($('input[name="poll[header]"]').val() == '') {
      alert('Введите пожалуйста заголовок опроса!');
      return false;
    }

    var emptyRows = false,
        existRows = false;

    $('input[name$="[text]"]').each(function() {
      if ($(this).val() == '') emptyRows = true;
      existRows = true;
    });

    if (!existRows) {
      alert('Создайте пожалуйста варианты опроса!');
      return false;
    }

    if (emptyRows) {
      alert('Заполните пожалуйста варианты опроса!');
      return false;
    }

    return true;
  }
}

$(function() {
  Poll.init();
})