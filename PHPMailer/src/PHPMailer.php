<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * @package PHPMailer
 * @link https://github.com/PHPMailer/PHPMailer/
 */

use PHPMailer\PHPMailer\Exception;

class PHPMailer
{
    public $From = 'root@localhost';
    public $FromName = 'Root User';
    public $Host = 'localhost';
    public $Mailer = 'smtp'; // or 'mail', 'sendmail', or 'qmail'
    public $SMTPAuth = false;
    public $Username = ''; // SMTP username
    public $Password = ''; // SMTP password
    public $SMTPSecure = ''; // 'ssl' or 'tls'
    public $Port = 25; // SMTP port
    public $CharSet = 'UTF-8';
    public $WordWrap = 50;
    public $IsHTML = true;
    public $Subject = '';
    public $Body = '';
    public $AltBody = '';

    // The recipients of the email
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];

    // Add a recipient
    public function addAddress($address, $name = '')
    {
        $this->to[] = ['address' => $address, 'name' => $name];
    }

    // Add a CC recipient
    public function addCC($address, $name = '')
    {
        $this->cc[] = ['address' => $address, 'name' => $name];
    }

    // Add a BCC recipient
    public function addBCC($address, $name = '')
    {
        $this->bcc[] = ['address' => $address, 'name' => $name];
    }

    // Send the email
    public function send()
    {
        // Initialize the email sending logic
        if ($this->Mailer === 'smtp') {
            // SMTP logic
            $this->sendSMTP();
        } else {
            // Other mailer logic (mail(), sendmail, etc.)
            $this->sendMail();
        }
    }

    // SMTP sending method
    protected function sendSMTP()
    {
        // Setup SMTP settings
        $smtp = new SMTP();
        $smtp->connect($this->Host, $this->Port);
        if ($this->SMTPAuth) {
            $smtp->authenticate($this->Username, $this->Password);
        }
        $smtp->send($this->to, $this->Subject, $this->Body);
    }

    // Mail function sending method
    protected function sendMail()
    {
        $headers = "From: " . $this->FromName . " <" . $this->From . ">\r\n";
        $headers .= "Content-type: text/html; charset=" . $this->CharSet . "\r\n";

        mail(implode(',', array_column($this->to, 'address')), $this->Subject, $this->Body, $headers);
    }
}
