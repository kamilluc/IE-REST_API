<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 04.04.2017
 * Time: 20:42
 */

namespace AppBundle\Controller;

use AppBundle\Document\Book;
use AppBundle\Form\BookType;
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
 *@Route("book")
 */
class BookController extends FOSRestController
{
    private function getDocumentManager(){
        return $this->get('doctrine_mongodb')->getManager();
    }


    /**
     * @Get("/", name="book_index")
     * @Security("has_role('ROLE_USER')")
     * @View(serializerGroups={"book"})
     */
    public function indexAction(Request $request)
    {
        $dm = $this->getDocumentManager();
        $books = $dm->getRepository('AppBundle:Book')->findAll();
        return $books;
    }


    /**
     * @Get("/get/{id}", name="book_get")
     * @Security("has_role('ROLE_USER')")
     * @View()
     */
    public function getAction(Request $request, string $id)
    {
        $dm = $this->getDocumentManager();
        $book = $dm->getRepository('AppBundle:Book')->find($id);
        if (!$book){
            throw $this->createNotFoundException('Book not found');
        }
        return $book;
    }


    /**
     * @Put("/create", name="book_create")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function createAction(Request $request){

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($book);
            $dm->flush();
            return $book;
        }
        return $form;
    }


    /**
     * @Post("/update/{id}", name="book_update")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();
        $book = $dm->getRepository('AppBundle:Book')->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $dm->persist($book);
            $dm->flush();
            return $book;
        }
        return $form;
    }

    /**
     * @Delete("/{id}", name="book_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @View()
     */
    public function deleteAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();
        $book = $dm->getRepository('AppBundle:Book')->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Selected book does not exist');
        }
        $dm->remove($book);
        $dm->flush();
        return ["status" => "OK"];
    }


    /**
     * @Get("/search/{title}", name="book_search")
     * @Security("has_role('ROLE_USER')")
     * @View(serializerGroups={"list"})
     */
    public function searchAction(Request $request, string $title){
        $dm=$this->getDocumentManager();
        // Option 1.
        // $repository = $dm->getRepository('AppBundle:Book');
        // $books = $repository->findBy(['title' => $title]);
        // Option 2.
        // $books = $dm->createQueryBuilder('AppBundle:Book')->field('title')->equals($title)->getQuery()->toArray();
        // Option 3 – LIKE using Regex
        $books = $dm->createQueryBuilder('AppBundle:Book')->field('title')->equals(new
        MongoRegex('/.*'.$title.'.*/i'))->getQuery()->toArray();
        if (!$books){
            throw $this->createNotFoundException('Book not found');
        }
        return $books;
    }

}
