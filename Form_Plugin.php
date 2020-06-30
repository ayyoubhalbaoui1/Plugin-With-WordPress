<?php
/*
Plugin Name: ayyoub plugin
Description: the first plugin
Version: 1.0.0
Author: ayyoub halbaoui
*/

//connection with wordpress database 
require_once(ABSPATH . 'wp-config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($conn, DB_NAME);



//creation of tables 
function newTable()
{

    global $conn;

    $sql = "CREATE TABLE form(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname varchar(255) NOT NULL, lastname varchar(255) NOT NULL, email varchar(255) NOT NULL, phone int NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($conn, $sql);
    return $res;
}

//creation of the table if the connection is established 
if ($conn == true){

    newTable();
}
 

// Fonction for let or delete the inputs (les champs )
function form($atts){
    $prenom= "";
    $nom= "";
    $mail= "";
    $tel= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'firstname' => 'true',
            'lastname' => 'true',
            'email' => 'true',
            'phone' => 'true',
            'message' => 'true'
            
    ), $atts));

    if($firstname== "true"){
        $prenom = '<label>First name:</label><input type="text" name="firstname" required>';
    }

    if($lastname== "true"){
        $nom = '<label>Last name:</label><input type="text" name="lastname" required>';
    }

    if($email== "true"){
        $mail = '<label>Email:</label><input type="email" name="email" required>';
    }
    if($phone== "true"){
        $tel = '<label>phone:</label><input type="number" name="phone" required>';
    }

    if($message== "true"){
        $msg = '<label>Message:</label><textarea name="msg"></textarea>';
    }



    echo '<form method="POST"  >' .$prenom.$nom.$mail.$tel.$msg. '<input style="margin-top : 20px;" value="Send" type="submit" name="submit"></form>';
}



//Shortcode of the Plugin 
add_shortcode('My_Form', 'form');



// Fontion to send the information to databse 
    function sendToDB($firstname,$lastname,$email,$phone,$msg)
    {
        global $conn;

    $sql = "INSERT INTO form(firstname,lastname,email,phone, msg) VALUES ('$firstname','$lastname','$email','$phone','$msg')";
    $res = mysqli_query($conn , $sql);
    
    return $res;
    }



//Sending informations into the databse 
    if(isset($_POST['submit'])){

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $msg = $_POST['msg'];
        

        sendToDB($firstname,$lastname,$email,$phone,$msg);
    
    }




    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("ayyoub plugin", "ayyoub plugin", 4, "ayyoub plugin", "adminMenu");
    }

function adminMenu()
{
    echo <<< EOD
    <div style="font-size : 20px; display : flex; flex-direction : column;">
    <h1 style="color:blue;">
      Welcome To Ayyoub Form
    </h1>
  
    <h4>
      This is Our Form :
    </h4>
    <ul>
      <li>Firstname</li>
      <li>Lastname</li>
      <li>Email</li>
      <li>Phone</li>
      <li>Message</li>
    </ul>
  
    <h3>
      USE THIS SHORTCODE [My_Form]
    </h3>
  
  
  
  </div>

EOD;
}

?>