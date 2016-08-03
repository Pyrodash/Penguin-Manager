<?php
/* 
	Profile Search 
	   2016 By Shawn

	   ========================[Notices]============================
		Uses the Users API and decodes the Data to regular text
		This is probably one of the best methods to get/receive 
		users Data is from a Json String.	  

*/
function errorMessages($errorFix){

$errors = array(
	"101" => "Error -  Field Empty",
	"102" => "Error - ID Doesn't Exist",
	"103" => "Error - Invalid Permission"
	);

	if(in_array($errors[$errorFix], $errors)){

		echo "<font color='red'>".$errors[$errorFix]."</font>";
		die();
	} 
}

 
if(isset($_POST['id'], $_POST['key'])) {
	if(empty($_POST['id']) && empty($_POST['key'])){

		errorMessages(101);
	} 
	
	$json = file_get_contents('http://localhost/play/api/users/'.$_POST['id'].'&authorized_key='.$_POST['key'].'');

	$data = json_decode($json, true);


if(array_key_exists('player_id', $data)) {
    $phrase = "
	PlayerID  ". $data['player_id'] ."
	<br />
	Username ". $data['username'] ." 
	<br />
	Email ". $data['email'] ." 
	<br />
	isModerator ". $data['isModerator'] ." 
	<br />
	Coins ". $data['coins'] ." 
	<br />
	<p>This Content is received from <a href='http://localhost/play/api/users/".$_POST['id']."&authorized_key=".$_POST['key']."'>Here</a>
	";
}else{

   errorMessages(102);
}

print_r($phrase);
exit;
}
?>
<style type="text/css">
.form-style-1 {
    margin:10px auto;
    max-width: 400px;
    padding: 20px 12px 10px 20px;
    font: 13px "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
.form-style-1 li {
    padding: 0;
    display: block;
    list-style: none;
    margin: 10px 0 0 0;
}
.form-style-1 label{
    margin:0 0 3px 0;
    padding:0px;
    display:block;
    font-weight: bold;
}
.form-style-1 input[type=text], 
.form-style-1 input[type=password], 
.form-style-1 input[type=date],
.form-style-1 input[type=datetime],
.form-style-1 input[type=number],
.form-style-1 input[type=search],
.form-style-1 input[type=time],
.form-style-1 input[type=url],
.form-style-1 input[type=email],
textarea, 
select{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    border:1px solid #BEBEBE;
    padding: 7px;
    margin:0px;
    -webkit-transition: all 0.30s ease-in-out;
    -moz-transition: all 0.30s ease-in-out;
    -ms-transition: all 0.30s ease-in-out;
    -o-transition: all 0.30s ease-in-out;
    outline: none;  
}
.form-style-1 input[type=text]:focus, 
.form-style-1 input[type=password]:focus, 
.form-style-1 input[type=date]:focus,
.form-style-1 input[type=datetime]:focus,
.form-style-1 input[type=number]:focus,
.form-style-1 input[type=search]:focus,
.form-style-1 input[type=time]:focus,
.form-style-1 input[type=url]:focus,
.form-style-1 input[type=email]:focus,
.form-style-1 textarea:focus, 
.form-style-1 select:focus{
    -moz-box-shadow: 0 0 8px #88D5E9;
    -webkit-box-shadow: 0 0 8px #88D5E9;
    box-shadow: 0 0 8px #88D5E9;
    border: 1px solid #88D5E9;
}
.form-style-1 .field-divided{
    width: 49%;
}

.form-style-1 .field-long{
    width: 100%;
}
.form-style-1 .field-select{
    width: 100%;
}
.form-style-1 .field-textarea{
    height: 100px;
}
.form-style-1 input[type=submit], .form-style-1 input[type=button]{
    background: #4B99AD;
    padding: 8px 15px 8px 15px;
    border: none;
    color: #fff;
}
.form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
    background: #4691A4;
    box-shadow:none;
    -moz-box-shadow:none;
    -webkit-box-shadow:none;
}
.form-style-1 .required{
    color:red;
}
</style>
<center>
<p>The below form uses our api to gather the information needed</p>
<ul class="form-style-1">

<form action="" method="POST">
<input type="text" name="id" placeholder="User PlayerId" />
<br /><br />
<input type="password" name="key" placeholder="Your Auth Key" />
<br /><br />
<input type="submit" name="submit" value="Search" />
</form>