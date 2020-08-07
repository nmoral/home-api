<?php

namespace App\Controller;

use App\Normalizer\JsonNormalizer;
use App\Recorder\TodoListDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController.
 *
 * @Route("/lists")
 */
class TodoListController extends AbstractController
{
    private TodoListDriver $recorder;

    private JsonNormalizer $denormalizer;

    public function __construct(TodoListDriver $recorder, JsonNormalizer $denormalizer)
    {
        $this->recorder = $recorder;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @Route(path="", methods={"POST"})
     */
    public function welcomeAction(Request $request): Response
    {
        /** @var string $content */
        $content = $request->getContent();
        $todoList = $this->denormalizer->denormalize($content);

        return new JsonResponse($this->recorder->save($todoList));
    }
}
