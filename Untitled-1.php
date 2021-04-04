



<?php
  require('config.php');
  require('includes/functions.php');
  $conn = @mysqli_connect($dbhost,$dbuser,$dbpass) or sqlerror();
 $db_select = mysqli_select_db($conn, $dbname);
  $zsql = mysqli_query($conn,"SELECT * FROM cmum_settings WHERE id='1'");
  $zline = mysqli_fetch_array($zsql);
  $SiteTitle = $zline['SiteTitle'];
  $msg = null;



  if (isset($_GET['logout']) and $_GET['logout'] == 'true') {
    $msg='You are now logged out.';
  }
  if (isset($_GET['error']) and $_GET['error'] == '1') {
    $msg='Incorrect username or password.';
  }
  if (isset($_GET['error']) and $_GET['error'] == '2') {
    $msg='No username or password given.';
  }
  if (isset($_GET['error']) and $_GET['error'] == '3') {
    $msg='Account disabled.';
  }
if(isset($_POST['loginuser'])){
  $loginuser = $_POST['loginuser'];
  $loginpass = $_POST['loginpass'];
  $loginuser = stripslashes($loginuser);
  $loginpass = stripslashes($loginpass);
  }
  

  

  if (isset($loginuser) && isset($loginpass) && !$_GET['error']) { 
    $conn = @mysqli_connect($dbhost,$dbuser,$dbpass) or sqlerror();
    mysqli_select_db($conn, $dbname);
    $sql = mysqli_query($conn,"SELECT * FROM cmum_settings WHERE id='1'");
    $line = mysqli_fetch_array($sql);
    $logfailedlogins = $line['logfailedlogins'];
    $sql = mysqli_query($conn,"SELECT * FROM cmum_admins WHERE username='".$loginuser."' AND password='".$loginpass."'");
    $rowcheck = mysqli_num_rows($sql);
    $line = mysqli_fetch_array($sql); 
    if ($rowcheck == 1) { 
      if ($line['enabled'] == 'true') {
        $_SESSION['lastlogin'] = $line['lastlogin'];
        $sql = mysqli_query($conn,'update cmum_admins set lastlogin=now() where id='.$line['id']);
        $sqlsrvname = mysqli_query($conn,"SELECT * FROM cmum_settings WHERE id='1'");
        $lineinfo = mysqli_fetch_assoc($sqlsrvname);
        mysqli_close($conn);
        session_start();
        $_SESSION['loginuser'] = $loginuser;
        $_SESSION['lastlogin'] = $line['lastlogin'];
        $_SESSION['adminid'] = $line['id'];
        $_SESSION['adminlevel'] = $line['level'];
        $_SESSION['srvname'] = $lineinfo['servername'];
        $_SESSION['fetchfromcsp'] = $lineinfo['fetchfromcsp'];
        $_SESSION['cspsrv_ip'] = $lineinfo['cspsrv_ip'];
        $_SESSION['cspsrv_port'] = $lineinfo['cspsrv_port'];
        $_SESSION['cspsrv_user'] = $lineinfo['cspsrv_user'];
        $_SESSION['cspsrv_pass'] = $lineinfo['cspsrv_pass'];
        $_SESSION['cspsrv_protocol'] = $lineinfo['cspsrv_protocol'];
        $_SESSION['cspconnchk'] = $lineinfo['cspconnchk'];
        $_SESSION['SiteHost'] = $lineinfo['SiteHost'];
        $_SESSION['SiteEmail'] = $lineinfo['SiteEmail'];
        $_SESSION['SiteTitle'] = $lineinfo['SiteTitle'];
        $_SESSION['XMLKey'] = $lineinfo['XMLKey'];
        header('Location: home.php');
      } else {
        mysqli_close($conn);
        session_start(); 
        session_unset(); 
        session_destroy(); 
        header('Location: login.php?error=3');	
      }
    } else {
      if ($logfailedlogins == '1') {
        $logdate = date('Y-m-d');
        $logtime = date('H:i:s');
        $logip = $_SERVER['REMOTE_ADDR'];
        $loghost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $loguser = $loginuser;
        $logpass = $loginpass;
        mysqli_query($conn,"INSERT INTO cmum_failedlogins (date,time,ip,hostname,username,password) VALUES ('".$logdate."','".$logtime."','".$logip."','".$loghost."','".$loguser."','".$logpass."')");
      }
      mysqli_close($conn);
      session_start(); 
      session_unset(); 
      session_destroy();

      if (isset($_GET['logout']) and $_GET['logout'] == 'true') {
        $msg = 'You are now logged out.';
      }else{
        if(isset($_POST['loginpass']) || isset($_POST['loginuser']))
          header('Location: login.php?error=1');
      }
    }
  }
