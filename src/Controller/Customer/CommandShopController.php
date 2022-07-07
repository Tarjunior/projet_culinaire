<?php 

namespace App\Controller\Customer;

use App\Entity\User;
use App\Entity\CommandShop;
use App\Repository\CommandShopRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandShopController extends AbstractController
{
    #[Route('/profile/commande/liste', name: 'command_shop_list')]
    // Méthode permettant de lister les commandes
    public function commandShopList(CommandShopRepository $commandShopRepository)
    {
        // Je récupère l'utilisateur connecté
        /** @var User $user */
        $user = $this->getUser();

        // Je trouve les commandes 
        $commandShop = $commandShopRepository->findBy([
            'user' => $user
        ],
        [
            'createdAt' => 'DESC'
        ]);

        return $this->render("customer/commande/list.html.twig",[
            'commandShop' => $commandShop
        ]);
    } 

    #[Route('/commande/detail/{id}', name: 'command_shop_detail')]
    // Méthode permettant de voir les details d'une commande
    public function commandShopDetail($id,CommandShopRepository $commandShopRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $commandShop = $commandShopRepository->find($id);

        if(!$commandShop)
        {
            $this->addFlash("danger","Commande introuvable");
            return $this->redirectToRoute("command_shop_list");
        }

        if($commandShop->getUser() !== $user)
        {
            $this->addFlash("danger","Cette commande ne vous appartient pas. Impossible de la consulter.");
            return $this->redirectToRoute("command_shop_list");
        }
        
        return $this->render("customer/commande/detail.html.twig",[
            'commandShop' => $commandShop
        ]);
    } 
}