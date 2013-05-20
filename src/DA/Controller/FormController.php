<?php

namespace DA\Controller;

use Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;

class FormController
{
	public function index(Request $request,Application $app)
	{
		$builder = $app['form.factory']->createBuilder('form');
		$choices = array('choice a', 'choice b', 'choice c');

		$form = $builder
			->add(
			$builder->create('sub-form', 'form')
			->add('subformemail1', 'email', array(
			        'constraints' => array(new Assert\NotBlank(), new Assert\Email()),
			        'attr'        => array('placeholder' => 'email constraints'),
			        'label'       => 'A custom label : ',
			    ))
			->add('subformtext1', 'text')
			)
			->add('text1', 'text', array(
			    'constraints' => new Assert\NotBlank(),
			    'attr'        => array('placeholder' => 'not blank constraints')
			))
			->add('text2', 'text', array('attr' => array('class' => 'span1', 'placeholder' => '.span1')))
			->add('text3', 'text', array('attr' => array('class' => 'span2', 'placeholder' => '.span2')))
			->add('text4', 'text', array('attr' => array('class' => 'span3', 'placeholder' => '.span3')))
			->add('text5', 'text', array('attr' => array('class' => 'span4', 'placeholder' => '.span4')))
			->add('text6', 'text', array('attr' => array('class' => 'span5', 'placeholder' => '.span5')))
			->add('text8', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'disabled field')))
			->add('textarea', 'textarea')
			->add('email', 'email')
			->add('integer', 'integer')
			->add('money', 'money')
			->add('number', 'number')
			->add('password', 'password')
			->add('percent', 'percent')
			->add('search', 'search')
			->add('url', 'url')
			->add('choice1', 'choice',  array(
			    'choices'  => $choices,
			    'multiple' => true,
			    'expanded' => true
			))
			->add('choice2', 'choice',  array(
			    'choices'  => $choices,
			    'multiple' => false,
			    'expanded' => true
			))
			->add('choice3', 'choice',  array(
			    'choices'  => $choices,
			    'multiple' => true,
			    'expanded' => false
			))
			->add('choice4', 'choice',  array(
			    'choices'  => $choices,
			    'multiple' => false,
			    'expanded' => false
			))
			->add('language', 'language')
			->add('locale', 'locale')
			->add('timezone', 'timezone')
			->add('date', 'date')
			->add('datetime', 'datetime')
			->add('time', 'time')
			->add('birthday', 'birthday')
			->add('checkbox', 'checkbox')
			->add('file', 'file')
			->add('radio', 'radio')
			->add('password_repeated', 'repeated', array(
			    'type'            => 'password',
			    'invalid_message' => 'The password fields must match.',
			    'options'         => array('required' => true),
			    'first_options'   => array('label' => 'Password'),
			    'second_options'  => array('label' => 'Repeat Password'),
			))
			->getForm()
			;

		if ($request->isMethod('POST')) {
			$form->bind($request);
			if ($form->isValid()) {
				$app['session']->getFlashBag()->add('success', 'The form is valid');
			} else {
				$form->addError(new FormError('This is a global error'));
				$app['session']->getFlashBag()->add('info', 'The form is bind, but not valid');
			}
		}

		return $app['twig']->render('form.html.twig', array('form' => $form->createView()));

	}
}

