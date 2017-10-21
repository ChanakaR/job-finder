<?php
/**
 * Created by PhpStorm.
 * User: inocer
 * Date: 10/19/17
 * Time: 12:26 AM
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResourceController extends Controller
{

    /**
     * @Route("/api/resource")
     * @Method("GET")
     */
    public function newAction(){
        return new Response("Hi I am here");
    }

    /**
     * @Route("/api/resource/category_list")
     * @Method("GET")
     */
    public function getCategoryListAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            '
            SELECT DISTINCT r.category categories,
            COUNT (DISTINCT r.category) item_count
            FROM AppBundle:Resource r
            '
        );
        $results = $query->getArrayResult();

        return new JsonResponse($results);
    }
}