<?php

namespace App;

use Silex\Api\ControllerProviderInterface;
use Silex\Application as App;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ControllerProvider implements ControllerProviderInterface
{
    private $app;

    public function connect(App $app)
    {
        $this->app = $app;

        $app->error([$this, 'error']);

        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/', [$this, 'homepage'])
            ->bind('homepage');

        $controllers
            ->get('/login', [$this, 'login'])
            ->bind('login');

        $controllers
            ->get('/doctrine', [$this, 'doctrine'])
            ->bind('doctrine');

        $controllers
            ->match('/form', [$this, 'form'])
            ->bind('form');

        $controllers
            ->get('/cache', [$this, 'cache'])
            ->bind('cache');

        return $controllers;
    }

    public function homepage(App $app)
    {
        $app['session']->getFlashBag()->add('warning', 'Warning flash message');
        $app['session']->getFlashBag()->add('info', 'Info flash message');
        $app['session']->getFlashBag()->add('success', 'Success flash message');
        $app['session']->getFlashBag()->add('danger', 'Danger flash message');

        return $app['twig']->render('index.html.twig');
    }

    public function login(App $app)
    {
        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.utils']->getLastAuthenticationError(),
            'username' => $app['security.utils']->getLastUsername(),
        ));
    }

    public function doctrine(App $app)
    {
        return $app['twig']->render('doctrine.html.twig', array(
            'posts' => $app['db']->fetchAll('SELECT * FROM post'),
        ));
    }

    public function form(App $app, Request $request)
    {
        $builder = $app['form.factory']->createBuilder(Type\FormType::class);

        $choices = array('choice a', 'choice b', 'choice c');

        $form = $builder
            ->add(
                $builder->create('sub-form', Type\FormType::class)
                    ->add('subformemail1', Type\EmailType::class, array(
                        'constraints' => array(new Assert\NotBlank(), new Assert\Email()),
                        'attr' => array('placeholder' => 'email constraints'),
                        'label' => 'A custom label : ',
                    ))
                    ->add('subformtext1', Type\TextType::class)
            )
            ->add('text1', Type\TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('placeholder' => 'not blank constraints'),
            ))
            ->add('text2', Type\TextType::class, array('attr' => array('class' => 'span1', 'placeholder' => '.span1')))
            ->add('text3', Type\TextType::class, array('attr' => array('class' => 'span2', 'placeholder' => '.span2')))
            ->add('text4', Type\TextType::class, array('attr' => array('class' => 'span3', 'placeholder' => '.span3')))
            ->add('text5', Type\TextType::class, array('attr' => array('class' => 'span4', 'placeholder' => '.span4')))
            ->add('text6', Type\TextType::class, array('attr' => array('class' => 'span5', 'placeholder' => '.span5')))
            ->add('text8', Type\TextType::class, array('disabled' => true, 'attr' => array('placeholder' => 'disabled field')))
            ->add('textarea', Type\TextareaType::class)
            ->add('email', Type\EmailType::class)
            ->add('integer', Type\IntegerType::class)
            ->add('money', Type\MoneyType::class)
            ->add('number', Type\NumberType::class)
            ->add('password', Type\PasswordType::class)
            ->add('percent', Type\PercentType::class)
            ->add('search', Type\SearchType::class)
            ->add('url', Type\UrlType::class)
            ->add('choice1', Type\ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('choice2', Type\ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => false,
                'expanded' => true,
            ))
            ->add('choice3', Type\ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => true,
                'expanded' => false,
            ))
            ->add('choice4', Type\ChoiceType::class, array(
                'choices' => $choices,
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('country', Type\CountryType::class)
            ->add('language', Type\LanguageType::class)
            ->add('locale', Type\LocaleType::class)
            ->add('timezone', Type\TimezoneType::class)
            ->add('date', Type\DateType::class)
            ->add('datetime', Type\DateTimeType::class)
            ->add('time', Type\TimeType::class)
            ->add('birthday', Type\BirthdayType::class)
            ->add('checkbox', Type\CheckboxType::class)
            ->add('file', Type\FileType::class)
            ->add('radio', Type\RadioType::class)
            ->add('password_repeated', Type\RepeatedType::class, array(
                'type' => Type\PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('required' => true),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('submit', Type\SubmitType::class)
            ->getForm()
        ;

        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $app['session']->getFlashBag()->add('success', 'The form is valid');
            } else {
                $form->addError(new FormError('This is a global error'));
                $app['session']->getFlashBag()->add('info', 'The form is bound, but not valid');
            }
        }

        return $app['twig']->render('form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function cache(App $app)
    {
        $response = new Response($app['twig']->render('cache.html.twig', array('date' => date('Y-M-d h:i:s'))));
        $response->setTtl(10);

        return $response;
    }

    public function error(\Exception $e, Request $request, $code)
    {
        if ($this->app['debug']) {
            return;
        }

        switch ($code) {
            case 404:
                $message = 'The requested page could not be found.';
                break;
            default:
                $message = 'We are sorry, but something went terribly wrong.';
        }

        return new Response($message, $code);
    }
}
