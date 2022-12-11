<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/billing.js"></script>
    <link rel="stylesheet" href="<?=base_url(); ?>frontend/css/quiz/billing.css">
    <title>Document</title>
</head>
<body>
<div>
    <label>Patient Id</label>
    <input type="number" name="test1" id="patientId" value="<?=$id?>" />
</div>
<div class="container" id="billing">
    <table>
        <thead>
        <tr>
            <th>SN</th>
            <th>Test Item</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody id="tbody">
        <tr id="1">
            <td>1</td>
            <td><input type="text" name="test1" placeholder="Test item" id="test1" required /></td>
            <td><input type="number" name="unit1" placeholder="Unit price" step=".01" id="unit1" required /></td>
            <td><input type="number" name="quantity1" placeholder="Quantity" id="quantity1" required/></td>
            <td><p id="price1">0</p></td>
        </tr>
        </tbody>
    </table>
    <button type="button" id="addRow">Add</button>
    <div>
        <table>
            <tr>
                <td>Subtotal : </td>
                <td><p id="subtotal"></p></td>
            </tr>
            <tr>
                <td>Discount Percent : </td>
                <td><input type="number" name="discountP" id="discountP"/></td>
            </tr>
            <tr>
                <td>Discount Amount : </td>
                <td><input type="number" name="discountA" id="discountA"/></td>
            </tr>
            <tr>
                <td>Net Total : </td>
                <td><p id="nettotal">0</p></td>
            </tr>
        </table>
    </div>
    <button type="button" id="save">Save</button>
</div>
</body>
</html>