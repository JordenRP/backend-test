<?php

namespace App\Controller;

use App\DTO\MailDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckCodeController extends AbstractController
{
    #[Route('/checkCode', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $json = json_decode($request->getContent(), true);
        $mail = $json['mail'];
        $code = $json['code'];

        $file = new FilesystemAdapter();
        $item = $file->getItem(md5($mail . $code));

        if ($item->get() !== null) {
            $file->delete(md5($mail . $code));

            return new Response(true);
        }


        return new Response(content: false, status: 422);
    }
}