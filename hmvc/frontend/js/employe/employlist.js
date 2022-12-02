$(function(){
    $.get(window.location.pathname, function(data, status){
        val = JSON.parse(data);
        let table = '<table><tr>';
        table += '<th>EMPLOYEE NAME</th>';
        table += '<th>EMPLOYEE ID</th>';
        table += '<th>EMPLOYEE EMAIL</th>';
        table += '<th></th>';
        table += '<th></th></tr>';
        $.each(val, (key, employee) => {
            table += `<tr><td>${employee.employename}</td>`;
            table += `<td>${employee.employeid}</td>`;
            table += `<td>${employee.employemail}</td>`;
            table += `<td><a href="./showview?id=${employee.employeid}"><input type="button" name="UPDATE" value="Update" id="updateBtn" /></a></td>`;
            table += `<td><a href="./deleteview?id=${employee.employeid}"><input type="button" name="DELETE" value="Delete" id="deleteBtn" /></a></td></tr>`;
        });
        table += '</table>';
        $('.form-group').append(table);
    });

})