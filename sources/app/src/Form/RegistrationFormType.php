<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, [
				'label' => 'Identifiant',
				'attr' => [
					'placeholder' => 'identifiant',
				]
			])
			->add('email', TextType::class, [
				'label' => 'E-mail',
				'attr' => [
					'placeholder' => 'e-mail',
				]
			])
			->add('plainPassword', RepeatedType::class, [
				// instead of being set onto the object directly,
				// this is read and encoded in the controller
				'type' => PasswordType::class,
				'invalid_message' => 'Les mots de passe doivent être identiques.',
				'options' => [
					'constraints' => [
						new NotBlank([
							'message' => 'Vous devez entrer un mot de passe.',
						]),
						new Length([
							'min' => 8,
							'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} charactères',
							// max length allowed by Symfony for security reasons
							'max' => 4096,
						]),
					],
				],
				'first_options' => ['label' => 'Mot de passe'],
				'second_options' => ['label' => 'Confirmer le mot de passe'],
				'required' => true,
				'mapped' => false,
			])
			->add('agreeTerms', CheckboxType::class, [
				'mapped' => false,
				'constraints' => [
					new IsTrue([
						'message' => 'Vous devez accepter les conditions d\'utilisation.',
					]),
				],
				'label' => 'Accepter les conditions d\'utilisation',
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
