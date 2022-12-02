<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="../frontend/js/employe/employeview.js"></script>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->

<!-- jQuery library -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>-->

<!-- Latest compiled JavaScript -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->

<link rel="stylesheet" href="">

<div class="container">

    <?php echo validation_errors(); ?>

    <form action="<?=base_url()?>employe/saveEmployee?id=<?php if(isset($one)) echo $one->employeid ?>" method = 'POST' class="form-group">
        <div class="row">
            <div class="col-md-4 form-group">
                <lable>Employe Name</lable>
                <input type="text" class="form-control" name="EMPLOYENAME" id="employename" value="<?php if(isset($one)) echo $one->employename ?>"/>
            </div>

            <div class="col-md-4 form-group">
                <lable>Employe ID</lable>
                <input type="text" class="form-control" name="EMPLOYEID" id="employeid" value="<?php if(isset($one)) echo $one->employeid ?>"/>
            </div>

            <div class="col-md-4 form-group">
                <lable>Employe Email</lable>
                <input type="text" class="form-control" name="EMPLOYEMAIL" id="employemail" value="<?php if(isset($one)) echo $one->employemail ?>"/>
            </div>


            <?php if(isset($one)) {?>
                <input type="button" value="Update" id="createBtn" />
                <a href="./showview"><input type="button" value="Cancel" id="cancelBtn" /></a>
            <?php } else { ?>
                <input type="button" value="Create" id="createBtn" />
            <?php } ?>

        </div>
    </form>

    <br>
    <a href="showview"><button>Employee list</button></a>

</div>

</div>
