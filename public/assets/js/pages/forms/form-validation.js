$(function () {
    // console.log(CKEDITOR.instances.ckeditor.updateElement());
    $('#form_validation').validate({
        ignore: [],
        rules: {
            category_id: {
                required: true
            },
            content: {
                required: true,
            },
            img: {
                required: true
            }
        },
        highlight: function (input) {
            console.log(123);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            console.log(456);
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            console.log(element.attr('name'));

            $(element).parents('.form-group').append(error);
        }
    });
});