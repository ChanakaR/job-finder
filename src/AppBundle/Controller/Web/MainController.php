<?php
/**
 * Created by PhpStorm.
 * User: inocer
 * Date: 10/20/17
 * Time: 10:35 PM
 */

namespace AppBundle\Controller\Web;

use AppBundle\Entity\Category;
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
        $cat_limit_col = $category_count%4;
        $cat_limit_row = ($category_count + (4-$cat_limit_col))/4;

        return $this->render('pages/home.html.twig',[
            "category_list"=>$category_list,
            "category_size"=>$category_count,
            "category_limit_row" => $cat_limit_row,
            "category_limit_col" => $cat_limit_col
        ]);
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
}