<?php
/**
 * Created by PhpStorm.
 * User: inocer
 * Date: 10/20/17
 * Time: 10:35 PM
 */

namespace AppBundle\Controller\Web;

use AppBundle\Entity\Category;
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
     * @Route("/employer",name="employer")
     */
    public function employerAction(){

        $employer_list = $this->getEmployersList();

        $employer_count = count($employer_list);
        $emp_limit_col = $employer_count%4;
        $emp_limit_row = ($employer_count + (4-$emp_limit_col))/4;

        return $this->render('pages/employer.html.twig',[
            "home_activated" => false,
            "category_activated" => false,
            "employer_activated" => true,
            "contact_activated" => false,
            "employer_count"=>$employer_count,
            "employer_limit_row" => $emp_limit_row,
            "employer_limit_col" => $emp_limit_col,
            "employer_list"=>$employer_list,
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
    private function getVacancyByCategory($categoryId){
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $query =$repository->createQueryBuilder('v')
            ->orderBy('v.id','DESC')
            ->where("v.category = :category_id")
            ->setParameter("category_id",$categoryId)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /*
     * Get list of job vacancies by category ID
     */
    private function getEmployersList(){
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $query =$repository->createQueryBuilder('v')
            ->select('v.employer')
            ->orderBy('v.employer','ASC')
            ->distinct(true)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }
}