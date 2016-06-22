 <?php
// Be sure to include the file you've just downloaded
require_once('AfricasTalkingGateway.php');
// Specify your login credentials
$username   = "peterKaranja";
$apikey     = "f9dace446394374253e92a557042d8d15486dd51f9c86fd7a8530b8e416a463e";
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)

// Any gateway error will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block
@$message = $_POST['message'];
include('../includes/conn.php');


$phone_no = "select customer.phone from customer";
$phone_no = mysql_query($phone_no) or die("asset check failed.".mysql_error());
$rowcount=mysql_num_rows($phone_no);

$user= @$_SESSION['username'];


$credit = "select sms_amount from auth where username='$user'";
$credit = mysql_query($credit) or die("asset check failed.".mysql_error());
$credit = mysql_fetch_array($credit);
$credit=$credit[0];

$messagelen=strlen(@$message);

if($messagelen==0)
{
$messagelen=0;
}

if($messagelen>0 && $messagelen<160)
{
$messagelen=1;
}

if($messagelen>=160 && $messagelen<320 )
{
$messagelen=2;
}
if($messagelen>=320 && $messagelen<480 )
{
$messagelen=3;
}
if($messagelen>=480 && $messagelen<640)
{
$messagelen=4;
}
if($messagelen>=640 && $messagelen<800)
{
$messagelen=5;
}
else if($messagelen>=800)

{
$messagelen=-5;
}


$no_of_sms=($messagelen*$rowcount);


$balance=($credit-$no_of_sms*5);


//KINDLY EDIT THIS AFTER SENDING ALL THE SMSs
$b = "update auth set sms_amount='$balance'  where username='$user'";
$b = mysql_query($b) or die("asset check failed.".mysql_error());

if($messagelen==0) 

{
$rowcount=0;
echo "*** Please type the  message</font>";
}
if( $balance<=0 )
{
$rowcount=0;
echo " *** No enough credit to send  ".$no_of_sms." messages";
}
else{
$rowcount=$rowcount;

}


for($i=0;$i<$rowcount;$i++)
{
	
	$row_phone_no = mysql_fetch_array($phone_no);
	$recipients = $row_phone_no[0];
	$message=@$message;
	// will print Test
	// Create a new instance of our awesome gateway class
	$from = "20880";
	$gateway    = new AfricasTalkingGateway($username, $apikey);
	try 
	{   
		$results = $gateway->sendMessage($recipients, $message, $from);            
		foreach($results as $result) {
		    	echo " Message Sending successful " .$row_phone_no[0];    
		}
	}
	catch ( AfricasTalkingGatewayException $e )
	{
	  echo "";
	}
}
?>                