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
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class VisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Votre prénom est trop court',
                    'maxMessage' => 'Votre prénom est trop long'
                ])
            ])
            ->add('lastName', TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ],
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage' => 'Votre prénom est trop court',
                    'maxMessage' => 'Votre prénom est trop long'
                ])
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'birthday',
                    'placeholder' => 'Date de naissance'
                ],
                'constraints' => new DateTime([
                    'message' => 'La date de naissance n\'est pas valide'
                ]),
                'years' => range(date('Y')-150, date('Y')),
                'label' => false,
                'html5' => false
            ])
            ->add('country', CountryType::class,[
                'label' => false,
                'placeholder' => 'Pays'
            ])
            ->add('reduce', CheckboxType::class, [
                'required' => false,
                'label' => 'Réduction*',
                'constraints' => new Type([
                    'type' => 'bool',
                    'message' => 'Veuillez cocher convenablement si vous avez une réduction'
                ])
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