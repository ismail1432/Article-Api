<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Author;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\{Get, View, Post};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ArticleController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Request $request)
    {
        $author = new Author();
        $author->setFullname('Eniams');
        $author->setBiography('Eniams is a strong self-educated french developer....');
        $art = new Article();
        $art->setTitle('titre api article');
        $art->setAuthor($author);
        $art->setContent('super article');

        return $art;
    }

    /**
     * @Post("/articles")
     * @View(StatusCode = 201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function createAction(Article $article, ConstraintViolationList $violations)
    {


        if (count($violations)) {
            die(var_dump(count($violations)));
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();


        return $this->view($article, Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_article_show',
                    ['id' => $article->getId(),
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ])
            ]);
    }
}
