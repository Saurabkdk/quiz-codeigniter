<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>frontend/js/quiz/divs.js"></script>
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Address</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td><input type="text" name="name1" id="name1" /></td>
            <td><input type="text" name="address1" id="address1" /></td>
        </tr>
        </tbody>
    </table>
    <button type="button" id="button">Add Row</button>
    <button type="button" id="view">View Data</button>
</body>
</html>
