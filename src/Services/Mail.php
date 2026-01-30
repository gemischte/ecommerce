<?php

namespace App\Services;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

//Load Composer's autoloader
// require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../', '.env');
$dotenv->load();

class Mail
{

    private $mail;
    public function __construct()
    {
        //Create an instance; passing `true` enables exceptions
        $this->mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = $_ENV['SMTP_ACCOUNT'];                     //SMTP username
            $this->mail->Password   =  $_ENV['SMTP_PWD'];                               //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $this->mail->setFrom($_ENV['SMTP_RECIPIENTS'], $_ENV['SMTP_NAME']);
            $this->mail->addBCC($_ENV['SMTP_RECIPIENTS']);

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML

        } catch (Exception $e) {
            write_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}", 'Error');
        }
    }

    private function sendEmail($to, $subject, $body, $attachment = null, $filename = null)
    {
        try
        {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();            
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            if ($attachment!==null){
                $this->mail->addStringAttachment ($attachment, $filename);
            }
            return $this->mail->send();
        }
        catch (Exception $e)
        {
            write_log("Mailer Error:{$this->mail->ErrorInfo}",'Error');
            return false;
        }
    }

    public static function send($to, $subject, $body, $attachment = null, $filename = null)
    {
        $inside = new self();
        return $inside->sendEmail($to, $subject, $body, $attachment, $filename);
    }
}
