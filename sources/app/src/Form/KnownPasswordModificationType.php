<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class KnownPasswordModificationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('oldPassword', PasswordType::class, [
				'mapped' => false,
				'required' => true,
				'label' => 'Saisir votre ancien mot de passe',
				'constraints' => [
					new UserPassword([
						'message' => 'Votre mot de passe est incorrect.',
					])
				],
			])
			->add('password', RepeatedType::class, [
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
				'first_options' => ['label' => 'Entrer le nouveau mot de passe'],
				'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
				'required' => true,
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
