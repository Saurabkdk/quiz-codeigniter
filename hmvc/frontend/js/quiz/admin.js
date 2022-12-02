$(function () {

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let url = path + '/login';

    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: (data) => {

            let adminLogin = data.admin;

            if (adminLogin){
                $('.container').hide();
                $('.container1').show();
                $('.adminMsg').hide();
            }
            else{
                $('.container').show();
                $('.container1').hide();
                $('.adminMsg').hide();
            }
        },
        error: function(xhr, status, error) {
            var err = xhr.responseText;
            console.log(err);
        }
    });

    $('#loginBtn').click(() => {
        var admin = $('#login').val();
        $.ajax({
            type: 'POST',
            url: url,
            data: {'admin' : admin},
            dataType: 'json',
            success: (data) => {
                if(!(data.status)){
                    // window.location.replace(path + '/login');
                }

                console.log(data.message);

                let adminLogin = data.admin;

                if (adminLogin){
                    $('.container').hide();
                    $('.container1').show();
                    $('.adminMsg').hide();
                }
                else{
                    $('.container').show();
                    $('.container1').hide();
                    $('.adminMsg').html(data.message);
                    $('.adminMsg').show();
                }

            }
        });
    });

});