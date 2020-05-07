<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $produit->setCreateAt(new \DateTime());
            $em->persist($produit);
            $em->flush();
            
            return $this->redirectToRoute("produits");
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("produit/{id}",name="produit")
     */
    public function produit(Produit $produit, Request $request, EntityManagerInterface $em){
        
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panier = $form->getData();
            $panier->setCreateAt(new \DateTime());
            $panier->setEtat(true);
            $panier->setProduit($produit);
            $em->persist($panier);
            $em->flush();
            return $this->redirectToRoute("produits");
        }        
        
        
        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/delete/{id}", name="deleteproduit")
     */
    public function deleteproduit(Produit $produit,$id,EntityManagerInterface $em)
    {
        //$produitid = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        //dump($produit);
        //$em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("produits");  
    }

}
