
<?php
/* --Database Connection --*/

$host = "localhost";
$user = "root";
$password = "";
$db = "crudoperation";

$conn = mysqli_connect($host, $user, $password, $db);

// Agar connection fail ho jaye
if (!$conn) {
    die("Database Connection Failed");
}



// Recorded data fetch

if(isset($_POST['readrecord'])) {
    $data = '<table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>address</th>
            <th>dob</th>
            <th>Country</th>
            <th>State</th>
            <th>City</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>';

    $query = "SELECT s.*,c.name country_name,st.name state_name,ci.name city_name
    FROM students s
    LEFT JOIN countries c ON s.country=c.id
    LEFT JOIN states st ON s.state=st.id
    LEFT JOIN cities ci ON s.city=ci.id";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
               
    $number = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $data .= '<tr>
            <td>' . $number. '</td>
            <td>'.$row['name'].'</td>
            <td>'.$row['email'].'</td>
            <td>'.$row['phone'].'</td>
            <td>'.$row['address'].'</td>
            <td>'.$row['dob'].'</td>
            <td>'.$row['country_name'].'</td>
            <td>'.$row['state_name'].'</td>
            <td>'.$row['city_name'].'</td>

            <td>
                <button onclick="GetUserDetails('.$row['id'].')" class="btn btn-warning btn-sm"><i class="fa-solid fa-eye"></i></button>
            </td>
            <td>
                <button onclick="DeleteUser('.$row['id'].')" class="btn btn-danger btn-sm"><i class="fa-solid fa-xmark"></i></button>
            </td>
        </tr>';
         $number++;
    }
}  

    $data .= '</table>';
    echo $data;
    exit;
}

if(isset($_POST['get_user_id'])){
    $id = $_POST['get_user_id'];

    $q = mysqli_query($conn,"SELECT * FROM students WHERE id='$id'");
    $row = mysqli_fetch_assoc($q);

    $name = explode(" ", $row['name']);

    echo json_encode([
        "id" => $row['id'],
        "first_name" => $name[0],
        "last_name" => $name[1] ?? '',
        "email" => $row['email'],
        "phone" => $row['phone'],
        "address" => $row['address'],
        "dob" => $row['dob'],
        "country" => $row['country'],
        "state" => $row['state'],
        "city" => $row['city']
    ]);
    exit;
}

/*-------INSERT DATA----------*/

if (
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['email']) &&
    isset($_POST['phone']) &&
    isset($_POST['address']) &&
    isset($_POST['dob']) &&
    isset($_POST['country']) &&
    isset($_POST['state']) &&
    isset($_POST['city']) && 
    ! isset($_POST['update_id'])  

) {

    // Form se data lena
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone']; 
    $address    = $_POST['address'];
    $dob        = $_POST['dob'];
    $country    = $_POST['country'];
    $state      = $_POST['state'];
    $city       = $_POST['city'];



    // Email already exist check

    if($first_name=='' || $last_name=='' || $email=='' || $phone=='' || $address=='' ||  $dob==''||  $country=='' ||  $state==''||  $city==''){
        echo "Please all fields required 🙏🙏";
        exit;
    }
       

    $check_email = "SELECT id FROM students WHERE email='$email'";
    $email_result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($email_result) > 0) {
        echo "Email already exists";
        exit;
    }

    //Phone already exist check
    

    $check_phone = "SELECT id FROM students WHERE phone='$phone'";
    $phone_result = mysqli_query($conn, $check_phone);

    if (mysqli_num_rows($phone_result) > 0) {
        echo "Mobile number already exists";
        exit; 
    }


    /*-------INSERT STUDENT DATA----------*/

    $name = $first_name . " " . $last_name;

    $insert_query = "INSERT INTO students (name, email, phone, address,dob,country, state, city)
        VALUES ('$name', '$email', '$phone', '$address','$dob','$country','$state','$city')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Record Added Successfully";
    } else {
        echo "Insert Error";
    }
}


 /*-------UPDATE DATA----------*/
if(isset($_POST['update_id'])){
    $id      = $_POST['update_id'];
    $name    = $_POST['first_name']." ".$_POST['last_name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];
    $dob     = $_POST['dob'];
    $country = $_POST['country'];
    $state   = $_POST['state'];
    $city    = $_POST['city'];

    $update = "UPDATE students SET
        name='$name',
        email='$email',
        phone='$phone',
        address='$address',
        dob='$dob',
        country='$country',
        state='$state',
        city='$city'
        WHERE id='$id'";

    if(mysqli_query($conn,$update)){
        echo "Record Updated Successfully";
    }else{
        echo "Update Failed";
    }
    exit;
}


    /* ===== DELETE RECORD ===== */
    if(isset($_POST['deleteid'])){
        $id = $_POST['deleteid'];
        $delete = "DELETE FROM students WHERE id='$id'";
        if(mysqli_query($conn,$delete)){
            echo "Record Deleted Successfully";
        } else {
            echo "Delete Failed";
        }
        exit;
}

 /* =====Load countries ===== */
    if(isset($_POST['load_country'])){
        $q = mysqli_query($conn,"SELECT * FROM countries");
        echo '<option value="">Select Country</option>';
        while($row=mysqli_fetch_assoc($q)){
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        exit;
}

    if(isset($_POST['country_id'])){
    $cid = $_POST['country_id'];
    $q = mysqli_query($conn,"SELECT * FROM states WHERE country_id='$cid'");
    echo '<option value="">Select State</option>';
    while($row=mysqli_fetch_assoc($q)){
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    exit;
}
   
   if(isset($_POST['state_id'])){
    $sid = $_POST['state_id'];
    $q = mysqli_query($conn,"SELECT * FROM cities WHERE state_id='$sid'");
    echo '<option value="">Select City</option>';
    while($row=mysqli_fetch_assoc($q)){
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    exit;
}

 ?>
