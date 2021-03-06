<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CategoryType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/categories", name="category_")
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $categoryManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $categoryManager->persist($category);
            // Flush the persisted object
            $categoryManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }    

        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    } 

    /**
    *
    *
    * @Route("/{categoryName}", name="show")
    * @return Response
    */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        if (!$category) {
            throw $this->createNotFoundException(
                'Aucune série trouvée dans la catégorie : ' .$categoryName. ''
            );
        }
        
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category'=> $category
    ]);
    }
}
