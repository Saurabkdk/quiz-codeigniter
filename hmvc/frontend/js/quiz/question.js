$(function(){
    const idArray = [0];
    var increment = 1;
    let pathname = window.location.pathname.split('/');
    let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];
    var date_time = new Date().toLocaleString();

    var nextBtn = document.getElementById('next');
    var previousBtn = document.getElementById('previous');

    if (localStorage.getItem('name') == null){
        localStorage.setItem('name', 'player');
        window.location.replace(path + '/index');
    }

    var name = localStorage.getItem('name');

    $('#name').html("Hey, " + name);

    function generateRandom(){
        var $repeat = true;
            while ($repeat && idArray.length < 11) {
                var id = Math.ceil(Math.random() * 10);
                for (let i = 0; i < idArray.length; i++) {
                    if (id == idArray[i]) {
                        i = idArray.length + 1;
                        $repeat = true;
                    } else {
                        $repeat = false;
                    }
                }
                if (!$repeat) {
                    idArray.push(id);
                }
                $repeat = true;
            }
    }

    function ajaxPostPlay(playDetails){
        let url = path + '/save';
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {'playDetails' : playDetails},
            success: (data) => {
                // data = JSON.parse(data);
                console.log(data.status);
            }
        });
    }

    function nextButton(index, checkId, saveLocal, timeCount){

        clearInterval(timeCount);
        var id = idArray[index + 1];

        // localStorage.setItem(checkId.toString(), JSON.stringify(saveLocal));

        ajaxPostPlay(saveLocal);

        showQuestion(id);

    }

    function previousButton(index, checkId, saveLocal, timeCount, timeLeftPrevious){
        if (index > 1 && timeLeftPrevious > 0) {
            clearInterval(timeCount);
            var id = idArray[index - 1];

            // localStorage.setItem(checkId.toString(), JSON.stringify(saveLocal));

            showQuestion(id);
        }

    }

    function buttonClick(index, timeCount){
        for (let m = 1; m < idArray.length; m++) {
            let showId = idArray[m];
            if(JSON.parse(localStorage.getItem(showId)) != null){
                let t = JSON.parse(localStorage.getItem(showId));

                if (m == index){
                    document.getElementById(`b${m}`).style.backgroundColor = 'dodgerblue';
                }
                else{
                    document.getElementById(`b${m}`).style.backgroundColor = '2ea44f';
                }

                if (t[0] == 0) {
                    document.getElementById(`b${m}`).disabled = true;
                    document.getElementById(`p${m}`).innerHTML = '0s';
                }
                else{
                    document.getElementById(`p${m}`).innerHTML = t[0] + 's';
                    document.getElementById(`b${m}`).disabled = false;
                }

                if (document.getElementById(`b${m}`).disabled){
                    document.getElementById(`b${m}`).style.backgroundColor = '#94d3a2';
                }

            }
            document.getElementById(`b${m}`).onclick = () => {
                {
                    if (JSON.parse(localStorage.getItem(showId)) != null && JSON.parse(localStorage.getItem(showId)) != 0) {

                        clearInterval(timeCount);
                        showQuestion(showId);

                    }
                }
            }
        }
    }

    function quizTimer(checkId, answer, index){
        if (JSON.parse(localStorage.getItem(checkId)) != null){
            var time = JSON.parse(localStorage.getItem(checkId));
            var timer = Number.parseInt(time[0]);
        }
        else{
            var timer = 20;
        }

        document.getElementById('timer').innerHTML = String(timer);

        var saveLocal = [];

        var timeCount = setInterval(() => {
            if (timer > 0) {
                timer--;
            }
            document.getElementById('timer').innerHTML = String(timer);

            saveLocal[0] = timer;
            saveLocal[1] = checkId;
            saveLocal[2] = 0;
            saveLocal[3] = 0;
            saveLocal[4] = name;
            saveLocal[5] = date_time;

            var value = document.getElementsByName('option');

            for (let i = 0; i < value.length; i++){
                if (value[i].checked) {
                    saveLocal[2] = Number.parseInt((value[i].value));
                    if (saveLocal[2] == answer){
                        saveLocal[3] = 1;
                    }
                }
            }

            if(timer < 8){
                document.getElementById('best').innerHTML = 'Hurry up mate!';
            }
            else{
                document.getElementById('best').innerHTML = 'Best of luck mate!';
            }

            if (timer <= 0){
                saveLocal[0] = timer;
                nextButton(index, checkId, saveLocal, timeCount);
            }

            localStorage.setItem(checkId.toString(), JSON.stringify(saveLocal));

            buttonClick(index, timeCount);

        }, 1000);

        if (JSON.parse(localStorage.getItem(checkId))){
            var checkTime = JSON.parse(localStorage.getItem(checkId));
            var timeLeft = Number.parseInt(checkTime[0]);

            if (timeLeft <= 0){
                document.getElementById('a').disabled = true;
                document.getElementById('b').disabled = true;
                document.getElementById('c').disabled = true;
                document.getElementById('d').disabled = true;
            }
        }

        var buttonDisable = setInterval(() => {
            nextBtn.disabled = false;
            previousBtn.disabled = false;
            clearInterval(buttonDisable);
        }, 1000);

        nextBtn.disabled = true;
        previousBtn.disabled = true;

        document.getElementById('next').onclick = () => {
            nextButton(index, checkId, saveLocal, timeCount);
        };

        var previousId = idArray[index - 1]

        if (JSON.parse(localStorage.getItem(previousId)) != null){
            var checkPrevious = JSON.parse(localStorage.getItem(previousId));
            var timeLeftPrevious = Number.parseInt(checkPrevious[0]);

            if (timeLeft <= 0){
                document.getElementById('a').disabled = true;
                document.getElementById('b').disabled = true;
                document.getElementById('c').disabled = true;
                document.getElementById('d').disabled = true;
            }
        }
        else{
            var timeLeftPrevious = 20;
        }

        document.getElementById('previous').onclick = () => {
            previousButton(index, checkId, saveLocal, timeCount, timeLeftPrevious);
        };

    }

    function ajaxGetQuestion(url, id){
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {'id':id},
            success: (data) => {

                document.getElementById('a').checked = false;
                document.getElementById('b').checked = false;
                document.getElementById('c').checked = false;
                document.getElementById('d').checked = false;

                var checkId = Number.parseInt(data['question'].id);

                var answer = Number.parseInt(data['options'].answer);

                var index = idArray.indexOf(id);

                increment = index;

                if (index == 10){
                    $('#next').prop('value', 'Submit');
                }
                else{
                    $('#next').prop('value', 'Next');
                }

                $('#question').fadeOut();

                $('#question').html(data['question'].question);
                $('#quesNo').html('Question ' + index + ' of 10');
                $('#aoption').html(data['options'].a);
                $('#boption').html(data['options'].b);
                $('#coption').html(data['options'].c);
                $('#doption').html(data['options'].d);

                $('#question').fadeIn();

                if(JSON.parse(localStorage.getItem(checkId)) != null){
                    var checkSelected = JSON.parse(localStorage.getItem(checkId));
                    var selected = Number.parseInt(checkSelected[2]);

                    if (selected > 0){
                        $('input:radio[name="option"][value=' + selected + ']').prop('checked',true);
                    }
                }

                quizTimer(checkId, answer, index);

            }
        });
    }

    function showQuestion(arrayId){

        if (arrayId > 0) {
            id = arrayId;
        }
        else{
            var id = idArray[increment];
            increment++;
        }
        if (arrayId == 0){
            for (let i = 1; i <= 10; i++) {
                localStorage.removeItem(i);
            }
        }

        if (increment == 11){
            idArray.length = 0;
            localStorage.clear();
            window.location.replace(path + '/index');
        }
        else{

            let url = path + `/question`;

            ajaxGetQuestion(url, id);

        }

    }

    generateRandom();

    showQuestion(0);

    $('#cancel').click(() => {
        if (localStorage.getItem('name') != null) {
            var deleteName = localStorage.getItem('name');
        }
        else{
            var deleteName = '0';
        }

       window.location.replace(path + `/delete?name=${deleteName}`);
    });

})