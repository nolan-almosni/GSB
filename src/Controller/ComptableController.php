<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PDO;
use PDOException;

class ComptableController extends AbstractController
{
    public function index(Request $request): Response
    {

        $session = $request->getSession();

        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
            'session'=> $session,
        ]);
    }
}