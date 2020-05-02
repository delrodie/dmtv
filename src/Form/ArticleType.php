<?php

namespace App\Form;

use App\Entity\Article;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"le Titre de la video", "autocomplete"=>"off"]])
            ->add('description', TextareaType::class,['attr'=>['class'=>'form-control', 'placeholder'=>'La description de la video', 'rows'=>9]])
            ->add('lien', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Le lien de la video", 'autocomplete'=>"off"]])
            ->add('auteur', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"L'auteur de la video", 'autocomplete'=>"off"]])
            ->add('tag', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>'Les mots clés']])
            ->add('rubrique', EntityType::class,[
                'attr'=>['class'=>'form-control custom-select rubrique-select', 'width'=>"100%"],
                'class'=> 'App\Entity\Rubrique',
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' => 'libelle',
                'label' => 'projet.labelDomaine',
                'required'=>true,
                'multiple' => true
            ])
            ->add('img480', FileType::class,[
                'mapped'=>false,
                'required' =>false,
                'constraints'=>[
                    new File([
                        'maxSize' => '100000k',
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'attr'=>['onchange'=>'getFileInfo()'],
                'label' => "Photo d'illustration "
            ])
            ->add('isValid', CheckboxType::class,['required'=>false])
            ->add('IsSlide', CheckboxType::class,['required'=>false])
            //->add('createdAt')
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
