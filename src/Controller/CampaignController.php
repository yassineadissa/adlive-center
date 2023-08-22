<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\LineItem;
use App\Form\CampaignFormType;
use App\Form\CampaignEndFormType;
use App\Repository\CampaignRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CampaignController extends BaseController
{
    public function __construct(private CampaignRepository $CampaignRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/admin/campaign', name: 'app_admin_campaigns')]
    #[IsGranted('ROLE_WRITER')]
    public function users(): Response
    {
        $campaigns = $this->CampaignRepository->findAll();

        return $this->render('admin/campaign/campaign.html.twig', ['campaigns' => $campaigns]);
    }

    #[Route(path: '/admin/campaign/new', name: 'app_admin_new_campaign')]
    #[IsGranted('ROLE_WRITER')]
    public function newCampaign(Request $request)
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignFormType::class, $campaign, ['request' => $request,]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Campaign $campaign */
            $campaign = $form->getData();
            $campaign->setConfirmed(true)
                ->setDeleted(false);
            $this->entityManager->persist($campaign);
            // Récupérer les données de lineItem du champ caché
            $lineItemsJson = $form->get('lineItemsJson')->getData();
            $lineItemsData = json_decode($lineItemsJson, true);
            if (!is_array($lineItemsData)) {
                throw new \Exception("Invalid line items data");
            }
            
            $lineNumber = 1;
            foreach ($lineItemsData as $itemData) {
                $lineItem = new LineItem();
                
                // Définir lineitemId = campaignId + "-" + lineNumber
                $lineItemId = $campaign->getCampaignId() . "-" . $lineNumber;
                $lineItem->setLineItemId($campaign->getCampaignId()."-".$lineNumber);
                
                // Définir name = Agency [campaignName - format - geo] - campaignId
                $name = $campaign->getAgency() . " [" . $campaign->getCampaignName() . " - " . $campaign->getFormat() . " - " . $itemData['geo'] . "] - " . $campaign->getCampaignId();
                $lineItem->setName($name);
                
                if ($lineItem !== null) {
                    $lineItem->setGeo($itemData['geo']);
                }
                $lineItem->setOrderNumber($itemData['order number']);
                $lineItem->setBookingType($itemData['booking type']);
                $lineItem->setKpi($itemData['kpi']);
                $lineItem->setLanguage($itemData['language']);
                $lineItem->setVolume($itemData['volume']);
                $lineItem->setGoal($itemData['goal']);
                $lineItem->setUnit($itemData['unit']);
                $lineItem->setBudget($itemData['budget']);
                $lineItem->setFormat($itemData['format']);
                $startDateConverted = new \DateTime($itemData['start date']);
                $endDateConverted = new \DateTime($itemData['end date']);
                $lineItem->setStartDate($startDateConverted);
                $lineItem->setEndDate($endDateConverted);
                $lineItem->setCreditNote($itemData['credit note']);

                $lineItem->setCampaign($campaign);
                $this->entityManager->persist($lineItem);
                $campaign->addLineItem($lineItem);
                $lineNumber++;
            }

            $campaign->setStatus(0);
            $this->entityManager->flush();
            $this->addFlash('success', 'Campaign Added');

            return $this->redirectToRoute('app_admin_campaigns');
        }
        // dump($form);

        return $this->render('admin/campaign/campaignform.html.twig', ['campaignForm' => $form]);
    }

    #[Route(path: '/admin/campaign/edit/{id}', name: 'app_admin_edit_campaign')]
    #[IsGranted('ROLE_WRITER')]
    public function editCampaign(Campaign $campaign, Request $request)
    {
        $form = $this->createForm(CampaignFormType::class, $campaign);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $campaign->setConfirmed(true)
                ->setDeleted(false);
            $this->entityManager->persist($campaign);
            $this->entityManager->flush();
            $this->addFlash('success', 'Campaign Edited');

            return $this->redirectToRoute('app_admin_campaigns');
        }
        dump($form);

        return $this->render('admin/campaign/campaignform.html.twig', ['campaignForm' => $form]);
    }

    #[Route(path: '/admin/campaign/end/{id}', name: 'app_admin_end_campaign')]
    #[IsGranted('ROLE_WRITER')]
    public function endCampaign(Campaign $campaign, Request $request)
    {
        $form = $this->createForm(CampaignEndFormType::class, $campaign);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $campaign->setConfirmed(true)
                ->setDeleted(false);
            $this->entityManager->persist($campaign);
            $this->entityManager->flush();
            $this->addFlash('success', 'End of Campaign Edited');

            return $this->redirectToRoute('app_admin_campaigns');
        }

        dump($form);

        return $this->render('admin/campaign/campaignform.html.twig', ['campaignForm' => $form->createView()]);
    }

    #[Route(path: '/admin/campaign/changevalidite/{id}', name: 'app_admin_changevalidite_campaign', methods: ['post'])]
    #[IsGranted('ROLE_WRITER')]
    public function activate(Campaign $campaign): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $campaign = $this->CampaignRepository->changeValidite($campaign);

        return $this->json(['message' => 'success', 'value' => $campaign->getValid()]);
    }

    #[Route(path: '/admin/campaign/delete/{id}', name: 'app_admin_delete_campaign')]
    #[IsGranted('ROLE_WRITER')]
    public function delete(Campaign $campaign): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $campaign = $this->CampaignRepository->delete($campaign);

        return $this->json(['message' => 'success', 'value' => $campaign->getDeleted()]);
    }

    #[Route(path: '/admin/campaign/groupaction', name: 'app_admin_groupaction_campaign')]
    #[IsGranted('ROLE_WRITER')]
    public function groupAction(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $action = $request->get('action');
        $ids = $request->get('ids');
        $campaigns = $this->CampaignRepository->findBy(['id' => $ids]);
        if ($action == 'desactiver' && $this->isGranted('ROLE_EDITORIAL')) {
            foreach ($campaigns as $campaign) {
                $campaign->setConfirmed(false);
                $this->entityManager->persist($campaign);
            }
        } elseif ($action == 'activer' && $this->isGranted('ROLE_EDITORIAL')) {
            foreach ($campaigns as $campaign) {
                $campaign->setConfirmed(true);
                $this->entityManager->persist($campaign);
            }
        } elseif ($action == 'supprimer' && $this->isGranted('ROLE_EDITORIAL')) {
            foreach ($campaigns as $campaign) {
                $campaign->setDeleted(true);
                $this->entityManager->persist($campaign);
            }
        } else {
            return $this->json(['message' => 'error']);
        }
        $this->entityManager->flush();

        return $this->json(['message' => 'success', 'nb' => count($campaigns)]);
    }

    // TODO: review role/access control for writers
    // TODO: Blog table add needed fields
}
