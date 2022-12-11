$(function (){

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let url = path + '/divs';

    var add = 1;

    function addARow(rowNum){
        let rowAdd = '<tr>';
        rowAdd += '<td>' + rowNum + '</td>';
        rowAdd += '<td><input type="text" name="name' + rowNum + '" id="name' + rowNum + '">';
        rowAdd += '<td><input type="text" name="address' + rowNum + '" id="address' + rowNum + '">';
        rowAdd += '<tr>';
        $('tbody').append(rowAdd);
    }

    $('#button').click(() => {
        add++;
        addARow(add);
    })

    function random(){
        let random = Math.ceil(Math.random() * 10000000);
        return ('00000000' + random).slice(-7);
    }

    $('#view').click(() => {
        var values = new Array(add);
        for (let i = 0; i < add; i++){
            var data = [];
            data[0] = random();
            data[1] = $('#name'+ (i + 1)).val();
            data[2] = $('#address' + (i + 1)).val();
            values[i] = data;
        }
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {'values' : values},
            success: (data) => {
                // data = JSON.parse(data);
                console.log(data.status);
            }
        })
    })

});