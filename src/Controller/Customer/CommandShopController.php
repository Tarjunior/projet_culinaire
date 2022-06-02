<?php 

namespace App\Controller\Customer;

use App\Entity\User;
use App\Entity\CommandShop;
use App\Repository\CommandShopRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandShopController extends AbstractController
{
    #[Route('/commande/liste', name: 'command_shop_list')]
    public function commandShopList(CommandShopRepository $commandShopRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

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
        // dd($commandShop);
        return $this->render("customer/commande/detail.html.twig",[
            'commandShop' => $commandShop
        ]);
    } 
}