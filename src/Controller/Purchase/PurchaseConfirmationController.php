<?php


namespace App\Controller\Purchase;


use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{



    protected $cartService;
    protected $em;

    public function __construct(   CartService $cartService , EntityManagerInterface $em) {

        $this->cartService = $cartService;
        $this->em = $em;
    }


    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
    public function confirm(Request $request ,  Security $security){

        $form = $this->createForm(CartConfirmationType::class);


        $form->handleRequest($request);

        if(!$form->isSubmitted()) {
            $this->addFlash('warning' , 'Vous devez remplir le formulaire');

            return $this->redirectToRoute('cart_show');

        }

        $user = $this->getUser();



        $cartItems = $this->cartService->getDetailedCardItems();

        if(count($cartItems) === 0) {
            $this->addFlash('warning' , 'Vous ne pouvez confirmer une commande avec un panier vide');

            return $this->redirectToRoute('cart_show');

        }

        /** @var Purchase */

        $purchase = $form->getData();

        $purchase->setUser($user)
            ->setPurchasedAt(new \DateTime())
            ->setTotal($this->cartService->getTotal());
        $this->em->persist($purchase);


        foreach ($this->cartService->getDetailedCardItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->product->getPrice());


            $this->em->persist($purchaseItem);
        }



        $this->cartService->empty();
        $this->em->flush();

        $this->addFlash('success' , 'La comamande a bien été enregistré');

    return $this->redirectToRoute('purchase_index');



    }
}