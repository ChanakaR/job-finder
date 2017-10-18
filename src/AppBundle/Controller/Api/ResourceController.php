<?php
/**
 * Created by PhpStorm.
 * User: inocer
 * Date: 10/19/17
 * Time: 12:26 AM
 */

namespace AppBundle\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
}