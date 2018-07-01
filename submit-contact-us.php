<?Php
$sessID = session_id; 
if($sessID){
$mytoken = $_SESSION["user_key"];
$myfullname = $_SESSION["fullname"];
}else{
$mytoken="" ;
$myfullname ="";    
}


ini_set('display_errors', 0);
require("myplugins/PHPMailer_V5.1/class.phpmailer.php");
require("myplugins/PHPMailer_V5.1/class.pop3.php");
require("myplugins/PHPMailer_V5.1/class.smtp.php");
include("myset.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Submit Pesanan</title>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">

<!--font-awesome-4.7.0-->
<link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">

</head>

<body>	
<?Php
set_time_limit(100);	
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
	return $isValid;
}



$message ="";

$nama = $_POST['First Name'];
$pesanan = $_POST['Message'];
$email = $_POST['email'];

// Test input values for errors
$errors = array();

if(!validEmail($email)) {
		$errors[] = "Alamat Email Tidak Valid";
	
}

if($errors) {
	// Output errors and die with a failure message
	$errortext = "";
	foreach($errors as $error) {
		$errortext .= "<li style='color:red;'>" . $error . "</li>" ;
	}
	$res = array("Success" => false, "Message" => $errortext);
	
	
	
	echo  "<div style='padding:0 50px'>
			<h5>Masih Ada Kekurangan</h5>
		    <p>
		        <ol type='number'>
		           $errortext
		        </ol>
		    </p>
			<a href='javascript:history.go(-1)' style='color:blue;'><-Kembali...</a>
		  </div>";
	die();
}

	
	
date_default_timezone_set("Asia/Jakarta");
$process_date = date("Y-m-d H:i:s");
$process_by = (isset($mytoken)?$mytoken:"") ;
	
				
		// Send the email
		$isiemail = '<html>
					<body>
					
						<table>
							<tr >
								<div style="display:block;"><img src="http://cirbon-property.andalusia-website.com/images/cirbon-property.png"/>
								</div><br/>
								<div>
									<table>
										<tr><td style="font-weight:bold;text-indent:10px;color:black;">Cirbon Property</td></tr>
										<tr><td style="font-weight:bold;text-indent:10px;color:black;">Head Office :</td></tr>
										<tr><td style="font-weight:normal;text-indent:10px;color:black;"><i class="fa fa-map-marker"></i> Cirebon</td></tr>
										<tr><td style="font-weight:normal;text-indent:10px;color:black;"><i class="fa fa-phone"></i> Phone: +62 231-1234 56 / Fax : +62 231-1234 56</td></tr>
										<tr><td  style="font-weight:normal;text-indent:10px;color:black;"><i class="fa fa-envelope"></i> Email: <a href="admin@andalusia-website.com">admin@andalusia-website.com</a></td></tr>
									</table>
								</div>	
								</td>
							</tr>
						</table>
					
						<br/><br/>

						Dear Bpk/Ibu/Sdr '. $nama . ', <br/><br/> <p style="text-align: justify;text-justify: inter-word;">Terimakasih Telah Mengunjungi Website Kami </p>';
						$isiemail .= '<table>
						<tr>
						<td style = "background-color:#f9f9f9;color:black;">
							NAMA LENGKAP
						</td>
						<td>
						:
						</td>
						<td style = "background-color:#f9f9f9;color:black;">		
							' . $nama . '
						</td>
						</tr>
						<tr>
							<td style = "background-color:#f9f9f9;color:black;">
								EMAIL
							</td>
							<td>
							:
							</td>
							<td style = "background-color:#f9f9f9;color:black;">		
								' . $email . '
							</td>
						</tr>
						<tr>
							<td style="color:black;">
								PESANAN
							</td>
							<td>
							:
							</td>
							<td style="color:black;">
								' . $pesanan . '
							</td>
						</tr>
						
						</table><br/> Untuk Lebih Lanjut Tunggu Konfirmasi Dari Kami<br/> Salam Hangat Dari Kami <strong>Cirbon Property</strong><br/>';
						$isiemail .= 'NB : <br/>Jika Dalam Waktu 2x24 Jam Kami Tidak Menghubungi Anda Silahkan Hubungi Kami <i class="fa fa-phone"></i> Phone: +62 231-1234 56 <br/><br/><br/><br/>';
		
		$isiemail .= '</body></html>';
		
		$admin = "admin@andalusia-website.com";
		$mail = new PHPMailer();
		$mail->IsSMTP(true);  // Telling the class to use SMTP  

		$mail->SMTPAuth = true;
		
		$mail->SMTPSecure = "ssl";
		$mail->Host = "mail.andalusia-website.com";
		$mail->CharSet = "UTF-8";
		$mail->IsHTML();
		$mail->Port = 465; // "The port"
		$mail->SMTPAuth = true;
		$mail->Username = $admin;
		$mail->Password = $anything; 
		$mail->IsHTML(true);
		//$mail->CharSet = "UTF-8";
	
		$mail->SetFrom($admin,"Elite MX") ;
		$mail->AddAddress($email);
		$mail->AddAddress($admin);
		$mail->AddReplyTo($email);
		$mail->Subject  = "Receipt Message Dari Web http://cirbon-property.andalusia-website.com From : " . $nama;
		$mail->Body     = $isiemail; // "The message."
	    $mail->Encoding = "base64";
		if($mail->send()){
			$res = array("Success" => true, "Message" => "<span style='color:green;'> <h3>Terimakasih</h3><br/> Pesan Anda Success Terkirim, Silahkan Baca Email Masuk Dari Kami</span>");
			$messages = "<span style='color:green;'> <h3>Terimakasih</h3><br/> Pesan Anda Success Terkirim, Silahkan Baca Email Masuk Dari Kami</span>";
		}else{
			$res = array("Success" => false, "Message" => "<span style='color:red;'>Pesan Anda Gagal Terkirim, Error: Terjadi Kesalahan!</span>");
			$messages = "<span style='color:red;'>Pesan Anda Gagal Terkirim, Error: Terjadi Kesalahan!</span><a href='javascript:history.go(-1)' style='color:red;'> <img src='http://www.elitemx.id/assets/img/button-go.jpg' width='100px' height='40px'/> <--- Kembali</a>";
		}
		
		echo  "<div style='padding:0 50px'>
			 <p>".$messages."</p>
		   </div>";
		?>
	</body>
</html>