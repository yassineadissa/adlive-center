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





class CampaignEndFormType extends AbstractType
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
           // Redirection to campaign List          
         
        }else {
            $builder
            ->add('campaignId', TextType::class, [
                'required' => true,
                'attr' => [
                            'readonly' => true
                            ],
            ])
            ->add('campaignName', TextType::class, [
              'required' => true, 
              'attr' => [
                'readonly' => true
                ],
            ])
            ->add('pipedriveId', TextType::class, [
               
                'required' => false, 
            ])

            ->add('globalBudget', TextType::class, [
                'label' => 'Global Budget',
                'required' => false,
            ])
                
            ->add('boFile', VichFileType::class, [
                'label' => 'Bo',
                'required' => false,
            ])
            ->add('briefFile', VichFileType::class, [
                'label' => 'Brief',
                'required' => false,
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

// class StringToFileTransformer implements DataTransformerInterface
// {
//     public function transform($filePath)
//     {
//         if (null === $filePath) {
//             return null;
//         }

//         return new File($filePath);
//     }

//     public function reverseTransform($file)
//     {
//         if ($file === null) {
//             return null;
//         }
    
//         if ($file instanceof File) {
//             return $file->getRealPath();
//         }

//         throw new TransformationFailedException();
//     }
// }
