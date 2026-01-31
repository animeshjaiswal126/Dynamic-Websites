<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="container">
<h1 class="text-primary text-uppercase text-center">AJAX CRUD OPERATION</h1>

<div class="d-flex justify-content-end">
    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Open modal</button>
</div>

<h2 class="text-danger">All Records</h2>
<div id="records_content"></div>

<!-- Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">AJAX CRUD OPERATION</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- ✅ FORM START -->
            <form id="addForm">
                <input type="hidden" id="hidden_user_id">

            <div class="modal-body">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control">
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="address" name="address" class="form-control">
                </div>

                <div class="form-group">
                    <label>Dob</label>
                    <input type="date" id="dob" name="dob" class="form-control">
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <select id="country" name="country" class="form-control">
                        <option value="">Select Country</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>State</label>
                    <select id="state" name="state" class="form-control">
                        <option value="">Select State</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>City</label>
                    <select id="city" name="city" class="form-control">
                        <option value="">Select City</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <!-- ❗ type="button" very important -->
                <button type="button" class="btn btn-success" onclick="addRecord()">Add +</button>
                <button type="button" class="btn btn-warning" onclick="UpdateUser()">Update</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="readRecords()">Close</button>
            </div>
            </form>
            <!-- ✅ FORM END -->

        </div>
    </div>
</div>

</div>

<script>

//Read records
function readRecords(){
    var readrecord = "readrecord";
    $.ajax({
        url: "backend.php",
        type: "POST",
        data: { readrecord: readrecord },

        success:function(data, status){
            $('#records_content').html(data);
        },
    });
}


// Add records 
function addRecord(){
    var first_name = $('#first_name').val();
    var last_name = $('#last_name').val();  
    var email = $('#email').val();
    var phone = $('#phone').val();
    var address = $('#address').val();
    var dob = $('#dob').val();  
    var country = $('#country').val();
    var state = $('#state').val();
    var city = $('#city').val();

    
    $.ajax({
        url: "backend.php",
        type: "POST",
        data: {
            first_name: first_name,
            last_name: last_name,
            email: email,
            phone: phone,
            address: address,
            dob: dob,
            country: country,
            state: state,
            city: city


        },
        success:function(data, status){
            alert(data);
            $('#myModal').modal('hide');  //modal hide karne ke liye
            readRecords();
        },
    });
}

        // ---------- LOAD COUNTRY ----------
    $(document).ready(function(){
        $.ajax({
            url: "backend.php",
            type: "POST",
            data: { load_country: 1 },
            success:function(data){
                $("#country").html(data);
            }
        });
    });

    // ---------- COUNTRY → STATE ----------
    $("#country").change(function(){
        var country_id = $(this).val();
        $.ajax({
            url:"backend.php",
            type:"POST",
            data:{ country_id: country_id },
            success:function(data){
                $("#state").html(data);
                $("#city").html('<option value="">Select City</option>');
            }
        });
    });

    // ---------- STATE → CITY ----------
    $("#state").change(function(){
        var state_id = $(this).val();
        $.ajax({
            url:"backend.php",
            type:"POST",
            data:{ state_id: state_id },
            success:function(data){ 
                $("#city").html(data);
            }
        });
    });


// Delete record
function DeleteUser(id){
    if(confirm("Are you sure you want to delete?")){
        $.ajax({
            url:"backend.php",
            type:"POST",
            data:{deleteid:id},
            success:function(data){
                alert(data);
                readRecords();
            }
        });
    }
}

  function GetUserDetails(id){
    $.ajax({
        url: "backend.php",
        type: "POST",
        data: { get_user_id: id },
        dataType: "json",
        success:function(data){

            $('#hidden_user_id').val(data.id);
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#address').val(data.address);
            $('#dob').val(data.dob);

            $('#country').val(data.country).change();

            setTimeout(function(){
                $('#state').val(data.state).change();
                setTimeout(function(){
                    $('#city').val(data.city);
                },300);
            },300);

            $('#myModal').modal('show');
        }
    });
}
  
  function UpdateUser(){
    var id = $('#hidden_user_id').val();

    $.ajax({
        url: "backend.php",
        type: "POST",
        data:{
            update_id: id,
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            dob: $('#dob').val(),
            country: $('#country').val(),
            state: $('#state').val(),
            city: $('#city').val()
        },
        success:function(data){
            alert(data);
            $('#myModal').modal('hide');
            readRecords();
        }
    });
}

</script>

</body>
</html>
