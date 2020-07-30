<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
     * @return Response
     * @Route(path="/", methods={"GET"})
     */
    public function welcomeAction(): Response
    {
        return new Response();
    }
}