<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
     * @Route(path="/", methods={"GET"})
     */
    public function welcomeAction(): Response
    {
        return new JsonResponse(['foo' => 'bar']);
    }
}
