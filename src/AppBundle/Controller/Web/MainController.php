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
    /**
     * @Route("/",name="homepage")
     */
    public function homeAction(){


        $category_list = $this->getCategoryList();
        $category_count = count($category_list);
        $cat_limit_col = $category_count%6;
        $cat_limit_row = ($category_count + (6-$cat_limit_col))/6;

        $recent_vacancy_list = $this->getRecentJobVacancies();
        return $this->render('pages/home.html.twig',[
            "category_list"=>$category_list,
            "category_size"=>$category_count,
            "category_limit_row" => $cat_limit_row,
            "category_limit_col" => $cat_limit_col,
            "recent_vacancy_list" => $recent_vacancy_list
        ]);
    }


    /**
     * @Route("/viewJobSpecs/{vacancy}",name="jobSpecPage")
     */
    public function viewJobSpecAction($vacancy){
        return $this->render('pages/viewJobSpec.html.twig',[
            "vacancy"=>"vacancies/$vacancy"
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
}