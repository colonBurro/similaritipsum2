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

    #[Route('api/classifier/learn', name: 'classifier_learn')]
    public function learn(Request $request)
    {
        $newDocuments = 0;
        $invalidDocuments = 0;

        $data = json_decode($request->getContent(), true);

        if (!isset($data["documents"]) || empty($data["documents"])) return $this->json(["documents"=>"invalid or missing 'documents' parameter."], Response::HTTP_BAD_REQUEST);
        if (!isset($data["category"]) || empty($data["category"])) return $this->json(["message"=>"invalid or missing 'category' parameter."], Response::HTTP_BAD_REQUEST);

        $category = $this->categoryRepository->findOneBy(["name" => $data["category"]]);
        if (!$category) $category = new Category($data["category"]);

        $this->classifier->checkAndSetCategory($data["category"])
            ->setDocumentCount($category->getDocumentCount())
            ->setTermCount($category->getTermCount())
            ->setTermFrequencies($category->getTermFrequencies());

        foreach ($data["documents"] as $document){
            if($this->classifier->learn($data["category"], $document))$newDocuments++;
            else $invalidDocuments++;
        }

        $classifierCategory = $this->classifier->fetchCategory($data["category"]);

        $category->setDocumentCount($classifierCategory->getDocumentCount())
            ->setTermCount($classifierCategory->getTermCount())
            ->setTermFrequencies($classifierCategory->getTermFrequencies());

        $this->categoryRepository->save($category, true);

        return $this->json(["message" => "Succesfully added ". $newDocuments ." documents. ". $invalidDocuments ." documents were invalid."], Response::HTTP_OK);
    }

    #[Route('api/classifier/classify', name: 'classifier_classify')]
    public function classify(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["documents"]) || empty($data["documents"])) return $this->json(["documents"=>"invalid or missing 'documents' parameter."], Response::HTTP_BAD_REQUEST);

        $categories = $this->categoryRepository->findAll();

        $this->classifier->loadCategoriesFromDatabase($categories);
        

        $result = [];

        foreach ($data["documents"] as $document){
            $classifyResult = $this->classifier->classify($document);
            $belongsTo = array_key_first($classifyResult);
            $result[] = [
                "document" => substr($document, 0, 30),
                "probabilities" => $classifyResult,
                "belongsTo" => $belongsTo
            ];
        }

        return $this->json(["message" => $result], Response::HTTP_OK);
    }
}