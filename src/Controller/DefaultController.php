<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
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
     *
     * @return Response
     */
    public function stats(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $projects = $entityManager->getRepository(Project::class)->findAll();

        $accessLogs = file_get_contents('/var/log/apache2/anaxago-starter/access.log');
        $accessStats = [];
        foreach (explode("\n", $accessLogs) as $accessLog) {
            if (!$accessLog) {
                continue;
            }
            $data = explode(' ', $accessLog)[3];
            $start = null;
            $end = null;
            foreach(str_split($data) as $index => $char) {
                if ($char === '/') {
                    if ($start !== null) {
                        $end = $index;
                    }
                    if ($start === null) {
                        $start = $index;
                    }
                }
            }
            $start -=- 1;
            $data = substr($data, $start, $end-$start);
            if (in_array($data, array_keys($accessStats))) {
                $accessStats[$data] = ++$accessStats[$data];
            } else {
                $accessStats[$data] = 1;
            }
        }
        dump($accessStats);

        return $this->render('Default/stats.html.twig', ['users' => count($users), 'projects' => $projects, 'accessStats' => $accessStats]);
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
