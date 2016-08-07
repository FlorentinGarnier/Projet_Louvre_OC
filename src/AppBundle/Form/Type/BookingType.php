<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 12/07/2016
 * Time: 23:05
 */

namespace AppBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visit_date', DateType::class, [
                'widget' => 'single_text',
                'constraints' => new DateTime([
                    'message' => 'La date de visite n\'est pas valide'
                ]),
                'html5' => false
            ])
            ->add('half_day', CheckboxType::class,[
                'required' => false,
                'label' => 'Demi-journée',
            ])
            ->add('visitors', CollectionType::class, [
                'by_reference' => false,
                'entry_type' => VisitorType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('email', EmailType::class, [
                'constraints' => new Email([
                    'message' => 'Veuillez renseigner une adresse email valide'
                    ]
                )
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Booking'
        ]);
    }
}