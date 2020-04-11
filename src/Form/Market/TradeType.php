<?php

namespace App\Form\Market;

use App\Entity\Item;
use App\Entity\Trade;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', IntegerType::class)
            ->add('dodoCode', TextType::class, [
                'help' => 'Vous pourrez le renseignez plus tard, mais pour ouvrir votre île, ce champs est requis',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'non' => false,
                    'oui' => true
                ],
                'label' => 'Activer maintenant'
            ])
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'by_reference' => false,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('description', CKEditorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trade::class,
        ]);
    }
}
