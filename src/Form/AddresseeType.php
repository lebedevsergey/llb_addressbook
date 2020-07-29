<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Addressee;
use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;

/**
 * Defines the form used to edit an addressee
 */
class AddresseeType extends AbstractType
{
    private $countryRepository;
    private $cityRepository;

    public function __construct(CountryRepository $countryRepository, CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/tiff',
                            'image/bmp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname',
            ])
            ->add('phone', TelType::class, [
                'label' => 'label.phone',
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'label.birthday',
            ])
            ->add('house', NumberType::class, [
                'label' => 'label.building',
            ])
            ->add('street', TextType::class, [
                'label' => 'label.street',
            ])
            ->add('city', EntityType::class, [
                'label' => 'City',
                'class' => City::class,
                'placeholder' => 'Choose an option',
            ])
            ->add('country', EntityType::class, [
                'label' => 'Country',
                'class' => Country::class,
                'placeholder' => 'Choose an option',
            ])
            ->add('zip', TextType::class, [
                'label' => 'label.zip',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addressee::class,
        ]);
    }
}