;?>
 <!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
  <head>
  <!--
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />
  -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="favicon.png">
    <link rel="stylesheet" type="text/css" href="<?=$_SESSION["THEME"]?>">
    <meta name="description" content="Simple Template #2 from simpletemplates.org" />
    <meta name="keywords" content="simple #2, template, simpletemplates.org" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/opa-icons.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css">

    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script> 
    <script type="text/javascript" src="js/cpanel.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <title>Dorbok Server </title>


    <style>



@import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

body {
  font-family: 'Open Sans', sans-serif;
  background: #f9faff;
  color: #3a3c47;
  line-height: 1.6;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0;
  padding: 0;
}

h1 {
  margin-top: 48px;
}

form {
  background: #fff;
  max-width: 360px;
  width: 100%;
  padding: 58px 44px;
  border: 1px solid ##e1e2f0;
  border-radius: 4px;
  box-shadow: 0 0 5px 0 rgba(42, 45, 48, 0.12);
  transition: all 0.3s ease;
}

.row {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.row label {
  font-size: 13px;
  color: #8086a9;
}

.row input {
  flex: 1;
  padding: 13px;
  border: 1px solid #d6d8e6;
  border-radius: 4px;
  font-size: 16px;
  transition: all 0.2s ease-out;
}

.row input:focus {
  outline: none;
  box-shadow: inset 2px 2px 5px 0 rgba(42, 45, 48, 0.12);
}

.row input::placeholder {
  color: #C8CDDF;
}

button {
  width: 100%;
  padding: 12px;
  font-size: 18px;
  background: #15C39A;
  color: #fff;
  border: none;
  border-radius: 100px;
  cursor: pointer;
  font-family: 'Open Sans', sans-serif;
  margin-top: 15px;
  transition: background 0.2s ease-out;
}

button:hover {
  background: #55D3AC;
}

@media(max-width: 458px) {
  
  body {
    margin: 0 18px;
  }
  
  form {
    background: #f9faff;
    border: none;
    box-shadow: none;
    padding: 20px 0;
  }

}









    </style>

  </head>
  <body onLoad="focus();dologin.loginuser.focus()">
     <center>
      <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30">&nbsp;</td>
        </tr>
      </table>
      </table>
    </center>
    <?php
      if (isset($_GET["logout"]) and $_GET["logout"] == "true"){
        print("<br><div class='alert alert-info' align='center'><b><font size='5'> You Are Logged Out Succesfully </font></b></div>");
      }?>

      <meta http-equiv="refresh" content="5;url=login.php">
<?php
      if (isset($_GET["error"]) and $_GET["error"] == "1") {
       echo ("<br><div class='alert alert-info' align='center'><b><font size='5'> Incorrect User Or Password  </font></b></div>");


      }
      if (isset($_GET["error"]) and $_GET["error"] == "2") {
        print("<br><div class='alert alert-info' align='center'><b><font size='5'> No User Or Password Given  </font></b></div>");
      }
      if (isset($_GET["error"]) and $_GET["error"] == "3") {
        print("<br><div class='alert alert-info' align='center'><b><font size='5'> Account Disabled By Administrator  </font></b></div>");
      }


    ?>

    <br><br>

<div id="footer" style="text-align:center;bottom:-10px;position:fixed">

      <script type="text/javascript">
        function logout() {
          var answer = confirm("Are you sure you want to logout?")
          if (answer){
            window.location = "logout.php";
          }
        }
      </script>

      
        <center><font size="3"><b>
        <font color="red">Developed by</font> : Server Dorbok &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; <font color="red">For More Info </font> : 01092696831 <font color="yellow"> :: Active Code</font> </a></b></font>
    </center></div></body>
</html>

<?php  ?>




<h1>Login </h1>
<form name="dologin" action="" method="post">
  <div class="row">
    <label for="email">Email</label>
    <input type="text" name="loginuser">
  </div>
  <div class="row">
    <label for="password">Password</label>
    <input type="password" name="loginpass">
  </div>
  <button type="submit">Login</button>
</form>
</center>
