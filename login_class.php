<?php
require_once 'dbconnection.php';
dbconnection::getConnection(); /*creates a connection to the database*/

error_reporting(E_ALL);
session_name('upris');
session_start();

class login_class {
    private static $instance = null;
    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new login_class();
        }
        return self::$instance;
    }   
 
public static function login($email,$password){ /*funtion to login*/
  
$result=mysql_query("SELECT userid,firstname,lastname,email FROM userinfo WHERE email='$email' AND password=MD5('$password')")or die(mysql_error());
$result2=mysql_query("SELECT adminid,firstname,lastname FROM admininfo WHERE email='$email' AND password=MD5('$password')") or die(mysql_error());
$result3=mysql_query("SELECT reviewerid,firstname,lastname FROM reviewerinfo WHERE email='$email' AND password=MD5('$password')") or die(mysql_error()); 
$result4=mysql_query("SELECT adminid,firstname,lastname FROM adviserinfo WHERE email='$email' AND password=MD5('$password')") or die(mysql_error());
if(mysql_num_rows($result) == 1){ /*if a users login information is found in the database, sets the SESSION variables*/
        $row = mysql_fetch_assoc($result);
        $_SESSION['risid'] = $row['userid'];
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['firstname'].' '.$row['lastname'];
        
        return true; /*for TDD test purposes, indicator of sucesful login*/
}
         
else if (mysql_num_rows($result2) == 1){ /*if the user is an admin, sets the SESSION variables*/
     $row = mysql_fetch_assoc($result2);
        $_SESSION['adminid'] = $row['adminid'];
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['firstname'].' '.$row['lastname'];
        return true;
}
else{
          
          return false; /*for TDD test purposes, indicator of unsucesful login*/
}
}

  public static function is_valid_user($u){ /*confirms if the userID or adminID is valid one*/
    
    $userid = mysql_real_escape_string($u);
    $query = "SELECT firstname FROM userinfo WHERE userid=$userid";
    $result = mysql_query($query) or die(mysql_error());

    $query2 = "SELECT firstname FROM admininfo WHERE adminid=$userid";
    $result2 = mysql_query($query2) or die(mysql_error());

    
    if(mysql_num_rows($result) == 1 OR mysql_num_rows($result2) == 1){
        return true;
    }else{
        return false;
    }
}
  public static function is_logged_in(){ /*function to check if someone is logged in, either as a normal user or an admin*/
      if(isset($_SESSION['adminid'])){
          $u=$_SESSION['adminid'];
          
      }
      else if(isset($_SESSION['risid'])) {
             $u=$_SESSION['risid']; 
      }
      else{
          return false;
      }      
    if((isset($_SESSION['risid']) OR isset($_SESSION['adminid'])) && self::is_valid_user($u)){
        return true;
    }else{
        return false;
    }
}    


public static function is_admin(){
    if(isset($_SESSION['adminid']) && self::is_logged_in()){
         return true;
      }
      else{
          return false;
      }
}
    
}



?>
