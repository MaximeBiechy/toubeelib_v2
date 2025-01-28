<?php
namespace toubeelib\core\services\mail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailService implements MailServiceInterface {

    public function __construct() {

    }

    public function sendMail(string $from, string $to, string $subject, string $text, string $html, string $smtp): bool
    {
        try {
            $transport = Transport::fromDsn($smtp);
            $mailer = new Mailer($transport);

            $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->text($text)
                ->html($html);

            $mailer->send($email);

            return true;
        } catch (\Exception $e) {
            return false;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}