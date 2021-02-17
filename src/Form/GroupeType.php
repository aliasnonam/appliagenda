<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Groupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $builder->getData()->getId();
        if ($_SERVER["REQUEST_URI"] == "/groupe/create") {
            $builder
                ->add('nom', TextType::class, ['label' => 'Nom du groupe'])
                ->add('contacts', CollectionType::class, [
                    'label' => false,
                    'entry_type' => ContactType::class,
                    'entry_options' => ['label' => false],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ->add('sauvegarder', SubmitType::class, ['label' => 'Ajoutez Groupe', 'attr' => ['class' => 'btn btn-block btn-dark']]);
        }
        if ($_SERVER["REQUEST_URI"] == "/groupe/{$id}") {
            $builder
                ->add('nom', TextType::class, ['label' => false, 'attr' => ['class' => 'bg-dark text-light text-center', 'style' => 'width:50%; display:flex; margin:auto']]);
        }
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Groupe::class,
    //     ]);
    // }
}
