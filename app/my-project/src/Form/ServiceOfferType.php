<?php

namespace App\Form;

use App\Entity\RateService;
use App\Entity\ServiceOffer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(message: 'Поле не должно быть пустым.'),
                    new Email(message: 'Неправильный формат email.'),
                ]
            ])
            ->add('rateService', EntityType::class, [
                'class' => RateService::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите услугу.',
                'choice_attr' => fn (RateService $rateService) => ['data-cost' => $rateService->getCost()],
                'constraints' => [
                    new NotBlank(message: 'Поле не должно быть пустым.'),
                    new Type(type: RateService::class, message: 'Не найден Id указанной услуги.')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceOffer::class,
        ]);
    }
}
