$(function () {

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let url = path + '/billing';

    let add = [1];
    let addCount = 1;

    if ($('#rows').length > 0){
        let addNum = Number.parseInt($('#rows').text());
        for (let i = 0; i < addNum; i++) {
            add[i] = 1;
        }
    }

    let eleId = 0;
    let btnId = '0';
    let bill = 0;

    function ajaxCall(type, url, dat, table, id, calculations) {
        $.ajax({
            type: type,
            url: url,
            dataType: 'json',
            data: {'data1': dat[0], 'data2': dat[1], 'table': table, 'id': id},
            success: (response) => {
                if (type == 'POST') {
                    console.log(response.status);
                    if (response.message != undefined){
                        alert(response.message);
                    }
                    if (response.status) {
                        clearInterval(calculations);
                        window.location.replace(url);
                    }
                }
                else{
                    displayTests(response.data);
                }
            }
        })
    }

    function displayTests(data){
        for (let i = 0; i < data.length; i++) {
            test = '<tr>';
            test += '<td>' + data[i].id +'</td>';
            test += '<td>' + data[i].patient_id +'</td>';
            test += '<td>' + data[i].test_item +'</td>';
            test += '<td>' + data[i].quantity +'</td>';
            test += '<td>' + data[i].unit +'</td>';
            test += '<td>' + data[i].price +'</td>';
            test += '</tr>';
            $('#testTbody').append(test);
        }
    }

    if (document.getElementById('billing') != null) {

        function addRow(rowNum) {
            row = '<tr id="' + rowNum + '">';
            row += '<td>' + rowNum + '</td>';
            row += '<td><input type="text" placeholder="Test item" name="test' + rowNum + '" id="test' + rowNum + '" class="form-style" /></td>';
            row += '<td><input type="number" placeholder="Unit price" step=".01" name="unit' + rowNum + '" id="unit' + rowNum + '" class="form-style" /></td>';
            row += '<td><input type="number" placeholder="Quantity" name="quantity' + rowNum + '" id="quantity' + rowNum + '" class="form-style" /></td>';
            row += '<td><p id="price' + rowNum + '">0</p></td>';
            row += '<td><input type="button" id="clear' + rowNum + '" value="Clear" class="button-3" role="button"></td>';
            row += '</tr>';
            $('#tbody').append(row);
        }

        $('#addRow').click(() => {
            add.push(1);
            addRow(add.length);
        });


        function addPrice(unit, quantity, eleId) {
            price = unit;
            if (quantity > 0) {
                price = unit * quantity;
            }
            $('#price' + eleId).text(price);
        }

        function findSubTotal() {
            let subtotal = 0;
            for (let i = 1; i <= add.length; i++) {
                if ($('#price' + i).length > 0) {
                    subtotal += Number.parseInt($('#price' + i).text());
                }
                $('#subtotal').text('Rs ' + subtotal);
            }
            return subtotal;
        }

        function addDiscountPercent(subtotal) {
            let discountPercent = 0;
            if (Number.parseInt($('#discountP').val()) > 0) {
                discountPercent = Number.parseInt($('#discountP').val());
            }
            let discountAmount = (discountPercent / 100 * subtotal).toFixed(2);
            $('#discountA').val(discountAmount);
            return discountAmount;
        }

        function addDiscountAmount(subtotal) {
            let discountAmount = 0;
            if (($('#discountA').val()).length > 0) {
                discountAmount = Number.parseInt($('#discountA').val());
            }
            let discountPercent = (discountAmount / subtotal * 100).toFixed(2);
            $('#discountP').val(discountPercent);
        }

        function findNetTotal(subtotal, discountAmount) {
            let netTotal = subtotal - discountAmount;
            $('#nettotal').text('Rs ' + netTotal);
        }

        var unit = [0];
        var quantity = [1];

        for (let i = 0; i < add; i++) {
            if ($('#unit' + (i + 1)).val() != ''){
                unit[i + 1] = Number.parseInt($('#unit' + (i + 1)).val());
            }
            if ($('#quantity' + (i + 1)).val() != ''){
                quantity[i + 1] = Number.parseInt($('#quantity' + (i + 1)).val());
            }
        }

        const calculations = setInterval(() => {

            const input = document.getElementsByTagName("input");

            for (let inp of input) {
                inp.addEventListener('click', (e) => {
                    let id = e.target.id;
                    if ($('#' + id).length > 0) {
                        eleId = Number.parseInt(document.getElementById(id).parentElement.parentElement.id);
                    }
                    if (id == 'clear' + eleId){
                        $('#' + eleId).remove();
                        // if (addCount == 1){
                        //     add.pop();
                        //     addCount = 0;
                        // }
                    }
                })
            }

            addCount = 1;

            $('#unit' + eleId).on('input', function (u) {
                unit[eleId] = 0 + Number.parseInt(u.target.value);
                if (u.target.value == '') {
                    unit[eleId] = 0;
                }
                addPrice(unit[eleId], quantity[eleId], eleId);
            })

            $('#quantity' + eleId).on('input', function (u) {
                quantity[eleId] = 0 + Number.parseInt(u.target.value);
                if (u.target.value == '') {
                    quantity[eleId] = 1;
                }
                addPrice(unit[eleId], quantity[eleId], eleId);
            })

            let subtotal = findSubTotal();

            let discountAmount = 0;

            if ($('#discountA').val().length > 0) {
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

        $('#save').click(() => {
            let billId = 0;

            if ($('#billId').length > 0){
                billId = Number.parseInt($('#billId').val());
            }

            let date = new Date().toLocaleString();

            let table1 = [];
            if (($('#patientId').val()).length > 0) {
                table1.push(Number.parseFloat($('#patientId').val()));
            }
            else{
                table1.push(($('#patientId').val()));
            }
            table1.push(date);

            let subt = ($('#subtotal').text()).replace('Rs ', '');
            table1.push(Number.parseInt(subt));

            if (($('#discountP').val()).length > 0) {
                table1.push(Number.parseFloat($('#discountP').val()));
            }
            else{
                table1.push(($('#discountP').val()));
            }

            if (($('#discountA').val()).length > 0) {
                table1.push(Number.parseFloat($('#discountA').val()));
            }
            else{
                table1.push(($('#discountA').val()));
            }

            let nett = ($('#nettotal').text()).replace('Rs ', '');
            table1.push(Number.parseInt(nett));

            table1.push(billId);


            let table2 = [];
            for (let i = 0; i < add.length; i++) {
                var data = [];

                let testId = 0;

                if ($('#testId' + (i + 1)).length > 0){
                    testId = Number.parseInt($('#testId' + (i + 1)).text());
                }

                if ($('#test' + (i + 1)).length > 0) {

                    if (($('#patientId').val()).length > 0) {
                        data[0] = Number.parseInt($('#patientId').val());
                    } else {
                        data[0] = ($('#patientId').val());
                    }
                    data[1] = $('#test' + (i + 1)).val();

                    if (($('#quantity' + (i + 1)).val()).length > 0) {
                        data[2] = Number.parseFloat($('#quantity' + (i + 1)).val());
                    } else {
                        data[2] = ($('#quantity' + (i + 1)).val());
                    }

                    if (($('#unit' + (i + 1)).val()).length > 0) {
                        data[3] = Number.parseFloat($('#unit' + (i + 1)).val());
                    } else {
                        data[3] = ($('#unit' + (i + 1)).val());
                    }
                    data[4] = Number.parseFloat($('#price' + (i + 1)).text());
                    data[5] = table1[1];
                    data[6] = testId;
                    table2.push(data);
                }
            }

            let dat = [table1, table2];
            let table = ['bills', 'tests'];

            console.log(table1);
            ajaxCall('POST', url, dat, table, calculations);

        })

        let work = Number.parseInt(document.getElementById('patientId').className);

        if (work == 0){
            document.getElementById('addRow').disabled = true;
            document.getElementById('save').disabled = true;
            window.location.replace(path + '/addPatient');
        }
    }

    const button = document.getElementsByTagName('button');

    for (let but of button){
        but.addEventListener('click', (e) => {
            let id = e.target.id;
            btnId = id;
            bill = document.getElementById(id).parentElement.parentElement.id;

            ajaxCall('GET', path + '/tests', [], [], bill);

            $('#tests').show();
            $('#billList').hide();
            $('#billing').hide();
        })
    }

    $('#change').click(() => {
        $('#patientId').attr('readonly', false);
    })

    $('#billsBtn').click(() => {
        window.location.replace(url);
    })

    $('#bill').click(() => {
        window.location.replace(url);
    })

    $('.list').click(() => {
        window.location.replace(path + '/addPatient');
    })

});