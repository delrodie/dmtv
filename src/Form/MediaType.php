<?php

namespace App\Form;

use App\Entity\Media;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->articleID = $options['articleID'];
        $articleID = $this->articleID;

        $builder
            ->add('img1920', FileType::class,[
                'mapped'=>false,
                'required' =>true,
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
                'label' => "Photo carousel x1920"
            ])
            ->add('img250', FileType::class,[
                'mapped'=>false,
                'required' =>true,
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
                'label' => "Photo loupe x250"
            ])
            ->add('article', EntityType::class,[
                'attr'=>['class'=>'form-control custom-select rubrique-select', 'width'=>"100%"],
                'class'=> 'App\Entity\Article',
                'query_builder' => function(EntityRepository $er)use($articleID){
                    return $er->getArticle($articleID);
                },
                'choice_label' => 'titre',
                'label' => 'Article',
                'required'=>true,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            "articleID" => null
        ]);
    }
}
