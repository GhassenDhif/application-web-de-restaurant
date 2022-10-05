<?php

namespace App\Form;

use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use App\Entity\Partenaire;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;




class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom')
        ->add('datef')
        ->add('local')
        ->add('image',FileType::class,['data_class' => NULL, 'constraints' => [
            new File([
                'maxSize' => '9000k',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                   
                ],
                'mimeTypesMessage' => 'Please upload a valid image',
            ])
        ]])
        ->add('descri',TextareaType::class)
        ->add('categorie',EntityType::class,['class'=> Categorie::class,
        'choice_label'=> 'type',
        'label' => 'type'

    ])

      // ->add('captchaCode', CaptchaType::class) ;


    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
