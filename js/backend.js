function bxAjaxSubmit(form) {
    event.preventDefault();
    form = $(form);
    let success_class = $('.bx-ajax-success');
    let error_class = $('.bx-ajax-error');

    $('.error').removeClass();

    $.ajax({
        url: form.attr('action'),
        data: form.serialize(),
        type: 'POST',
        success: function(data){
            if (data.success) {

                success_class.children('p').html('Спасибо! Ваша заявка принята.');
                success_class.addClass('js-notification__active').removeClass('notification__error');
                setTimeout(function () {
                    success_class.removeClass('js-notification__active');
                    $('.md-modal').removeClass('md-show');
                }, 6000);
            }
        },
        error: function(data){
            console.log(data.responseJSON.error);
            let errors = data.responseJSON.error;

            function showError(errorType) {
                error_class.addClass('js-notification__active').addClass('notification__error');
                error_class.children('p').html(errors.message);
                form.find('input[name='+errorType+']').closest("label").addClass('error');
                setTimeout(function () {
                    error_class.removeClass('js-notification__active');
                }, 6000);
            }

            if (errors.name) {
                showError('name')
            }

            if (errors.email) {
                showError('email')
            }

            if (errors.phone) {
                showError('phone')
            }
        }
    });

}
