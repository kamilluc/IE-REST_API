<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 05.04.2017
 * Time: 07:15
 */

namespace AppBundle\Document;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}