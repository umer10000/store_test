
    $(".image-upload :file").on('change', function () {

        var file = this.files[0];
        var fileType = file["type"];
        var ValidImageTypes = ["image/gif", "image/jpeg", "image/png", "gif"];
        if ($.inArray(fileType, ValidImageTypes) < 0) {
            alert("Invalid File Format");
        } else {
         //   $(this).parents('.image-upload').prepend('<i class="fa fa-times" aria-hidden="true"></i>');
            $(this).parents('.file-btn').siblings('img').attr('src', URL.createObjectURL(this.files[0]));
            $('#door_preview .main_img').attr('src', URL.createObjectURL(this.files[0]));

        }
    });
    $(".image-upload").on('click', 'i', function () {
        $(this).parents('.image-upload').children(':file').val('');
        $(this).parents('.image-upload').children('img').attr('src', 'http://localhost/laravelecommerce/public/admin/dist/img/placeholder.png');
        $(this).remove();
    });
    $("body").on('change', '.multi-image-upload :file', function () {
        var file = this.files[0];
        var fileType = file["type"];
        var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
        if ($.inArray(fileType, ValidImageTypes) < 0) {
            alert("Invalid File Format");
        } else {
            let temp = $(this).parents('.multi-image-upload:last').clone();
            $(this).parents('.input-group-btn').append(temp).find('input:last').val('');
            $(this).parents('.multi-image-upload').prepend('<i class="fa fa-times" aria-hidden="true"></i>');
            $(this).parents('.file-btn').siblings('img').attr('src', URL.createObjectURL(this.files[0]));
            $(this).siblings('label').remove();
        }
    });
    $("body").on('click', '.multi-image-upload i', function () {
        $(this).parents('.multi-image-upload').remove();
        console.log('test');
    });

