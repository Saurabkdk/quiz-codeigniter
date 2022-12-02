$(function() {
    $('#createBtn').click(() => {
        var EMPLOYENAME = $('#employename').val();
        var EMPLOYEID = $('#employeid').val();
        var EMPLOYEMAIL = $('#employemail').val();

        // $_GET = <?php echo json_encode($_GET); ?>;

        var id = Number.parseInt(window.location.href.match(/(?<=id=)(.*?)[^&]+/));



        if(id > 0){
            // var url = `<?=base_url()."employe/saveEmployee?id=" ?>${id}`;
            var url = window.location.host + `../../saveEmployee?id=${id}`;
        }
        else{
            // var url = "<?=base_url()."employe/saveEmployee" ?>";
            var url = window.location.host + `../../saveEmployee`;
        }

        $.post(url, {
                EMPLOYENAME,
                EMPLOYEID,
                EMPLOYEMAIL
            },
            function(checkdata){

                checkdata = JSON.parse(checkdata);

                if(checkdata.status) {
                    window.location.replace(window.location.host + '../../showview');
                }

                if (checkdata.jsonCheck){
                    alert(checkdata.message);
                }
                else{
                    console.log(checkdata.message);
                }

            }
        );

    });
});



