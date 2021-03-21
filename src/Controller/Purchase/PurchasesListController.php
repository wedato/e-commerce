<?php


namespace App\Controller\Purchase;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchasesListController extends AbstractController
{


    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER" , message="Vous devez être connecté pour accéder à vos commandes")
     *
     *
     */
    public function index()
    {

        /** @var User */

//        N1. Nous devons nous assurer que la personne est connecté
        $user = $this->getUser();


//        3. Nous voulons passer l'utilisateur connecté à Twig
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);

    }
}