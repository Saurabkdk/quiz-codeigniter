$(function () {

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let countryId = 0;
    let provinceId = 0;

    function createVal(val, name, elementId){
        option = '<option value="' + val + '" class="' + elementId + 'Option">';
        option += name;
        option += '</option>';
        $(`#${elementId}`).append(option);
    }

    function ajaxGetSelect(elementId, id, parent){
        let checkId = id;
        if (elementId == 'province' && (countryId > 0 || provinceId > 0)) {
            checkId = countryId;
        }
        else{
            checkId = provinceId;
        }

        if ($('#' + elementId).val() == 0 || $('#' + parent).val() != checkId) {

            $.ajax({
                type: 'POST',
                url: path + '/index',
                dataType: 'json',
                data: {'id' : id, 'element' : elementId},
                success: (data) => {
                    $('.' + elementId +'Option').remove();
                    $.each(data.record, (key, record) => {
                        if (elementId == 'province') {
                            createVal(record.id, record.province, elementId);
                            countryId = id;
                        }
                        else {
                            createVal(record.id, record.municipality, elementId);
                            provinceId = id;
                        }
                    })
                }
            })
        }
    }

    $('#country').click(() => {

        country = $('#country').val();

        ajaxGetSelect('province', country, 'country');

    })

    $('#province').click(() => {

        province = $('#province').val();

        ajaxGetSelect('municipality', province, 'province');

    })

    $('#addPatientBtn').click(() => {
        let name = $('#name').val();
        let age = $('#age').val();
        let gender;

        let genderElement = document.getElementsByName('gender');

        for (let i = 0; i < genderElement.length; i++) {
            if (genderElement[i].checked){
                gender = genderElement[i].value;
            }
        }

        let language = [];

        let languageElement = document.getElementsByName('language');

        for (let i = 0; i < languageElement.length; i++) {
            if (languageElement[i].checked){
                language.push(languageElement[i].value);
            }
        }

        let country = $("#country :selected").text();
        let province = $('#province :selected').text();
        let municipality = $('#municipality :selected').text();
        let address = $('#address').val();
        let phone = $('#phone').val();

        let date = new Date().toLocaleString();

        console.log(country);

        let patientData = [name, age, gender, language, country, province, municipality, address, phone, date];

        $.ajax({
            type: 'POST',
            url: path + '/addPatient',
            dataType: 'json',
            data: {'name' : name, 'age' : age, 'gender' : gender,
                    'language' : language[0], 'lang' : language, 'country' : country, 'province' : province, 'municipality' : municipality,
                    'address' : address, 'phone' : phone, 'date' : date},
            success: (data) => {
                alert(data.message)
                if (!(data.status)){
                    window.location.replace(url);
                }
                else{
                    window.location.replace(path + '/patientList');
                }
            }
        })

    });

    $('#register').click(() => {
        $('#form').show();
        $('#table').hide();
    })

    $('#cancel').click(() => {
        window.location.replace(path + '/patient/addPatient');
    })

});