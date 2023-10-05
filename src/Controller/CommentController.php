<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dovetailing;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    public function __construct(private readonly  EntityManagerInterface $em)
    {
    }

    /**
     * @throws NotSupported
     */
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        $comments = $this->em->getRepository(Comment::class)->findAll();
        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/comment/new/{dovetailing}', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Dovetailing $dovetailing, Request $request): Response
    {
        $user = $this->getUser();

        // if($request->isxmlhttprequest()) {
        if ($request->isMethod('POST')){
            $commentTxt = $request->get('comment');
            $emailTxt = $request->get('email');
            $idDovetailing = $request->get("dovetailing_id");

            //dd($commentTxt, $emailTxt, $idDovetailing);

            $comment = new Comment();
            $comment->setText($commentTxt);
            $dovetailing = $this->em->getRepository(Dovetailing::class)->find($idDovetailing);
            $comment->setDovetailing($dovetailing);
            $this->em->persist($comment);
            $this->em->flush();

            return $this->json(['status' => 'success']);
        }

        return $this->render('comment/new.html.twig', [
            'user' => $user,
            'dovetailing' => $dovetailing
        ]);
    }
    
}
