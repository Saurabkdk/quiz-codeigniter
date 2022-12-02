<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/admin.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/quiz/admin.css">
</head>
<body>

<div class="container">
    <div><h2>Login</h2></div>
    <form action="<?=base_url()?>quiz/login" method="POST">
        <div class="form-item">
            <p class="formLabel">Admin Password</p>
            <input type="password" name="admin" id="login" required class="form-style" placeholder="Admin Password" autocomplete="off"/>
        </div>
    </form>
    <input type="button" value="Login" id="loginBtn" class="button-3" role="button">
</div>
<div class="adminMsg"></div>


<div class="container1">
    <h1>Admin Portal</h1>
    <table class="table">
        <thead>
        <tr>
            <th>SN</th>
            <th>Player</th>
            <th>Total Questions</th>
            <th>Correct/Attempted/Total</th>
            <th>Played on</th>
            <th>Time consumed</th>
            <th></th>
        </thead>
        </tr>
        <tbody>
        <?php if (isset($player)) {
                foreach ($player as $play){
            ?>
            <tr>
                <td><?=$play['sn']; ?></td>
                <td><?=$play['name']; ?></td>
                <td><?=$play['total']; ?></td>
                <td><?=$play['correct'].'/'.$play['attempt'].'/'.$play['total']; ?></td>
                <td><?=$play['date']; ?></td>
                <td><?=$play['time']; ?>s</td>
                <td><a href="<?=base_url(); ?>quiz/playerResult?name=<?=$play['name'] ?>"><button class="button-3" role="button">Preview</button></a></td>
            </tr>
        <?php }} ?>
        </tbody>
    </table>
    <div id="logout"><a href="<?=base_url() ?>quiz/logout"><button class="button-3" role="button" style="background-color: red">Logout</button></a></div>
</div>

</body>
</html>