<?php

namespace App\Controller;


use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $paniers = $this->getDoctrine()->getRepository(Panier::class)->findAll();
        
        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
        ]);
    }


    /**
     * @Route("/panier/delete/{id}", name="deleteproduitpanier")
     */
    public function deleteprod(Panier $panier, EntityManagerInterface $em)
    {
        //$paniers = $this->getDoctrine()->getRepository(Panier::class)->findAll();
        
        $em->remove($panier);
        $em->flush();
        return $this->redirectToRoute("panier");  
    }
}
