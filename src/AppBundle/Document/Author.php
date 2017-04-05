<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 04.04.2017
 * Time: 23:10
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

class Author
{
    /**
     * @var MongoId $id
     * @Groups({"author"})
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @Groups({"author"})
     * @ODM\Field(type="string")
     */
    protected $name;


    /**
     * @var Book[]|ArrayCollection
     * @ODM\ReferenceMany(targetDocument="Book", mappedBy="author")
     */
    protected $books;


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
    public function getName() //: string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Book[]|ArrayCollection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param Book[]|ArrayCollection $books
     */
    public function setBooks($books)
    {
        $this->books = $books;
    }

    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

}