<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 13/07/2016
 * Time: 10:13
 */

namespace AppBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class VisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'birthday'],
                'html5' => false,
                'constraints' => new DateTime([
                    'message' => 'La date de naissance n\'est pas valide'
                ]),
                'years' => range(1900, date('Y')),
                'label' => 'Date de naissance',
            ])
            ->add('reduce', CheckboxType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Visitor'
        ]);
    }
}