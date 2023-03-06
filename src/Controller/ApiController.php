<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Classifier\BayesClassifier;
use App\Entity\Category;
use App\Repository\CategoryRepository;

class ApiController extends AbstractController
{
    private SerializerInterface $serializer;

    private HttpClientInterface $client;

    private BayesClassifier $classifier;

    public function __construct(
        SerializerInterface $serializer,
        HttpClientInterface $client,
        BayesClassifier $classifier,
        CategoryRepository $categoryRepository
    )
    {
        $this->serializer = $serializer;
        $this->client = $client;
        $this->classifier = $classifier;
        $this->categoryRepository = $categoryRepository;
    }
}