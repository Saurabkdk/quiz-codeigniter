$(function () {

    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

    let countryId = 0;
    let provinceId = 0;
    let patients = [];

    function createVal(val, name, elementId){
        option = '<option value="' + val + '" class="' + elementId + 'Option">';
        option += name;
        option += '</option>';
        $(`#${elementId}`).append(option);
    }

    function createPatientRow(patient){
        let row = '<tbody>';
        $.each(patient, (key, patient) => {
            row += '<tr class="' + patient.id +'">';
            row += '<td>' + (key + 1) +'</td>';
            row += '<td>' + (patient.id) +'</td>';
            row += '<td>' + (patient.name) +'</td>';
            row += '<td>' + (patient.age) +' / ' + patient.gender + '</td>';
            row += '<td>' + (patient.province) +'</td>';
            row += '<td>' + (patient.address) +'</td>';
            row += '<td>' + (patient.date) +'</td>';
            row += '<td>\n' +
                '<a href="' + path + '/patient/aboutPatient?id=' + patient.id + '"><button type="button" class="button-3" role="button">Preview</button></a>\n' +
                '&nbsp\n' +
                '<a href="'+ path +'/patient/billing?id=' + patient.id + '"><button class="button-3" role="button">Reg & Billing</button></a>\n' +
                '</td>';
            row += '</tr>';
        })
        row += '</tbody>';
        $('table').append(row);
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

    function ajaxCall(type, url, patientData, patientName){
        $.ajax({
            type: type,
            url: url,
            dataType: 'json',
            data: {'patient' : patientData, 'patientName' : patientName},
            success: (data) => {
                if (type == 'POST') {
                    if (data.message != undefined) {
                        alert(data.message);
                    }
                    if (data.status) {
                        window.location.replace(path + '/addPatient');
                    }
                }
                else{
                    $.each(data.patients, (key, patient) => {
                        patients.push(patient.name);
                    })
                    if (patientName){
                        $('#allPatients').hide();
                        createPatientRow(data.patients);
                    }
                }
            }
        })
    }

    function savePatient(){
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

        let pid = 0;

        if (document.getElementById('id') != null){
            pid = $('#id').val();
        }

        let country = $("#country :selected").val();
        let province = $('#province :selected').text();
        let municipality = $('#municipality :selected').text();
        let address = $('#address').val();
        let phone = $('#phone').val();

        let date = new Date().toLocaleString();

        let patientData = [name, age, gender, language, country, province, municipality, address, phone, date, pid];

        // {'name' : name, 'age' : age, 'gender' : gender,
        //     'language' : language[0], 'lang' : language, 'country' : country, 'province' : province, 'municipality' : municipality,
        //     'address' : address, 'phone' : phone, 'date' : date}

        if (pid > 0){
            ajaxCall('POST', path + '/addPatient', patientData);
        }
        else {
            ajaxCall('POST', path + '/addPatient', patientData);
        }
    }

    $('#addPatientBtn').click(() => {

        savePatient();

    });

    ajaxCall('GET', path + '/addPatient', []);

    $('#searchInp').autocomplete({
        source: patients,
        autofocus: true
    });

    $('#register').click(() => {
        $('#form').show();
        $('#table').hide();
    })

    $('#cancel').click(() => {
        window.location.replace(path + '/addPatient');
    })

    $('#bill').click(() => {
        window.location.replace(path + '/billing');
    })

    $('#edit').click(() => {
        $('#form').show();
        $('#detail').hide();
    })

    $('#cancelDetail').click(() => {
        window.location.replace(path + '/addPatient');
    })

    $('#search').click(() => {
        if (($('#searchInp').val()).length > 0){
            let searchName = $('#searchInp').val();
            ajaxCall('GET', path + '/addPatient', [], searchName);
        }
    })

});