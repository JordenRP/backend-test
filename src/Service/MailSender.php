<?php

namespace App\Service;

use App\DTO\MailDto;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Cache\ItemInterface;

class MailSender
{
    public function __construct()
    {
    }

    public function send(MailDto $mailDto)
    {
        $cache = new FilesystemAdapter();

        $code = (string) rand(1000, 9999);
        $key = $mailDto->email . $code;

        $code = $cache->get(md5($key), function (ItemInterface $item) use ($code) : string {
            $item->expiresAfter(3600);

            $computedValue = $code;

            return $computedValue;
        });

        $email = (new Email())
            ->from('gritsatsuev.pavel@gmail.com')
            ->to($mailDto->email)
            ->subject('Verification code')
            ->text("$code");

        $transport = Transport::fromDsn('gmail+smtp://gritsatsuev.pavel@gmail.com:pqwuodumyqkebttc@default');

        $mailer = new Mailer($transport);

        $mailer->send($email);
    }
}