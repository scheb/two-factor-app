<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class EsiController extends Controller
{
    /**
     * @Route("/esi/navigation", name="navigation")
     */
    public function navigation()
    {
        return $this->render('_navigation.html.twig');
    }
}
