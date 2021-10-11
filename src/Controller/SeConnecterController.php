<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PDO;
use PDOException;

class SeConnecterController extends AbstractController
{
    public function index(): Response
    {

        return $this->render('se_connecter/index.html.twig', [
            'controller_name' => 'SeConnecterController',
        ]);
    }

    public function connexion(Request $request): Response{
        
        try{
            $dbName = 'gsbFrais';
            $host = 'localhost';
            $utilisateur = 'userGSB';
            $motDePasse = 'azerty';
            $port = '3306';
            $dns = 'mysql:host='.$host.';dbname='.$dbName.';port='.$port;
            $connection = new PDO( $dns, $utilisateur, $motDePasse);
        } catch (Exception $e) {
            echo "connection impossible : " . $e;
            die();
        }
        
        $login = $request->request->get('login');
        $mdp = $request->request->get('mdp');

        $requeteVisiteur = $connection->query("SELECT * from Visiteur");
        $infoVisiteur = $requeteVisiteur->fetchall();

        $requeteComptable = $connection->query("SELECT * from Comptable");
        $infoComptable = $requeteComptable->fetchall();

        foreach($infoComptable as $Comptable){
            if($Comptable['login'] == $login){
                if($Comptable['mdp'] == $mdp){

                    $session = $request->getSession();
                    $session -> set('id', $Comptable[0]);
                    $session -> set('nom', $Comptable[0]);
                    $session -> set('prenom', $Comptable[0]);
                    $session -> set('login', $login);

                    return $this->redirect('/comptable');
                }else{
                    $mdpComptable = false;
                }
            }else{
                $loginComptable = false;
            }
        }

        foreach($infoVisiteur as $Visiteur){
            if($Visiteur['login'] == $login){
                if($Visiteur['mdp'] == $mdp){

                    $session = $request->getSession();
                    $session -> set('id', $Visiteur[0]);
                    $session -> set('nom', $Visiteur[1]);
                    $session -> set('prenom', $Visiteur[2]);
                    $session -> set('login', $login);

                    return $this->redirect('/visiteur');

                }else{
                    $mdpVisiteur = false;
                }
            }else{
                $loginVisiteur = false;
            }
        }

        $connexionFailure = 1;

        return $this->redirectToRoute('seConnecter', [
            'connexionFailure' => $connexionFailure
        ]);

    }

    public function deconnexion(Request $request): Response
    {
        $session = $request->getSession();
        $session -> clear();

        return $this->redirect('/seConnecter');
    }
}
