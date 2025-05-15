<?php
    /**
     * Mailer Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Mailer.php, v1.00 7/1/2023 4:44 PM Gewa Exp $
     *
     */
    
    use PHPMailer\PHPMailer\PHPMailer;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Mailer
    {
        private function __construct()
        {
        }
        
        /**
         * instance
         *
         * @return Mailer
         */
        public static function instance(): Mailer
        {
            if (!self::$instance) {
                self::$instance = new Mailer();
            }
            
            return self::$instance;
        }
        
        /**
         * sendMail
         *
         * @return PHPMailer
         */
        public static function sendMail(): PHPMailer
        {
            require_once BASEPATH . 'lib/PHPMailer/vendor/autoload.php';
            
            $core = App::Core();
            $mail = new PHPMailer(true);
            
            if ($core->mailer == 'SMTP') {
                $mail->isSMTP();
            } else {
                $mail->isSendmail($core->sendmail);
            }
            //$mail->SMTPDebug = 4;//Enable verbose debug output
            $mail->Host = $core->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $core->smtp_user;
            $mail->Password = $core->smtp_pass;
            $mail->SMTPSecure = $core->is_ssl ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $core->smtp_port;            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            
            return $mail;
        }
        
        /**
         * sendExpiryNotification
         * 
         * Sends subscription expiry notification to a user
         *
         * @param string $email Recipient email address
         * @param string $name Recipient name
         * @param string $membershipTitle Membership/subscription title
         * @param string $expiryDate Formatted expiry date
         * @param string $membershipType Type of membership (optional)
         * @return bool Success status
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public static function sendExpiryNotification(string $email, string $name, string $membershipTitle, string $expiryDate, string $membershipType = 'membership'): bool
        {
            $core = App::Core();
            $mailer = self::sendMail();
            
            try {
                // Get email template or use default content
                $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))
                    ->where('typeid', 'subscription_expiry', '=')
                    ->first()->run();
                
                // If template doesn't exist, use default subject
                $subject = $tpl ? $tpl->subject : "Your " . $core->company . " " . ucfirst($membershipType) . " is Expiring Soon";
                
                // If template exists, use it, otherwise build a default email
                if ($tpl) {
                    $body = str_replace(array(
                        '[LOGO]',
                        '[NAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[MEMBERSHIP]',
                        '[EXPIRE_DATE]',
                        '[RENEW_URL]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]',
                        '[YEAR]'
                    ), array(
                        $core->plogo,
                        $name,
                        date('Y'),
                        $core->company,
                        $core->company,
                        $membershipTitle,
                        $expiryDate,
                        SITEURL . '/dashboard/',
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL,
                        date('Y')
                    ), $tpl->body);
                } else {
                    // Default email body if no template exists
                    $body = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>";
                    $body .= "<div style='text-align: center; padding: 20px;'><img src='" . SITEURL . "/uploads/logo.svg' alt='Logo' style='max-width: 200px;'></div>";
                    $body .= "<h2 style='color: #333;'>" . ucfirst($membershipType) . " Expiry Notification</h2>";
                    $body .= "<p>Hello <strong>" . $name . "</strong>,</p>";
                    $body .= "<p>This is a friendly reminder that your <strong>" . $membershipTitle . "</strong> " . $membershipType . " is set to expire on <strong>" . $expiryDate . "</strong>.</p>";
                    $body .= "<p>To continue enjoying our services without interruption, please renew your " . $membershipType . " before the expiration date.</p>";
                    $body .= "<p><a href='" . SITEURL . "/dashboard/' style='display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Renew " . ucfirst($membershipType) . "</a></p>";
                    $body .= "<p>If you have any questions or need assistance, please don't hesitate to contact us.</p>";
                    $body .= "<p>Thank you for being a valued member!</p>";
                    $body .= "<p>Best regards,<br>The " . $core->company . " Team</p>";
                    $body .= "</div>";
                }
                
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($email, $name);
                $mailer->isHTML(true);
                
                return $mailer->send();
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log("Error sending expiry notification: " . $e->getMessage());
                return false;
            }
        }
    }