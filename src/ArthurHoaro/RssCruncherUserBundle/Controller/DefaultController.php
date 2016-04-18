<?php

namespace ArthurHoaro\RssCruncherUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{
//    public function indexAction($name)
//    {
//        return $this->render('ArthurHoaroRssCruncherUserBundle:Default:index.html.twig', array('name' => $name));
//    }

    public function testAction(Request $request)
    {
        $value = $request;
        echo $value;
    }
}
