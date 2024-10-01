<?php
namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuizResult;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuizController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $questions = $entityManager->getRepository(Question::class)->findAll();
        if (!$questions) {
            throw $this->createNotFoundException('We don\'t have questions for you');
        }

        return $this->render('quiz/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/result', methods: ['POST'])]
    public function result(Request $request, EntityManagerInterface $entityManager): Response
    {
        $answers = $request->get('answer');
        //TODO make validator
        if (!$answers || !is_array($answers)) {
            throw new BadRequestHttpException('Answers did\'t send');
        }

        $questions = $entityManager->getRepository(Question::class)->getModelsAsAssociativeArray(array_keys($answers));
        if (!$questions) {
            throw new BadRequestHttpException('Answers are corrupt');
        }

        $result = [];
        foreach ($answers as $questionId => $answer) {
            if (!isset($questions[$questionId])) {
                throw new BadRequestHttpException('Don\'t have answers for all questions');
            }
            $question = $questions[$questionId];
            $result[$question->getTitle()] = array_diff(array_keys($answer), $question->getAnswers()) ? false : true;
        }

        $quizResult = new QuizResult();
        $quizResult->setUserName($request->get('name'));
        $quizResult->setData($result);
        $quizResult->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($quizResult);
        $entityManager->flush();

        return $this->render('quiz/result.html.twig', [
            'score' => count(array_filter($result)),
            'quizResult' => $quizResult,
        ]);
    }
}