<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/load.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/quiz/load.css">
</head>
<body>

<div class="head1">

    <div><p id="quesNo1">Welcome</p></div>

</div>
<div class="container" id="load">
    <div id="message"><?= $this->session->flashdata('message'); ?></div>
    <form action="">
        <div class="form-item">
            <p class="formLabel">Username</p>
            <input type="text" name="playerName" id="playerName" required class="form-style" autocomplete="off"/>
        </div>
    </form>
    <input type="button" value="Play" id="play" class="button-3" role="button">
    <div class="rules">
        <h3>Rules</h3>
        <ul>
            <li>You will have 20 seconds for each question.</li>
            <li>You might change your answer within the time limit, not after it.</li>
            <li>Clicking number buttons will display the seen questions if time is left.</li>
            <li>After 20 seconds, answer will auto submit.</li>
            <li>Use your time wisely.</li>
        </ul>
    </div>
</div>

</body>
</html>
