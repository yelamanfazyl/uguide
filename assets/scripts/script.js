$(function () {
    $('#select-region').change(function (e) { 
        e.preventDefault();
        
        $('#select-country').val('');

        $('#form-main').submit();
    });

    $('#select-country').change(function (e) { 
        e.preventDefault();
        
        $('#select-univer').val('');

        $('#form-main').submit();
    });
    
    $('#select-univer').change(function (e) { 
        e.preventDefault();
        
        $('#select-exam1').val('');

        $('#form-main').submit();
    });

    $('#select-exam1').change(function (e) { 
        e.preventDefault();
        
        $('#select-exam2').val('');

        $('#form-main').submit();
    });

    $('#select-exam2').change(function (e) { 
        e.preventDefault();
        
        
        $('#form-main').submit();
    });

    $('.gallery-create-images').imageUploader({
        label: 'Выберите изображения или перетащите сюда',
        maxFiles: 1
    });
});