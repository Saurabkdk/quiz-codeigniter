<?php if (isset($_SESSION['player'])) { ?>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php if(!isset($playerResult)) { ?>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/question.js"></script>
    <?php } else{ ?>
        <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/all.js"></script>
    <?php } ?>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/quiz/question.css">

</head>
<body>
<div class="head">
    <div><p id="name">Name</p></div>
    <div><p id="quesNo">Questions and Answers</p></div>
    <div class="cancel"><button id="cancel" class="button-3" role="button">Cancel</button></div>

</div>
<div class="quizContainer">
    <div class="container" id="container">
        <?php if(!isset($playerResult)){ ?>
        <div class="timeQuiz">
            <div id="best">Best of luck mate!</div>
            <div id="timer">20</div>
        </div>
        <?php } ?>
        <div class="question"><h2 id="question">Question</h2></div>
        <div class="optionLine">
            <label class="labl first " id="1">
                <input type="radio" name="option" value="1" id="a"/>
                <div><p id="aoption">Option 1</p></div>
            </label>
            <label class="labl second " id="2">
                <input type="radio" name="option" value="2" id="b"/>
                <div><p id="boption">Option 2</p></div>
            </label>
        </div>
        <div class="optionLine">
            <label class="labl first " id="3">
                <input type="radio" name="option" value="3" id="c"/>
                <div><p id="coption">Option 3</p></div>
            </label>
            <label class="labl second " id="4">
                <input type="radio" name="option" value="4" id="d"/>
                <div><p id="doption">Option 4</p></div>
            </label>
        </div>
        <?php if(!isset($playerResult)){ ?>
        <div class="prevNext">
            <input type="button" value="Previous" id="previous" class="button-3" role="button">
            <input type="button" value="Next" id="next" class="button-3" role="button">
        </div>
        <?php } else { ?>
                <div class="onadmin">
                    <div class="onad">
                        <div class="show correct"></div>
                        <div> &nbsp Correct </div>
                    </div>
                    <div class="onad">
                        <div class="show incorrect"></div>
                        <div> &nbsp Submitted but incorrect </div>
                    </div>
                    <div class="onad">
                        <div>Player Result: &nbsp</div>
                        <div id="attempt">Attempt or not</div>
                    </div>
                </div>
        <?php } ?>
    </div>
    <?php if(!isset($playerResult)){ ?>
    <div class="timeContainer">
    <?php for ($i = 1; $i <= 10; $i++) { ?>
            <div class="questime">
                <button id="b<?=$i?>" name="<?=$i ?>" class="time button-3" role="button" disabled><?=$i ?></button>
                <p id="p<?=$i?>">20s</p>
            </div>
        <?php }} ?>
    </div>
</div>
</body>
</html>
<?php } else{
    redirect(base_url().'quiz/index');
} ?>

