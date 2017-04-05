<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 04.04.2017
 * Time: 23:13
 */

namespace AppBundle\Controller;
//czy potrzebne?
//use AppBundle\Document\Book;
//use AppBundle\Form\BookType;
//end
use AppBundle\Document\Author;
use AppBundle\Form\AuthorType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use MongoRegex;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 *@Route("author")
 */
class AuthorController extends FOSRestController
{
    private function getDocumentManager()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }


    /**
     * @Get("/", name="author_index")
     * @Security("has_role('ROLE_USER')")
     * @View(serializerGroups={"author"})
     */
    public function indexAction(Request $request)
    {
        $dm = $this->getDocumentManager();
        $authors = $dm->getRepository('AppBundle:Author')->findAll();
        return $authors;
    }


    /**
     * @Get("/get/{id}", name="author_get")
     * @Security("has_role('ROLE_USER')")
     * @View()
     */
    public function getAction(Request $request, string $id)
    {
        $dm = $this->getDocumentManager();
        $author = $dm->getRepository('AppBundle:Author')->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }
        return $author;
    }


    /**
     * @Put("/create", name="author_create")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function createAction(Request $request)
    {

        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($author);
            $dm->flush();
            return $author;
        }
        return $form;
    }


    /**
     * @Post("/update/{id}", name="author_update")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();
        $author = $dm->getRepository('AppBundle:Author')->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $dm->persist($author);
            $dm->flush();
            return $author;
        }
        return $form;
    }

    /**
     * @Delete("/{id}", name="author_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function deleteAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();
        $author = $dm->getRepository('AppBundle:Author')->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Selected author does not exist');
        }
        $dm->remove($author);
        $dm->flush();
        return ["status" => "OK"];
    }


    /**
     * @Get("/search/{name}", name="author_search")
     * @Security("has_role('ROLE_USER')")
     * @View(serializerGroups={"list"})
     */
    public function searchAction(Request $request, string $name)
    {
        $dm = $this->getDocumentManager();
        // Option 1.
        // $repository = $dm->getRepository('AppBundle:Book');
        // $books = $repository->findBy(['title' => $title]);
        // Option 2.
        // $books = $dm->createQueryBuilder('AppBundle:Book')->field('title')->equals($title)->getQuery()->toArray();
        // Option 3 – LIKE using Regex
        $authors = $dm->createQueryBuilder('AppBundle:Author')->field('name')->equals(new
        MongoRegex('/.*'.$name.'.*/i'))->getQuery()->toArray();
        if (!$authors) {
            throw $this->createNotFoundException('Author not found');
        }
        return $authors;
    }
}