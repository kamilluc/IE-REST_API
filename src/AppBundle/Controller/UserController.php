<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 05.04.2017
 * Time: 08:00
 */

namespace AppBundle\Controller;


use AppBundle\Document\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("user")
 */
class UserController extends FOSRestController
{
    /**
     * @Get("/info")
     * @View()
     */
    public function infoAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'last_login' => $user->getLastLogin(),
            'roles' => $user->getRoles()
        ];
    }

     private function validateEmail($data){
        $constraints = array(
            new \Symfony\Component\Validator\Constraints\Email(),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );
        //$tmp=0;
        $tmp=$this->get('validator')->validate($data['email'], $constraints);
        if(count($tmp) > 0)
            return 1;
        else
            return 0;
    }

    private function validateNewUser($data)
    {
        $userManager = $this->get('fos_user.user_manager');
        // Check if email is free
        $user = $userManager->findUserByEmail($data['email']);
        if ($user) {
            throw new HttpException(409, "User with the same email already exists.");
        }
        // Check if username is free
        $user = $userManager->findUserByUsername($data['username']);
        if ($user) {
            throw new HttpException(409, "User with the same username already exists.");
        }
        // Check if email is proper (well formed, not blank)
        $constraints = array(
            new \Symfony\Component\Validator\Constraints\Email(),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );
        $constraints2 = array(
            //new \Symfony\Component\Validator\Constraints\E(),
            new \Symfony\Component\Validator\Constraints\Length(['min' => 6]),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );
        $errors = $this->get('validator')->validate($data['email'], $constraints);
        $errors2 = $this->get('validator')->validate($data['password'], $constraints2);


        //$errors=validateEmail($data);
        if (count($errors) > 0) {
            throw new HttpException(422, "Email address is not correct");
        }
        elseif (count($errors2)>0){
            throw new HttpException(422, "Password is not correct - use at least 6 characters");
        }
        return true;
    }

    /**
     * @Put("/register")
     * @View()
     */
    public function registerAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $data = $request->request->all();
        if($this->validateNewUser($data)){
            $user = $userManager->createUser();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setPlainPassword($data['password']);
            $user->setEnabled(true);
            $user->addRole("ROLE_USER");
            $userManager->updateUser($user);
            return ["token" => $this->get('lexik_jwt_authentication.jwt_manager')->create($user)];
 }
        throw new HttpException(500, "Unknown error");
    }

    /**
     * @Post("/update/{email}/{password}", name="user_update")
     * @Security("has_role('ROLE_USER')")
     * @View()
     */
    public function updateAction(Request $request, $email, $password)
    {
        $userManager = $this->get('fos_user.user_manager');
        //czy email jest zajety?
        $user = $userManager->findUserByEmail($email);
        if ($user) {
            throw new HttpException(409, "User with the same email already exists.");
        }
        //jakie mam email?
        /** @var User $user */
        $user2 = $this->getUser();
        $myemail = $user2->getEmail();

        $user3 = $userManager->findUserByEmail($myemail);
        $user3->setEmail($email);
        //$user3->setPassword($password);
        $user3->setPlainPassword($password);
        $userManager->updateUser($user3);
    }
//        $dm = $this->getDocumentManager();
//        $author = $dm->getRepository('AppBundle:Author')->find($id);
//        $user=$userManager->updateUser();
//        $user=$user->setUsername($data['username']);
//        $data = $request->request->all();
//        if($this->validateNewUser($data)){
//            $user = $userManager->createUser();
//            $user->setUsername($data['username']);
//            $user->setEmail($data['email']);
//            $user->setPlainPassword($data['password']);
//            $user->setEnabled(true);
//            $user->addRole("ROLE_USER");
//            $userManager->updateUser($user);
//            return ["token" => $this->get('lexik_jwt_authentication.jwt_manager')->create($user)];
//        }
//        throw new HttpException(500, "Unknown error");
//    }
//
/*
 $userManager = $this->get('fos_user.user_manager');
        // Check if email is free
        $user = $userManager->findUserByEmail($data['email']);
        if ($user) {
            throw new HttpException(409, "User with the same email already exists.");
        }
        // Check if username is free
        $user = $userManager->findUserByUsername($data['username']);
        if ($user) {
            throw new HttpException(409, "User with the same username already exists.");
        }
        // Check if email is proper (well formed, not blank)
        $constraints = array(
            new \Symfony\Component\Validator\Constraints\Email(),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );
        $constraints2 = array(
            //new \Symfony\Component\Validator\Constraints\E(),
            new \Symfony\Component\Validator\Constraints\Length(['min' => 6]),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );
        $errors = $this->get('validator')->validate($data['email'], $constraints);
        $errors2 = $this->get('validator')->validate($data['password'], $constraints2);


        //$errors=validateEmail($data);
        if (count($errors) > 0) {
            throw new HttpException(422, "Email address is not correct");
        }
        elseif (count($errors2)>0){
            throw new HttpException(422, "Password is not correct - use at least 6 characters");
        }
        return true;
  Aby sprawdzić czy w tablicy jest klucz o danej wartości, zastosuj metodę isset, np. isset($data['email']).
  Pozwoli to na aktualizację tylko przesłanych danych.
      @Post("/update/{id}", name="author_update")
      @View()
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
 */
}