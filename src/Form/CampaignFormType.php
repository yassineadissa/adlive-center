<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Repository\CampaignRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;





class CampaignFormType extends AbstractType
{
    public function __construct(private CampaignRepository $campaignRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $idCamp =  0; $nb = 0;
        // $pipedriveId = $options['request']->query->get('pipedriveId', '');
        $pipedriveId =  '';
        if (isset($options["data"])){
            $idCamp = $options["data"]->getId() ;
            $nb = $this->campaignRepository->countChildCampaigns($idCamp);

        }

        if ($nb == 0){
            $builder
            ->add('campaignId', TextType::class, [
                'data' => 'adlc' . sprintf("%04d", rand()),
                'attr' => [
                            'readonly' => true
                            ],
            ])
            ->add('campaignName', TextType::class, [
                 'required' => true, 
            ])
            ->add('pipedriveId', TextType::class, [
                'data' => $pipedriveId,
                'required' => false, 
            ])
            ->add('agency', ChoiceType::class, [
                'choices' => [
                    'Accuen Mena FZ LLC' => 'Accuen Mena FZ LLC',
                    'Advermatic LLC' => 'Advermatic LLC',
                    'Carat Middle East FZ LLC' => 'Carat Middle East FZ LLC',
                    'Digital Venture FZ, LLC' => 'Digital Venture FZ, LLC',
                    'Fusion 5' => 'Fusion 5',
                    'GroupM Mena FZ LLC' => 'GroupM Mena FZ LLC',
                    'Havas Media Middle East FZ-LLC' => 'Havas Media Middle East FZ-LLC',
                    'Havas Programmatic Hub Ltd' => 'Havas Programmatic Hub Ltd',
                    'Hearts & Science' => 'Hearts & Science',
                    'MMP Worldwide DMCC' => 'MMP Worldwide DMCC',
                    'One Eleven' => 'One Eleven',
                    'Optimum Media Direction' => 'Optimum Media Direction',
                    'PHD FZ LLC' => 'PHD FZ LLC',
                    'Publicis Media FZ LLC' => 'Publicis Media FZ LLC',
                    'Publiscreen Media FZ LLC' => 'Publiscreen Media FZ LLC',
                    'Resolution' => 'Resolution',
                    'Ripply LLC' => 'Ripply LLC',
                    'Share IT' => 'Share IT',
                    'The Digital Tree FZ LLC' => 'The Digital Tree FZ LLC',
                    'Media International Advertising FZ-LLC' => 'Media International Advertising FZ-LLC',
                    'Media Plus' => 'Media Plus',
                    'Direct client' => 'Direct client',
                    'New agency ...' => 'New agency ...',
                ],
            ])
            ->add('advertiser', TextType::class, [
                'label' => 'Advertiser',
                'required' => false,
            ])
            ->add('bookingType', ChoiceType::class, [
                'choices' => [
                    'Direct' => 'Direct',
                    'Programmatic' => 'Programmatic',
                ],
                'label' => 'Booking Type',
                'required' => true,
                'attr' => ['id' => 'bookingType'],
            ])
            
            ->add('globalBudget', TextType::class, [
                'label' => 'Global Budget',
                'required' => false,
            ])
            ->add('format', ChoiceType::class, [
                'label' => 'Format',
                'choices' => [
                    'Display' => 'Display',
                    'Video' => 'Video',
                    'Display & Video' => 'Display & Video',
                ],
            ])
            ->add('markets', TextType::class, [
                'label' => 'Markets',
                'required' => false,
            ])
            ->add('kpis', TextType::class, [
                'label' => 'Targeting, Audience, KPIs',
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'required' => false,
                'attr' => [
                    'class' => 'date',
                    'type' => 'date'   
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'required' => false,
                'attr' => [
                    'class' => 'date',
                    'type' => 'date'   
                ],
            ])
            
            ->add('boFile', VichFileType::class, [
                'label' => 'Bo',
                'required' => false,
            ])
            ->add('briefFile', VichFileType::class, [
                'label' => 'Brief',
                'required' => false,
            ])
            
            ->add('seatId', TextType::class, [
                'label' => 'Seat ID',
                'required' => false,
            ])
            ->add('agencyDsp', ChoiceType::class, [
                'choices' => [
                    'DV360' => 'DV360',
                    'TTD' => 'TTD',
                    'Verizon' => 'Verizon',
                    'BidCore' => 'BidCore',
                    'MediaMath' => 'MediaMath',
                    'Adelphic' => 'Adelphic',
                    'Bidtellect' => 'Bidtellect',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Under review' => 0,
                    'Agency confirmed' => 1,
                    'Ready to go' => 2,
                    'Running' => 3,
                    'Aborted' => 4,
                    'Complete' => 5,
                ],
            ])
            ->add('inclusionList', ChoiceType::class, [
                'choices' => [
                    'OMG Web Inclusion List_MENA' => 'OMG Web Inclusion List_MENA',
                    'OMG Web Inclusion List_INTERNATIONAL' => 'OMG Web Inclusion List_INTERNATIONAL',
                    'OMG App Inclusion List_MENA' => 'OMG App Inclusion List_MENA',
                    'OMG App Inclusion List_INTERNATIONAL' => 'OMG App Inclusion List_INTERNATIONAL',
                    'New list' => 'New list',
                ],
            ])

            

            // ->add('confirmed', CheckboxType::class, [
            //     'label'    => 'Confirmed',
            //     'required' => false,
            // ])
            ->add('LineItemsFile', VichFileType::class, [
                'label' => 'LineItems',
                'required' => false,
            ])
            // ->add('lineItems', CollectionType::class, [
            //     'entry_type' => LineItemFormType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
            ->add('lineItemsJson', HiddenType::class, [
                'mapped' => false,
            ]);

            
         
        }else {
            $builder
            ->add('campaignId', TextType::class, [
               
                'attr' => [
                            'readonly' => true
                            ],
            ])
            ->add('campaignName', TextType::class, [
              'required' => true, 
            ])
            ->add('pipedriveId', TextType::class, [
               
                'required' => false, 
            ])
            ->add('agency', ChoiceType::class, [
                'choices' => [
                    'Accuen Mena FZ LLC' => 'Accuen Mena FZ LLC',
                    'Advermatic LLC' => 'Advermatic LLC',
                    'Carat Middle East FZ LLC' => 'Carat Middle East FZ LLC',
                    'Digital Venture FZ, LLC' => 'Digital Venture FZ, LLC',
                    'Fusion 5' => 'Fusion 5',
                    'GroupM Mena FZ LLC' => 'GroupM Mena FZ LLC',
                    'Havas Media Middle East FZ-LLC' => 'Havas Media Middle East FZ-LLC',
                    'Havas Programmatic Hub Ltd' => 'Havas Programmatic Hub Ltd',
                    'Hearts & Science' => 'Hearts & Science',
                    'MMP Worldwide DMCC' => 'MMP Worldwide DMCC',
                    'One Eleven' => 'One Eleven',
                    'Optimum Media Direction' => 'Optimum Media Direction',
                    'PHD FZ LLC' => 'PHD FZ LLC',
                    'Publicis Media FZ LLC' => 'Publicis Media FZ LLC',
                    'Publiscreen Media FZ LLC' => 'Publiscreen Media FZ LLC',
                    'Resolution' => 'Resolution',
                    'Ripply LLC' => 'Ripply LLC',
                    'Share IT' => 'Share IT',
                    'The Digital Tree FZ LLC' => 'The Digital Tree FZ LLC',
                    'Media International Advertising FZ-LLC' => 'Media International Advertising FZ-LLC',
                    'Media Plus' => 'Media Plus',
                    'Direct client' => 'Direct client',
                    'New agency ...' => 'New agency ...',
                ],
            ])
            ->add('advertiser', TextType::class, [
                'label' => 'Advertiser',
                'required' => false,
            ])
            ->add('bookingType', ChoiceType::class, [
                'choices' => [
                    'Direct' => 'Direct',
                    'Programmatic' => 'Programmatic',
                ],
                'label' => 'Booking Type',
                'required' => true,
                'attr' => ['id' => 'bookingType'],
            ])
            
            ->add('globalBudget', TextType::class, [
                'label' => 'Global Budget',
                'required' => false,
            ])
            ->add('format', ChoiceType::class, [
                'label' => 'Format',
                'choices' => [
                    'Display' => 'Display',
                    'Video' => 'Video',
                    'Display & Video' => 'Display & Video',
                ],
            ])
            ->add('markets', TextType::class, [
                'label' => 'Markets',
                'required' => false,
            ])
            ->add('kpis', TextType::class, [
                'label' => 'Targeting, Audience, KPIs',
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'required' => false,
                'attr' => [
                    'class' => 'date',
                    'type' => 'date'   
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'required' => false,
                'attr' => [
                    'class' => 'date',
                    'type' => 'date'   
                ],
            ])
            
            ->add('boFile', VichFileType::class, [
                'label' => 'Bo',
                'required' => false,
            ])
            ->add('briefFile', VichFileType::class, [
                'label' => 'Brief',
                'required' => false,
            ])
            
            ->add('seatId', TextType::class, [
                'label' => 'Seat ID',
                'required' => false,
            ])
            ->add('agencyDsp', ChoiceType::class, [
                'choices' => [
                    'DV360' => 'DV360',
                    'TTD' => 'TTD',
                    'Verizon' => 'Verizon',
                    'BidCore' => 'BidCore',
                    'MediaMath' => 'MediaMath',
                    'Adelphic' => 'Adelphic',
                    'Bidtellect' => 'Bidtellect',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Under review' => 0,
                    'Agency confirmed' => 1,
                    'Ready to go' => 2,
                    'Running' => 3,
                    'Aborted' => 4,
                    'Complete' => 5,
                ],
            ])
            ->add('inclusionList', ChoiceType::class, [
                'choices' => [
                    'OMG Web Inclusion List_MENA' => 'OMG Web Inclusion List_MENA',
                    'OMG Web Inclusion List_INTERNATIONAL' => 'OMG Web Inclusion List_INTERNATIONAL',
                    'OMG App Inclusion List_MENA' => 'OMG App Inclusion List_MENA',
                    'OMG App Inclusion List_INTERNATIONAL' => 'OMG App Inclusion List_INTERNATIONAL',
                    'New list' => 'New list',
                ],
            ])

            

            // ->add('confirmed', CheckboxType::class, [
            //     'label'    => 'Confirmed',
            //     'required' => false,
            // ])
            ->add('LineItemsFile', VichFileType::class, [
                'label' => 'LineItems',
                'required' => false,
            ])
            // ->add('lineItems', CollectionType::class, [
            //     'entry_type' => LineItemFormType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
            ->add('lineItemsJson', HiddenType::class, [
                'mapped' => false,
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
        $resolver->setDefined(['request']);
        $resolver->setAllowedTypes('request', Request::class);
    }
}

class StringToFileTransformer implements DataTransformerInterface
{
    public function transform($filePath)
    {
        if (null === $filePath) {
            return null;
        }

        return new File($filePath);
    }

    public function reverseTransform($file)
    {
        if ($file === null) {
            return null;
        }
    
        if ($file instanceof File) {
            return $file->getRealPath();
        }

        throw new TransformationFailedException();
    }
}
