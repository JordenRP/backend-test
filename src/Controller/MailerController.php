<?php

namespace App\Controller;

use App\DTO\MailDto;
use App\Service\MailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    public function __construct(private MailSender $mailSender)
    {
    }

    #[Route('/mailing', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $json = json_decode($request->getContent(), true);
        $mailDto = (new MailDto());
        $mailDto->email = $json['mail'];

        $this->mailSender->send($mailDto);

        return new Response(true);
    }
}