<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\User;
use App\Entity\Scategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class FichierType extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options): void
 {
 $builder
 ->add('fichier', FileType::class, array('label' => 'Fichier', 'mapped'=>false,'attr' => ['class'=>
'form-control'], 'label_attr' => ['class'=> 'fw-bold'],'constraints' => [
 new File([
 'maxSize' => '200k',
 'mimeTypes' => [
 'application/pdf',
 'application/x-pdf',
 'image/jpeg',
 'image/png',
 ],
 'mimeTypesMessage' => 'Le site accepte uniquement les fichiers PDF, PNG et JPG',
 ])
 ],))
 ->add('nomOriginal')
 ->add('nomServeur')
 ->add('dateEnvoi')
 ->add('extension')
 ->add('taille')
 ->add('user', EntityType::class, [
 'class' => User::class,
'choice_label' => 'id',
 ])
 ->add('scategories', EntityType::class, [
    'class' => Scategorie::class,
    'choices' => $options['scategories'],
    'choice_label' => 'titre',
    'expanded' => true,
    'multiple' => true,
    'label' => false, 'mapped' => false])
    ->add('ajouter', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ],
   'row_attr' => ['class' => 'text-center'],])
 ;
 }
 public function configureOptions(OptionsResolver $resolver): void
 {
 $resolver->setDefaults([
 'data_class' => Fichier::class,
 'scategories' => []
 ]);
 }
}