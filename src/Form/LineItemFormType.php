<?php 

namespace App\Form;

use App\Entity\LineItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LineItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Ajoutez les champs ici
            ->add('lineitemId')
            ->add('orderNumber')
            ->add('name')
            ->add('geo')
            ->add('bookingType')
            ->add('kpi')
            ->add('language')
            ->add('volume')
            ->add('goal')
            ->add('unit')
            ->add('budget')
            ->add('format')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LineItem::class,
        ]);
    }
}

?>