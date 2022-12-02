$(function (){
   $('#play').click(() => {

       let playerName = $('#playerName').val();

       let pathname = window.location.pathname.split('/');
       let path = window.location.origin + '/' +  pathname[1] + '/' + pathname[2];

       let url = path + `/player`;

       $.post(url,{
           playerName
       }, function (data){
           data = JSON.parse(data);

           if (data.status){
               window.location.replace(path + '/question');
           }
           else {
               window.location.replace(path + '/index');
           }

           let player = data.player;

           localStorage.setItem('name', player);

           console.log(data.message);

       });

   }) ;
    localStorage.clear();
});