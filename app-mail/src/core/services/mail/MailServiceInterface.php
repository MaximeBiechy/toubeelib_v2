<?php
namespace toubeelib\core\services\mail;

interface MailServiceInterface {
    public function sendMail(string $from, string $to, string $subject, string $text, string $html, string $smtp): bool;
}