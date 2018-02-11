<?php
/**
 * Created by PhpStorm.
 * User: inocer
 * Date: 10/20/17
 * Time: 10:35 PM
 */

namespace AppBundle\Controller\Web;

use AppBundle\Entity\Category;
use AppBundle\Entity\Employer;
use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    private $category_list = null;
    /**
     * @Route("/",name="homepage")
     */
    public function homeAction(){

        if($this->category_list == null){
            $this->category_list = $this->getCategoryList();
        }
        $category_count = count($this->category_list);
        $cat_limit_col = $category_count%6;
        $cat_limit_row = ($category_count + (6-$cat_limit_col))/6;

        $recent_vacancy_list = $this->getRecentJobVacancies();

        return $this->render('pages/home.html.twig',[
            "home_activated" => true,
            "category_activated" => false,
            "employer_activated" => false,
            "contact_activated" => false,
            "category_list"=>$this->category_list,
            "category_size"=>$category_count,
            "category_limit_row" => $cat_limit_row,
            "category_limit_col" => $cat_limit_col,
            "recent_vacancy_list" => $recent_vacancy_list
        ]);
    }

    /**
     * @Route("/category",name="category")
     */
    public function categoryAction(){

        if($this->category_list == null){
            $this->category_list = $this->getCategoryList();
        }

        $category_count = count($this->category_list);
        $cat_limit_col = $category_count%4;
        $cat_limit_row = ($category_count + (4-$cat_limit_col))/4;

        return $this->render('pages/category.html.twig',[
            "home_activated" => false,
            "category_activated" => true,
            "employer_activated" => false,
            "contact_activated" => false,
            "category_size"=>$category_count,
            "category_limit_row" => $cat_limit_row,
            "category_limit_col" => $cat_limit_col,
            "category_list"=>$this->category_list,
        ]);
    }

    /**
     * @Route("/category/{id}",name="categoryDetails")
     */
    public function categoryDetailsAction($id){

        $vacancy_list = $this->getVacancyByCategoryId($id);
        $category = $this->getCategoryById($id);

        return $this->render('pages/viewCategorySpec.html.twig',[
            "home_activated" => false,
            "category_activated" => true,
            "employer_activated" => false,
            "contact_activated" => false,
            "vacancy_list"=> $vacancy_list,
            "category" => $category,
        ]);
    }

    /**
     * @Route("/employer",name="employer")
     */
    public function employerAction(){

        $employer_list = $this->getEmployersList();


        return $this->render('pages/employer.html.twig',[
            "home_activated" => false,
            "category_activated" => false,
            "employer_activated" => true,
            "contact_activated" => false,
            "employer_list"=>$employer_list,
        ]);
    }
    /**
     * @Route("/employer/{id}",name="employerDetails")
     */
    public function employerDetailsAction($id){

        $employer_details = $this->getEmployerDetailsById($id);
        
        return $this->render('pages/viewEmployerSpec.html.twig',[
            "home_activated" => false,
            "category_activated" => false,
            "employer_activated" => false,
            "contact_activated" => false,
            "employer"=>$employer_details["employer"],
            "vacancy_list"=>$employer_details["vacancy_list"],
        ]);
    }


    /**
     * @Route("/viewJobSpecs/{vacancy}",name="jobSpecPage")
     * @param $vacancy
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewJobSpecAction($vacancy){
        return $this->render('pages/viewJobSpec.html.twig',[
            "vacancy"=>"vacancies/$vacancy",
            "home_activated"=>false,
            "category_activated"=> false,
            "employer_activated" => false,
            "contact_activated" => false,

        ]);
//        return $this->redirect($this->generateUrl('homepage'));
    }




    /*
     * Get category list from database and return as a json
     */
    private function getCategoryList(){
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $query = $repository->createQueryBuilder('c')
            ->select('c.name')
            ->orderBy('c.name','ASC')
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /*
     * Get category list from database and return as a json
     */
    private function getCategoryById($id){
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $query = $repository->createQueryBuilder('c')
            ->where("c.id= :category_id")
            ->setParameter("category_id",$id)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /*
     * Get recent job vacancies
     */
    private function getRecentJobVacancies(){
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $query =$repository->createQueryBuilder('v')
            ->innerJoin('v.employer','e')
            ->addSelect('e.name AS emp_name')
            ->orderBy('v.id','DESC')
            ->setMaxResults(100)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /*
     * Get list of job vacancies by category ID
     */
    private function getVacancyByCategoryId($categoryId){
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $query =$repository->createQueryBuilder('v')
            ->innerJoin('v.employer','e')
            ->addSelect('e.name AS emp_name')
            ->orderBy('v.id','DESC')
            ->where("v.category = :category_id")
            ->setParameter("category_id",$categoryId)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /*
     * Get Employer details
     */
    private function getEmployersList(){
        $repository = $this->getDoctrine()->getRepository(Employer::class);
        $query =$repository->createQueryBuilder('e')
            ->select('e')
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    private function getEmployerDetailsById($id){
        $employer = $this->getDoctrine()->getRepository(Employer::class)
            ->createQueryBuilder('e')
            ->where("e.id = :employer_id")
            ->setParameter("employer_id",$id)
            ->getQuery()
            ->getArrayResult();

        $vacancy_list = $this->getDoctrine()->getRepository(Vacancy::class)
            ->createQueryBuilder('v')
            ->where("v.employer = :employer_id")
            ->setParameter("employer_id",$id)
            ->orderBy('v.id','DESC')
            ->getQuery()->getArrayResult();

        $result = array("employer" => $employer, "vacancy_list"=>$vacancy_list);
        return $result;
    }
}