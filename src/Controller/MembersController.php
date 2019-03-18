<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MembersController extends Controller
{
    /**
     * @Route("/members", name="members_area")
     */
    public function login()
    {
        return $this->render('members/index.html.twig');
    }
}
