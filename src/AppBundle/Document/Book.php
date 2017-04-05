<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 04.04.2017
 * Time: 20:39
 */
namespace AppBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use MongoId;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Groups;

/**
 *
 * @ODM\Document
 */
class Book
{
    /**
     * @var MongoId $id
     * @Groups({"book"})
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @Groups({"book"})
     * @ODM\Field(type="string")
     */
    protected $title;

    /**
     * @var Author
     * @ODM\ReferenceOne(targetDocument="Author", inversedBy="books"))
     */
    protected $author;


    /**
     * @return MongoId
     */
    public function getId(): MongoId
    {
        return $this->id;
    }

    /**
     * @param MongoId $id
     */
    public function setId(MongoId $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle() //: string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {

        $this->title = $title;
    }

    /**
     * @return Author
     */
    public function getAuthor() //: Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

}