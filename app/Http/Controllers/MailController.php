<?php
namespace App\Http\Controllers;
require_once('./vendor/autoload.php');
use Mail;
use Illuminate\Http\Request;
use App\Mail\SendMailable;
use Postmark\PostmarkClient;
use Config;

class MailController extends Controller
{
    public static function sendForgotPwdMailOLD($link, $email){
    	if(!empty($link) && !empty($email)){
			
			$headers = 'From: Mylas <info@mylastech.com>' . PHP_EOL;
			$headers .= 'Reply-To:Mylas<info@mylastech.com>' . PHP_EOL;
			$headers = 'MIME-Version: 1.0' . PHP_EOL;
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;
			$email = base64_decode($email);
    		/*$data = array('body'=>$link);
    		Mail::send('emails.email', array('body'=>$link), function($message){
	        	$message->to('arpita@mylastech.com', 'Test purpose');
	        	$message->subject('Forgot Password Link');
        	});*/
			mail($email, 'Forgot Password', 'Please click the below link to set your new password '.$link, $headers);
			
            //Create a new PHPMailer instance
           /* $mail = new PHPMailer();

            //Tell PHPMailer to use SMTP
            // $mail->isSMTP(true);

            //Set the hostname of the mail server
            $mail->Host = 'smtp-mail.outlook.com';
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6

            //Set the SMTP port number - 587 for authenticated TLS
            $mail->Port = 587;

            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'tls';

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = "arpita@mylastech.com";

            //Password to use for SMTP authentication
            $mail->Password = "#Saibaba#";

            $mail->Body = 'Hello, this is my message.';

            //Set who the message is to be sent from
            $mail->setFrom('info@mylastech.com', 'Mylas');

            //Set an alternative reply-to address
            $mail->addReplyTo('info@mylastech.com', 'Mylas');

            //Set who the message is to be sent to
            $mail->addAddress($email, $email);

            if(!$mail->Send()) {
				echo 'Message was not sent.';
				echo 'Mailer error: ' . $mail->ErrorInfo;
            } else {
            	echo 'Message has been sent.';
            }*/
    	}    	
    }

    public static function sendForgotPwdMail($link, $email){
        if(!empty($link) && !empty($email)){
            $email = base64_decode($email);
            $data = array('body'=>$link);
            $client = new PostmarkClient(Config::get('constants.PostmarkKey'));
            // Send an email:
            $sendResult = $client->sendEmail(
              "info@mylastech.com",
              $email,
              "Forgot Password",
              "Please click the below link to set your new password ".$link
            );
        }
    }
}
