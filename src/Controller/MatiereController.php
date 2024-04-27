<?php

namespace App\Controller;
use App\Form\MatiereType;
use App\Entity\Matiere;
use App\Form\CategorieType;
use App\Entity\Categorie;
use App\Form\PropertySearchType;
use App\Entity\PropertySearch;
use App\Form\TravailType;
use App\Entity\Travail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TravailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TravailLikeRepository;
use App\Entity\TravailLike;

class MatiereController extends AbstractController
{
    /**
     * @Route("/matiere", name="matiere")
     */
    public function index(Request $request): Response
    {    $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Matiere::class);
        $matieres= $repo->findAll();
        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom de la matiere tapée dans le formulaire
            $Nom=$propertySearch->getNom();
            if ($Nom!="")
            $matieres= $this->getDoctrine()->getRepository(Matiere::class)->findBy(['nom' => $Nom] );
            else
 //si si aucun nom n'est fourni on affiche tous les articles
 $matieres= $this->getDoctrine()->getRepository(Matiere::class)->findAll();
 }

        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,'formlistrub' =>$form->createView()
        ]);
    
    }
    /**
     * @Route("/matiere/ajouter", name="ajouter_matiere")
     */
    public function ajouterMatiere(Request $request): Response
    {

        $matiere = new Matiere();


        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            #$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('matiere');
        }


        return $this->renderForm('matiere/new.html.twig', [
            'formmat' => $form,
        ]);
    }
    /**
     * @Route("/matiere/newcategorie/{id}", name="newcategorie")
     */
    public function newcategorie(Request $request , Matiere $matiere): Response
    {   $matiere -> getId();
        $cat = new Categorie();
        $cat -> setMatieres($matiere);                                                                                                                                                                                                                                     
        $form = $this->createForm(CategorieType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original $task variable has also been updated
            #$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cat);
            $entityManager->flush();

            return $this->redirectToRoute('matiere');
        }


        return $this->renderForm('matiere/newCat.html.twig', [
            'formcat' => $form,
            
        ]);
    }
       /**
     * @Route("/matiere/affichecategories/{id}", name="affiche_categorie")
     */
    public function afficheCategories(Matiere $matiereid): Response
    {  $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categories= $repo->findAll();
        $cat = new Categorie();
        $cat -> getMatieres();                                                                                                                                                                                                                                     

        return $this->renderForm('matiere/Cat.html.twig', [
            'categories' => $categories,'matiereid'=> $matiereid,
            
        ]);
    }
     /**
     * @Route("/matiere/modif/{id}", name="modif")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $matiere = new Matiere();

        $matiere = $this->getDoctrine()->getRepository(Matiere::class)->find($id);

        $form = $this->createForm(MatiereType::class, $matiere);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('matiere');
        }

        return $this->render('matiere/modif.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/matiere/travail/{id}", name="newtravail")
     */
    public function newTravail(Request $request , Matiere $matiere): Response
    {   $matiere -> getId();
        $travail = new Travail();
        $travail-> setMatieres($matiere);                                                                                                                                                                                                                                     
        $form = $this->createForm(TravailType::class, $travail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file= $form['file']->getData();
            $uploads_directory=$this->getParameter('uploads_directory');
            $filename=md5(uniqid()). '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );

            // $form->getData() holds the submitted values
            // but, the original $task variable has also been updated
            #$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travail);
            $travail->setFile($filename);
            $entityManager->flush();

            return $this->redirectToRoute('matiere');
        }


        return $this->renderForm('matiere/newTra.html.twig', [
            'formtr' => $form,
            
        ]);
    }

          /**
     * @Route("/matiere/afficheTravaux/{id}", name="affiche_travaux")
     */
    public function afficheTravaux(Matiere $matiereid): Response
    {  $repo = $this->getDoctrine()->getRepository(Travail::class);
        $travaux= $repo->findAll();
        $travail = new Travail();
        $travail -> getMatieres(); 
         

        return $this->renderForm('matiere/travail.html.twig', [
            'travaux' => $travaux,'matiereid'=> $matiereid,
            
        ]);
    }
    /**
     * @Route("/matiere/modiftravail/{id}", name="modif_travail")
     * Method({"GET", "POST"})
     */
    public function editTravail(Request $request, $id)
    {
        $travail = new Travail();

        $travail = $this->getDoctrine()->getRepository(Travail::class)->find($id);

        $form = $this->createForm(TravailType::class, $travail);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file= $form['file']->getData();
            $uploads_directory=$this->getParameter('uploads_directory');
            $filename=md5(uniqid()). '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($travail);
            $travail->setFile($filename);
            $entityManager->flush();

            return $this->redirectToRoute('matiere');
        }

        return $this->renderForm('matiere/modifTravail.html.twig', [
            'formModifTr' => $form,
           
            
        ]);
    }
     /**
    * @Route("/matiere/affichagetravail/{id}/like" , name="travail_like")
    * 
    */
   public function like(Travail $travail,EntityManagerInterface $manager, TravaillikeRepository $likerepo):Response
   {
   
     $user = $this->getUser();
     if(!$user) return $this ->json([
         'code' => 403,
         'message'=> "Unauthorized"
     ],403);
     if($travail->isLikedByUser($user)){
       $like=$likerepo->findOneBy([
           'travail' =>$travail,
           'user'=>$user
       ])  ;
       $manager ->remove($like);
       $manager->flush();
       return new Response("dislike Validée ");
     }
      $like= new TravailLike();
      $like->setTravail($travail)
      ->setUser($user);
      $manager->persist($like);
      $manager->flush();
      
      return new Response("like Validée ");

   }

}
