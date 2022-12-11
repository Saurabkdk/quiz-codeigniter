<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/patient/addPatient.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/patient/addPatient.css">
    <title>Document</title>
</head>
<body>
<div><?= $this->session->flashdata('noId'); ?></div>
<?php if (isset($list)) { ?>
<div class="container" id="table">
    <div class="head">
        <h1>Patient List</h1>
        <h1>Midas Hospital</h1>
        <div class="search">
            <button type="button" id="register" class="button-3" role="button">Register</button>
            <input type="text" name="search" id="searchInp" class="form-style" />
            <button type="button" id="search" class="button-3" role="button">Search</button>
        </div>
    </div>
    <table>
    <thead>
    <tr>
        <th>SN</th>
        <th>Patient ID</th>
        <th>Patient Name</th>
        <th>Age / Gender</th>
        <th>District</th>
        <th>Address</th>
        <th>Registered Date</th>
        <th>Action</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($list as $key => $patient) { ?>
        <tr class="<?=$patient->id ?>">
            <td><?=$key + 1; ?></td>
            <td><?=$patient->id ?></td>
            <td><?=$patient->name ?></td>
            <td><?=$patient->age ?> / <?=$patient->gender ?></td>
            <td><?=$patient->province ?></td>
            <td><?=$patient->address ?></td>
            <td><?=$patient->date ?></td>
            <td><a href="<?= base_url(); ?>patient/aboutPatient?id=<?=$patient->id?>"><button type="button" class="button-3" role="button">Preview</button></a>&nbsp<a href="<?= base_url(); ?>patient/billing?id=<?=$patient->id ?>"><button class="button-3" role="button">Reg & Billing</button></a></td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
    <button type="button" id="bill" class="button-3" role="button">Patient Bills</button>
</div>
<?php } ?>

<div class="container" id="form">
    <div class="head">
        <h1>Register</h1>
        <h1>Midas Hospital</h1>
        <div class="search">
            <button type="button" id="cancel" class="button-3" role="button">Cancel</button>
        </div>
    </div>
<div>
    <?= $this->session->flashdata('message'); ?>
</div>
    <form>
        <div class="inputDes">
        <?php if (isset($details)) { ?>
            <label>Id</label>
            <input type="text" name="id" id="id" value="<?= $details[0]->id; ?>" class="form-style" readonly /><br>
        <?php } ?>

        <label>Name : </label>
        <input type="text" name="name" id="name" class="form-style" value="<?php if (isset($details)) echo $details[0]->name; ?>" required />

        <label>Age : </label>
        <input type="date" name="age" id="age" class="form-style" value="<?php if (isset($details)) echo date('Y-m-d',strtotime($details[0]->age)) ?>" required />

        </div><br>

        <label>Gender</label>
        <div class="py">
                <label>
                    <input type="radio" name="gender" class="option-input radio" value="M" <?php if (isset($details[0])) echo ($details[0]->gender == "M" ? 'checked="checked"': ''); ?> />
                    Male
                </label>
                <label>
                    <input type="radio" name="gender" class="option-input radio" value="F" <?php if (isset($details[0])) echo ($details[0]->gender == "F" ? 'checked="checked"': ''); ?> />
                    Female
                </label>
                <label>
                    <input type="radio" name="gender" class="option-input radio" value="O" <?php if (isset($details[0])) echo ($details[0]->gender == "O" ? 'checked="checked"': ''); ?> />
                    Other
                </label>
        </div>
        <br>

        <label>Language</label>
        <?php if (isset($details)) $language = explode(' ', $details[0]->language) ?>
            <div class="px">
        <label>
        <input type="checkbox" name="language" class="option-input checkbox" value="Nepali" <?php if(isset($details)) if(in_array("Nepali",$language)) echo 'checked="checked"'; ?> />
            Nepali
        </label>
        <label>
        <input type="checkbox" name="language" class="option-input checkbox" value="English" <?php if(isset($details)) if(in_array("English",$language)) echo 'checked="checked"'; ?> />
            English
        </label>
        <label>
        <input type="checkbox" name="language" class="option-input checkbox" value="Hindi" <?php if(isset($details)) if(in_array("Hindi",$language)) echo 'checked="checked"'; ?> />
            Hindi
        </label>
            </div>
        <br>

        <div class="inputDes">
        <label>Country</label>
        <select name="country" id="country">
            <option value="0"></option>
            <?php foreach ($countries as $country) { ?>
                <option value="<?= $country->id; ?>" <?php if (isset($details[0]) && $details[0]->country == $country->id) echo 'selected = "selected"' ?>><?=$country->country; ?></option>
            <?php } ?>
        </select><br>

        <label>Province</label>
        <select name="province" id="province">
            <option value="0"></option>
        </select><br>

        <label>Municipality</label>
        <select name="municipality" id="municipality">
            <option value="0"></option>
        </select>
        </div><br>

        <div class="inputDes">
        <label>Address : </label>
        <input type="text" name="address" id="address" class="form-style" value="<?php if (isset($details)) echo $details[0]->address; ?>" />

        <label>Mobile Number : </label>
        <input type="text" name="phone" id="phone" class="form-style" value="<?php if (isset($details)) echo $details[0]->phone; ?>" />
        </div><br>
    </form>
    <button type="button" id="addPatientBtn" class="button-3" role="button">Save Patient</button>
</div>

<?php if (isset($details)) { ?>
<div class="container1" id="detail">
    <div class="head">
        <h1>Register</h1>
        <h1>Midas Hospital</h1>
        <div class="search">
            <button type="button" id="cancelDetail" class="button-3" role="button">Cancel</button>
        </div>
    </div>
    <div class="details">
        <p>Patient Id : &nbsp;</p>
        <p><?=$details[0]->id ?></p>
    </div>
    <div class="details">
        <p>Name : &nbsp;</p>
        <p><?=$details[0]->name ?></p>
    </div>
    <div class="details">
        <p>Age : &nbsp;</p>
        <p><?=$details[0]->age ?></p>
    </div>
    <div class="details">
        <p>Gender : &nbsp;</p>
        <p><?=$details[0]->gender ?></p>
    </div>
    <div class="details">
        <p>Language : &nbsp;</p>
        <p><?=$details[0]->language ?></p>
    </div>
    <div class="details">
        <p>Country : &nbsp;</p>
        <p><?=$details[0]->country ?></p>
    </div>
    <div class="details">
        <p>Province : &nbsp;</p>
        <p><?=$details[0]->province ?></p>
    </div>
    <div class="details">
        <p>Municipality : &nbsp;</p>
        <p><?=$details[0]->municipality ?></p>
    </div>
    <div class="details">
        <p>Address : &nbsp;</p>
        <p><?=$details[0]->address ?></p>
    </div>
    <div class="details">
        <p>Phone : &nbsp;</p>
        <p><?=$details[0]->phone ?></p>
    </div>
    <div class="details">
        <p>Date : &nbsp;</p>
        <p><?=$details[0]->date ?></p>
    </div>
    <button type="button" id="edit" class="button-3">Edit</button>
</div>
<?php } ?>

</body>
</html>
