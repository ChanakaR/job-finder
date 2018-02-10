<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancy
 *
 * @ORM\Table(name="vacancy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VacancyRepository")
 */
class Vacancy
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="vacancy")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $category;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Employer", inversedBy="vacancy")
     * @ORM\JoinColumn(name="employer_id",referencedColumnName="id")
     */
    private $employer;


    /**
     * @var string
     *
     * @ORM\Column(name="description",type="string",length=150)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location",type="string",length=75)
     */
    private $location;


    /**
     * @return string
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * @param string $employer
     */
    public function setEmployer($employer)
    {
        $this->employer = $employer;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param $location
     * @internal param string $position
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }


    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory(){
        return $this->category;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Resource
     */
    public function setCategory($category){
        $this->category = $category;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Resource
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

