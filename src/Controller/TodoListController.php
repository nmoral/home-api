<?php

namespace App\Controller;

use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;
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
    public function create(Request $request): Response
    {
        /** @var string $content */
        $content = $request->getContent();
        $todoList = $this->denormalizer->denormalize($content);

        return new JsonResponse($this->recorder->create($todoList));
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     */
    public function update(Request $request, string $id): Response
    {
        /** @var string $content */
        $content = $request->getContent();
        $todoList = $this->denormalizer->denormalize($content);

        return new JsonResponse($this->recorder->update($id, $todoList));
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     */
    public function retrieve(string $id): Response
    {
        return new JsonResponse($this->recorder->retrieve($id));
    }

    /**
     * @Route(path="", methods={"GET"})
     */
    public function retrieveList(): Response
    {
        return new JsonResponse($this->recorder->retrieveList());
    }
}
