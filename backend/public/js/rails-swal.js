// This file has to be required before rails-ujs
// To use it change `data-confirm` of your links to `data-confirm`
(function() {
  const handleConfirm = function(element) {
    if (!allowAction(this)) {
      Rails.stopEverything(element);
    }
  };

  const allowAction = element => {
    if (element.getAttribute('data-confirm') === null) {
      return true;
    }

    showConfirmationDialog(element);
    return false;
  }; // Display the confirmation dialog

  const showConfirmationDialog = element => {
    const message = element.getAttribute('data-confirm');
    const text = element.getAttribute('data-text');

    BootstrapSwal.fire({
      title: message,
      icon: 'warning',
      showCancelButton: true
    }).then((result) => {
      return confirmed(element, result.isConfirmed);
    });
  };

  const confirmed = (element, result) => {
    if (result) {
      // User clicked confirm button
      element.removeAttribute('data-confirm');
      element.click();
    }
  }; // Hook the event before the other rails events so it works togeter
  // with `method: :delete`.
  // See https://github.com/rails/rails/blob/master/actionview/app/assets/javascripts/rails-ujs/start.coffee#L69

  document.addEventListener('rails:attachBindings', element => {
    Rails.delegate(document, 'a[data-confirm]', 'click', handleConfirm);
  });
}).call(void 0);

$(document).on('ajax:success', '[data-callback]', function(data) {
  let callback = $(this).data('callback');
  let callable = eval(callback);
  
  if (typeof callable == 'function') {
    return callable(data);
  }

  return false;
});
