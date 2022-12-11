<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/addPatient.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/quiz/addPatient.css">
    <title>Document</title>
</head>
<body>
<div class="container" id="table">
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
        <tr>
            <td><?=$key + 1; ?></td>
            <td><?=$patient->id ?></td>
            <td><?=$patient->name ?></td>
            <td><?=$patient->age ?> / <?=$patient->gender ?></td>
            <td><?=$patient->province ?></td>
            <td><?=$patient->address ?></td>
            <td><?=$patient->date ?></td>
            <td><a href="<?= base_url(); ?>quiz/patient/billing?id=<?=$patient->id ?>"><button type="button">Preview</button>&nbsp<button>Reg & Billing</button></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
    <button type="button" id="register">Register</button>
</div>

<div class="container" id="form">
<div>
    <?= $this->session->flashdata('message'); ?>
</div>
    <button type="button" id="cancel">Cancel</button>
    <form>
        <label>Name</label>
        <input type="text" name="name" id="name" required /><br>

        <label>Age</label>
        <input type="date" name="age" id="age" required /><br>

        <label>Gender</label>
        <input type="radio" name="gender" value="M" />Male
        <input type="radio" name="gender" value="F" />Female
        <input type="radio" name="gender" value="O" />Other<br>

        <label>Language</label>
        <input type="checkbox" name="language" value="Nepali" />Nepali
        <input type="checkbox" name="language" value="English" />English
        <input type="checkbox" name="language" value="Hindi" />Hindi<br>

        <label>Country</label>
        <select name="country" id="country">
            <option value="0">Select Country</option>
            <?php foreach ($countries as $country) { ?>
                <option value="<?= $country->id; ?>"><?=$country->country; ?></option>
            <?php } ?>
        </select><br>

        <label>Province</label>
        <select name="province" id="province">
            <option value="0">Select Province</option>
        </select><br>

        <label>Municipality</label>
        <select name="municipality" id="municipality">
            <option value="0">Select Municipality</option>
        </select><br>

        <label>Address</label>
        <input type="text" name="address" id="address" /><br>

        <label>Mobile Number</label>
        <input type="text" name="phone" id="phone" /><br>
    </form>
    <button type="button" id="addPatientBtn">Add Patient</button>
</div>
<!--<div class="container" id="billing">-->
<!--    <button type="button" id="cancel">Cancel</button>-->
<!--    <table>-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th>SN</th>-->
<!--            <th>Patient Id</th>-->
<!--            <th>Test Item</th>-->
<!--            <th>Quantity</th>-->
<!--            <th>Unit Price</th>-->
<!--            <th>Price</th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody id="tbody">-->
<!--        <tr>-->
<!--            <td>1</td>-->
<!--            <td>12345678</td>-->
<!--            <td><input type="text" name="test1" placeholder="Test item" id="test1" /></td>-->
<!--            <td><input type="number" name="quantity1" placeholder="Quantity" id="quantity1" /></td>-->
<!--            <td><input type="number" name="unit1" placeholder="Unit price" step=".01" id="unit1" /></td>-->
<!--            <td><p id="price1">Price</p></td>-->
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
<!--    <button type="button" id="addRow">Add</button>-->
<!--    <div>-->
<!--        <table>-->
<!--            <tr>-->
<!--                <td>Sutotal : </td>-->
<!--                <td>12345678</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Discount Percent : </td>-->
<!--                <td><input type="number" name="discountP"/></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Discount Amount : </td>-->
<!--                <td><input type="number" name="discountA"/></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Net Total : </td>-->
<!--                <td>12345678</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--</div>-->
</body>
</html>
