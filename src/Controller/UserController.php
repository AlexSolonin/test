<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\UserType;
use App\Service\GiftService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/login")
 */
class UserController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="user_index", methods={"GET", "POST"})
     * @param Request $request
     * @param GiftService $giftService
     * @return Response
     */
    public function index(Request $request, GiftService $giftService): Response
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $password = md5($formData->getPassword());
            $queryUser = $this->em->getRepository(User::class)->findOneBy(
                [
                    'email' => $formData->getEmail()
                ]
            );

            if (isset($queryUser) && $queryUser->getPassword() == $password) {
                $form = $this->createForm(LoginType::class, $user);
                $form->handleRequest($request);
                $giftService->getGiftList($queryUser->getId());

                return $this->render('user/personal.twig', [
                    'user' => $queryUser,
                    'bonus' =>$giftService->giftList['bonus'],
                    'money' =>$giftService->giftList['money'],
                    'prizes' =>$giftService->giftList['prize'],
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $userFromDb = $this->em->getRepository(User::class)
                ->findOneBy(
                    [
                        'email' => $formData->getEmail()
                    ]
                );

            if (!isset($userFromDb)) {
                $user = new User();
                $user->setName($formData->getName());
                $user->setEmail($formData->getEmail());
                $user->setBankAccount($formData->getBankAccount());
                $user->setPassword(md5($formData->getPassword()));
                $this->em->persist($user);
                $this->em->flush();
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}
