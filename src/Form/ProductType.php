<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Url;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 3,
                            'max' => 255
                        ])
                    ]
                ]
            )->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3
                    ])
                ]
            ])
            ->add('pictureUrl', UrlType::class, [
                'help' => 'Enter an url with protocol <<https>>',
                'constraints' => [
                    new NotBlank(),
                    new Url([
                        'protocols' => ['https'],
                        'message'   => 'You must enter an url with protocol <<https>>'
                    ])
                ]
            ])
            ->add('price', NumberType::class, [
                'scale'       => 2,
                'html5'       => true,
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ]
            ])
            ->add('category', EntityType::class, [
                'class'         => Category::class,
                'multiple'      => false,
                'required'      => true,
                'choice_label'  => 'name',
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
