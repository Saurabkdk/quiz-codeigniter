$(function () {

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let url = path + '/divs';

    let add = 1;
    let eleId = 0;

    function addRow(rowNum){
        row = '<tr id="' + rowNum +'">';
        row += '<td>' + rowNum +'</td>';
        row += '<td><input type="text" placeholder="Test item" name="test' + rowNum + '" id="test' + rowNum +'" /></td>';
        row += '<td><input type="number" placeholder="Unit price" step=".01" name="unit' + rowNum +'" id="unit' + rowNum + '" /></td>';
        row += '<td><input type="number" placeholder="Quantity" name="quantity' + rowNum +'" id="quantity' + rowNum +'" /></td>';
        row += '<td><p id="price' + rowNum +'">0</p></td>';
        row += '</tr>';
        $('#tbody').append(row);
    }

    $('#addRow').click(() => {
        add++;
        addRow(add);
    });


    function addPrice(unit, quantity, eleId){
        price = unit;
        if (quantity > 0) {
            price = unit * quantity;
        }
        $('#price' + eleId).text(price);
    }

    function findSubTotal(){
        let subtotal = 0;
        for (let i = 1; i <= add; i++) {
            subtotal += Number.parseInt($('#price' + i).text());
            $('#subtotal').text('Rs ' + subtotal);
        }
        return subtotal;
    }

    function addDiscountPercent(subtotal){
        let discountPercent = 0;
        if (Number.parseInt($('#discountP').val()) > 0){
            discountPercent = Number.parseInt($('#discountP').val());
        }
        let discountAmount = (discountPercent / 100 * subtotal).toFixed(2);
        $('#discountA').val(discountAmount);
        return discountAmount;
    }

    function addDiscountAmount(subtotal){
        let discountAmount = 0;
        if(($('#discountA').val()).length > 0){
            discountAmount = Number.parseInt($('#discountA').val());
        }
        let discountPercent = (discountAmount / subtotal * 100).toFixed(2);
        $('#discountP').val(discountPercent);
    }

    function findNetTotal(subtotal, discountAmount){
        let netTotal = subtotal - discountAmount;
        $('#nettotal').text('Rs ' + netTotal);
    }

        var unit = [0];
        var quantity = [1];

        const calculations = setInterval(() => {

            const input = document.getElementsByTagName("input");

            for (let inp of input){
                inp.addEventListener('click', (e) => {
                    let id = e.target.id;
                    eleId = Number.parseInt(document.getElementById(id).parentElement.parentElement.id)
                })
            }

            $('#unit' + eleId).on('input', function (u) {
                unit[eleId] = 0 + Number.parseInt(u.target.value);
                if (u.target.value == ''){
                    unit[eleId] = 0;
                }
                addPrice(unit[eleId], quantity[eleId], eleId);
            })

            $('#quantity' + eleId).on('input', function (u) {
                quantity[eleId] = 1 * Number.parseInt(u.target.value);
                if (u.target.value == ''){
                    quantity[eleId] = 1;
                }
                addPrice(unit[eleId], quantity[eleId], eleId);
            })

            let subtotal = findSubTotal();

            let discountAmount = 0;

            if ($('#discountA').val().length > 0){
                discountAmount = Number.parseInt($('#discountA').val());
            }

            $('#discountP').on('input', function () {
                discountAmount = addDiscountPercent(subtotal);
            })

            $('#discountA').on('input', function () {
                addDiscountAmount(subtotal);
            })

            findNetTotal(subtotal, discountAmount);

        }, 100);

        function ajaxPost(url, data, table, data2, table2){
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {'data': data, 'table': table},
                success: (data) => {
                    console.log(data);
                    ajaxPost(path + '/patient/billing', data2, table2);
                    if (data.status){
                        window.location.replace(path + '/patient/billing');
                    }
                }
            })
        }

        $('#save').click(() => {
            clearInterval(calculations);

            let date = new Date().toLocaleString();

            let table1 = [];
            table1.push(Number.parseInt($('#patientId').val()));
            table1.push(date);

            let subt = ($('#subtotal').text()).replace('Rs ', '');

            table1.push(Number.parseInt(subt));
            table1.push(Number.parseFloat($('#discountP').val()));
            table1.push(Number.parseInt($('#discountA').val()));

            let nett = ($('#nettotal').text()).replace('Rs ', '');
            table1.push(Number.parseInt(nett));


            let table2 = [];
            for (let i = 0; i < add; i++) {
                var data = [];
                data[0] = Number.parseInt($('#patientId').val());
                data[1] = $('#test' + (i + 1)).val();
                data[2] = Number.parseFloat($('#quantity' + (i + 1)).val());
                data[3] = Number.parseFloat($('#unit' + (i + 1)).val());
                data[4] = Number.parseFloat($('#price' + (i + 1)).text());
                data[5] = table1[1];
                table2[i] = data;
            }

            ajaxPost(path + '/patient/billing', table1, 'bills', table2, 'tests');

        })

});