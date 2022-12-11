<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/patient/billing.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/patient/billing.css">
    <title>Document</title>
</head>
<body>
<?php if ((isset($_GET['id']) || isset($_GET['billId'])) && !isset($_GET['bill'])) { ?>
    <div class="container" id="billing">
        <div class="head">
            <h1>Bill Registration</h1>
            <h1>Midas Hospital</h1>
            <div class="search">
                <input type="button" class="button-3 list" role="button" value="Patient List" />
                <input type="button" id="bill" class="button-3" role="button" value="Patient Bills" />
            </div>
        </div>
        <div><?= $this->session->flashdata('noId'); ?></div>
    <div style="display: flex">
        <div>
            <label>Patient Id</label>
            <div class="patient">
                <input type="number" name="test1" class="<?=$work?> form-style" id="patientId" value="<?=$id?>" readonly />
                <input type="button" value="Change" id="change" class="button-3" role="button" />
            </div>
        </div>
        <?php if (isset($tests)) { ?>
        <div style="margin-left: -60px">
            <label>Bill Id</label>
            <div class="patient">
                <input type="text" name="id" id="billId" value="<?= $tests[0]->id; ?>" class="form-style" readonly /><br>
            </div>
        </div>
            <?php } ?>
    </div>
    <table class="test">
        <thead>
        <tr>
            <th>
                # (
                <?php if (isset($tests)) { ?><span id="rows" style="margin: 0"><?=count($tests) ?></span><?php } ?>
                )
            </th>
            <th>Test Item</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Price</th>
            <?php if (isset($tests)) { ?>
                <th>Test Id</th>
            <?php } else { ?>
            <th></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="tbody">
        <?php if (isset($tests)) { ?>
                <?php foreach ($tests as $key => $test) { ?>
        <tr id=<?=$key + 1 ?>>
            <td><?=$key + 1 ?></td>
            <td><input type="text" name="test<?=$key + 1 ?>" placeholder="Test item" id="test<?=$key + 1 ?>" class="form-style" value="<?=$test->test_item ?>" required /></td>
            <td><input type="number" name="unit<?=$key + 1 ?>" placeholder="Unit price" step=".01" id="unit<?=$key + 1 ?>" class="form-style" value="<?=$test->unit ?>" required /></td>
            <td><input type="number" name="quantity<?=$key + 1 ?>" placeholder="Quantity" id="quantity<?=$key + 1 ?>" class="form-style" value="<?=$test->quantity ?>" required/></td>
            <td><p id="price<?=$key + 1 ?>"><?=$test->price ?></p></td>
            <td id="testId<?=$key + 1 ?>"><?=$test->id ?></td>
        </tr>
        <?php }} else { ?>
            <tr id="1">
                <td>1</td>
                <td><input type="text" name="test1" placeholder="Test item" id="test1" class="form-style" required /></td>
                <td><input type="number" name="unit1" placeholder="Unit price" step=".01" id="unit1" class="form-style" required /></td>
                <td><input type="number" name="quantity1" placeholder="Quantity" id="quantity1" class="form-style" required/></td>
                <td><p id="price1">0</p></td>
                <td><input type="button" id="clear1" value="Clear" class="button-3" role="button"></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <input type="button" id="addRow" value="Add" class="button-3" role="button" />
    <div>
        <table class="total">
            <tr>
                <td>Subtotal : </td>
                <td><p id="subtotal"></p></td>
            </tr>
            <tr>
                <td>Discount Percent : </td>
                <td><input type="number" name="discountP" id="discountP" class="form-style" value="0"/></td>
            </tr>
            <tr>
                <td>Discount Amount : </td>
                <td><input type="number" name="discountA" id="discountA" class="form-style" value="0"/></td>
            </tr>
            <tr>
                <td>Net Total : </td>
                <td><p id="nettotal">0</p></td>
            </tr>
        </table>
    </div>
    <input type="button" id="save" value="Save" class="button-3" role="button" />
        <?php if (isset($_GET['id'])) { ?>
        <a href="<?=base_url() ?>patient/billing?id=<?= $_GET['id'] ?>&bill"><input type="button" id="patientBill" value="Bills" class="button-3" role="button"></a>
        <?php } ?>
</div>
<?php } ?>

<?php if (isset($bills) && (!isset($_GET['id']) || isset($_GET['bill'])) && !isset($_GET['billId'])) { ?>
<div class="container" id="billList">
    <div class="head">
        <h1>Patient Bills</h1>
        <h1>Midas Hospital</h1>
        <div class="search">
            <input type="button" class="button-3 list" role="button" value="Patient List" />
<!--            <button type="button" id="bill" class="button-3" role="button">Patient Bills</button>-->
            <input type="text" name="search" id="searchInp" class="form-style" />
            <input type="button" id="search" class="button-3" role="button" value="Search" />
        </div>
    </div>
    <table class="testT">
        <thead>
        <tr>
            <th>Patient Id</th>
            <th>Sample No.</th>
            <th>Billing Date</th>
            <th>Subtotal</th>
            <th>Discount Percent</th>
            <th>Discount Amount</th>
            <th>Net Total</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($bills as $key => $bill) { ?>
        <tr id="<?=$bill->id ?>">
            <td><?=$bill->patient_id ?></td>
            <td><?=$bill->id ?></td>
            <td><?=$bill->billing_date ?></td>
            <td><?=$bill->subtotal ?></td>
            <td><?=$bill->discount_percent ?></td>
            <td><?=$bill->discount_amount ?></td>
            <td><?=$bill->nettotal ?></td>
            <td>
                <button type="button" id="view<?=$key + 1 ?>" class="button-3" role="button">View</button>
                <a href="<?= base_url(); ?>patient/billing?billId=<?=$bill->id ?>"><button type="button" id="editBill" class="button-3 edit" role="button">Edit</button></a>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>

<div class="container" id="tests">
    <div class="head">
        <h1>Tests</h1>
        <h1>Midas Hospital</h1>
        <div class="search">
            <input type="button" class="button-3 list" role="button" value="Patient List" />
            <input type="button" id="bill" class="button-3" role="button" value="Patient Bills" />
        </div>
    </div>
    <table class="testT">
    <thead>
    <tr>
        <th>Sample no.</th>
        <th>Patient Id</th>
        <th>Test Item</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody id="testTbody">

    </tbody>
    </table>
</div>
</body>
</html>