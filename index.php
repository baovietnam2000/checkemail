<?php
$cons = mysqli_connect("127.0.0.1", "root", "", "huybaodeveloper");

if (!$cons) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}else{
	echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL.'<br/>';
	echo "Host information: " . mysqli_get_host_info($cons) . PHP_EOL.'<br/>';	
}
?>
<?php
	//require_once('crud.php');
	require_once 'VerifyEmail.class.php';
?>
<?php
	$sql="select EmailAdress from email_to_list where active='0'";//active=0, new email need to check
	if ($result = $cons->query($sql)) {
	    /* fetch associative array */
	    while ($row = $result->fetch_assoc()) {
	        $tempEmail = $row["EmailAdress"];
    		echo 'email need to check : '.$tempEmail.'<br/>';
    		// Include library file
			//require_once 'VerifyEmail.class.php';

			// Initialize library class
			$mail = new VerifyEmail();

			// Set the timeout value on stream
			$mail->setStreamTimeoutWait(20);

			// Set debug output mode
			$mail->Debug= FALSE; 
			//$mail->Debug= TRUE; 
			$mail->Debugoutput= 'html'; 

			// Set email address for SMTP request
			$mail->setEmailFrom('baoazaria2020@gmail.com');

			// Email to check
			$email = $tempEmail ; //'baoazaria2026@gmail.com'

			// Check if email is valid and exist
			if($mail->check($email)){ 
			    echo 'Email &lt;'.$email.'&gt; is exist!'.'<br/>'; 
			    $sql_update="update email_to_list set active='1' where EmailAdress='$tempEmail'";	
			    $rs_sql = mysqli_query($cons,$sql_update) or die(mysqli_error($cons));
			    echo 'updated : '.$tempEmail;	    

			}elseif(verifyEmail::validate($email)){ 
			    echo 'Email &lt;'.$email.'&gt; is valid, but not exist!'.'<br/>';	    
			    $sql_update="update email_to_list set active='2' where EmailAdress='$tempEmail'";	
			    $rs_sql = mysqli_query($cons,$sql_update) or die(mysqli_error($cons));
			    echo 'deactived : '.$tempEmail;				    
			}else{ 
			    echo 'Email &lt;'.$email.'&gt; is not valid and not exist!'.'<br/>'; 
			    $sql_update="update email_to_list set active='2' where EmailAdress='$tempEmail'";	
			    $rs_sql = mysqli_query($cons,$sql_update) or die(mysqli_error($cons));
			    echo 'deactived : '.$tempEmail;				    
			} 
	    }
	    /* free result set */
	    $result->free();
	}	
?>
<?php
	mysqli_close($cons);
?>