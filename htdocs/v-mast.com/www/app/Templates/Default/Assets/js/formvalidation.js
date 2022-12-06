(function() {
  var app = {
    initialize: function() {
      this.setUpListeners();
    },
    setUpListeners: function() {
      $('form').on('submit', app.submitForm);
      $('form').on('keydown', 'input', app.removeError);
    },
    submitForm: function(e) {
      var form = $(this),
          submitBtn = form.find('button[type="submit"]');
      if (!app.validateForm(form)) {
        e.preventDefault();
        return false;
      }
    },
    validateForm: function(form) {
      var inputs = form.find('input,select'),
          valid = true;

      $(".popover").remove();
      $.each(inputs, function(index, val) {
        var input = $(val),
            val = input.val(),
            formGroup = input.parents('.form-group'),
            label = input.parents('label'),
            emptyError = input.data("emptyError"),
            inputType = input.data("type"),
            errorVal = false;

        if (inputType == undefined) return true;

        if (input.hasClass("select-chosen-single")) {
            input = $(".chosen-single", formGroup);
        }
        if (input.hasClass("select-chosen-multiple")) {
            input = $(".chosen-choices", formGroup);
        }
        
        if (val == null || val.length === 0) {
          errorVal = emptyError;
        } else {
          switch (inputType) {
          case 'login':
          if (!app.checkLogin(val)){
            errorVal = input.data("customError");
          }
            break;
          case 'name':
          if (!app.checkName(val)){
            errorVal = input.data("customError");
          }
            break;
          case 'email':
          if (!app.checkEmail(val)){
            errorVal = input.data("customError");
          }
            break;
          case 'password':
          if (!app.checkPassword(val)){
            errorVal = input.data("customError");
          }
            break;
          case 'checkbox':
          if (input.prop('checked') == false){
            valid = false;
            label.popover({
              trigger: 'manual',
              placement: 'right',
              container: 'body',
              delay: 0,
              content:  input.data("customError")
            }).popover('show');
          }
            break;
          case 'confirm':
          val2 = $(this).parents('.form-group').parents('form').find("input[data-type='password']").val();
          if (!app.checkConfirmPassword(val, val2)){
            errorVal = input.data("customError");
          }
            break;
          }
        }
        if (errorVal !== false){
          valid = false;
          formGroup.addClass('has-error').removeClass('has-success');
          input.popover({
            trigger: 'manual',
            placement: 'right',
            container: 'body',
            delay: 0,
            content: errorVal
          }).popover('show');
        } else {
          formGroup.addClass('has-success').removeClass('has-error');
        }
      });
      return valid;
    },
    removeError: function() {
      $(this).popover('destroy').parents('.form-group').removeClass('has-error');
    },
    checkEmail: function(value) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,6})+$/;
      return regex.test(value);
    },
    checkName: function(value) {
      return (value.length>1 && value.length<21);
    },
    checkLogin: function(value) {
      var regex = /^[a-z]+[a-z0-9]*$/i;
      return regex.test(value);
    },
    checkPassword: function(value) {
      return value.length>4;
    },
    checkConfirmPassword: function(valueConfirm, valuePass) {
      return (valuePass == valueConfirm);
    },
  };
  app.initialize();
}());