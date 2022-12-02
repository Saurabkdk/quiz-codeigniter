<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="../frontend/js/employe/employlist.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php if(isset($list)) { ?>
    <br>
    <a href="./index"><button class="btn-info btn center-block">Add employee</button></a>
    <br>
    <br>
    <div class="form-group" id="read">
        <table class="table-hover table-condensed table-bordered container">
            <tbody class="text-center">
                
                <tr>
                    <th class="text-center">EMPLOYEE NAME</th>
                    <th class="text-center">EMPLOYEE ID</th>
                    <th class="text-center">EMPLOYEE EMAIL</th>
                    <th></th>
                    <th></th>
                </tr>
                
                <?php 
                foreach($list as $data) { ?>
                    <tr>
                        <td><?=$data->employename ?></td>
                        <td><?=$data->employeid ?></td>
                        <td><?=$data->employemail ?></td>
                        <td><a href="./showview?id=<?=$data->employeid ?>"><input type="button" name="UPDATE" value="Update" id="updateBtn" /></a></td>
                        <td><a href="./deleteview?id=<?=$data->employeid ?>"><input type="button" name="DELETE" value="Delete" id="deleteBtn" /></a></td>

                    </tr>
               <?php } ?>
            </tbody>
        </table>

        <br>

        <div>
            <p>Fetch from jquery</p>
        </div>

    </div>

    <br>
    <br>
    <?php echo $this->session->flashdata('message').'<br>'; ?>
    <?php } ?>
