<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('Default/index.html.twig', ['projects' => $projects]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param int $id
     *
     * @return Response
     */
    public function project(EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->isGranted('ROLE__USER')) {
            throw $this->createAccessDeniedException();
        }
        $project = $entityManager->getRepository(Project::class)->find($id);

        $form = $this->createFormBuilder(
            null,
            [
                'action' => $this->generateUrl('anaxago_create_investment')
            ]
        )->add('amount', NumberType::class, ['label' => 'Montant à investir :'])
            ->add('submit', SubmitType::class, ['label' => 'Continuer'])
            ->getForm();

        return $this->render('Default/project.html.twig', ['project' => $project, $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createInvestment(Request $request): Response
    {
        if ($this->isGranted('ROLE__USER')) {
            throw $this->createAccessDeniedException();
        }

        $request->request->get('amount');

        return $this->render('Default/createInvestment.html.twig', ['amount' => $request->request->get('amount')]);
    }
}
