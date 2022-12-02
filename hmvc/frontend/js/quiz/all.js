$(function (){

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let name = window.location.href.match(/(?<=name=)(.*?)[^&]+/);

    $('#name').html(name);

    $.ajax({
        type: 'GET',
        url: path + `/playerResult?name=${name[0]}`,
        dataType: 'json',
        success: (data) => {
            playerResult = data.data;

            $('.quizContainer').hide();

            document.getElementById('container').style.left = '440px';

            $.each(playerResult, (key, result) => {

                var idArr = ['a','b','c','d'];

                let value = [1,2,3,4];

                let idName = 0;

                for (let i = 0; i < result.length; i++) {

                    for (let i = 1; i <= value.length; i++){
                        document.getElementById(i.toString()).classList.remove('correct');
                        document.getElementById(i.toString()).classList.remove('incorrect');
                        $('#attempt').html('Correct');
                    }

                    for (let j = 0; j < value.length; j++){
                        if (Number.parseInt(value[j]) === Number.parseInt(result[i].answer)) {
                            var idNa = document.getElementById(idArr[value[j] - 1]);

                            idName = idNa.parentElement.id;
                        }
                    }

                    document.getElementById(idName).classList.add('correct');

                    if (result[i].answer != result[i].answer_submitted){

                        if (result[i].answer_submitted != 0) {
                            var idNa = document.getElementById(idArr[result[i].answer_submitted - 1]);

                            idName = idNa.parentElement.id;

                            document.getElementById(idName).classList.add('incorrect');

                            $('#attempt').html('Incorrect');
                        }
                        if (result[i].answer_submitted == 0){
                            $('#attempt').html('Not attempted');
                        }

                    }

                    $('#question').html(result[i].question);
                    $('#aoption').html(result[i].a);
                    $('#boption').html(result[i].b);
                    $('#coption').html(result[i].c);
                    $('#doption').html(result[i].d);


                    var clone = $('#container').clone();
                    $('body').append(clone);

                }


            })

        },
        // error: function(xhr, status, error) {
        //     var err = xhr.responseText;
        //     console.log(err);
        // }
    })

    $('#cancel').click(() => {
        window.location.replace(path + '/login');
    })

});