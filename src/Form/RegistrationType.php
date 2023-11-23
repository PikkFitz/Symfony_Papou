<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir votre prénom',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])

            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir votre nom',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])

            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir votre adresse mail',
                    'minlength' => '2',
                    'maxlength' => '180'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre adresse mail',
                    ]),
                    new Assert\Email([
                        'message' => 'Erreur adresse mail invalide',
                    ]),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])

            ->add('gender', ChoiceType::class, [
                'label' => 'Civilité',
                'label_attr' => [
                    'class' => 'form-label mt-4 me-3'
                ],
                'choices'  => [
                    'Femme' => 'woman',
                    'Homme' => 'man',
                    'Autre' => 'other',
                    'Je ne souhaite pas me prononcer' => 'not specified',
                ],
                'invalid_message' => 'Civilité non valide'
            ])

            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'label_attr' => [
                    'class' => 'form-label mt-4 me-3'
                ],
                'widget' => 'single_text',
                'input'  => 'datetime',
                'required' => false,
                'by_reference' => true, //  /!\  Nécessaire pour pouvoir entrer une date nulle /!\
                'invalid_message' => 'Date de naissance non valide'
            ])
            
            ->add('plainPassword', RepeatedType::class, [    // RepeatedType : Pour avoir un deuxième champ pour la confirmation du mdp (donc first_options et second_options)
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Choisir un mot de passe',
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez renseigner votre mot de passe',
                        ])
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Confirmer votre mot de passe',
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez confirmer votre mot de passe',
                        ])
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas'  // Message si les mdp 1 et 2 ne sont pas identiques
            ])

            ->add('agreeTerm', CheckboxType::class, [
                'label'    => 'J\'accepte les conditions générales d\'utilisation',
                'label_attr' => [
                    'class' => 'form-label mt-4 me-3'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cochez la case et accepter les conditions générales',
                    ]),
                ]
            ])
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Valider votre inscription'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
