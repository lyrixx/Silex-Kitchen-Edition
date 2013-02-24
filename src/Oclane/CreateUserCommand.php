<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oclane;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

//use UserProvider;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateUserCommand extends Command
{
    private $app;

    public function __construct($name = null, $app = null)
    {
        parent::__construct($name);
        $this->app = $app;
    }

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('admin', null, InputOption::VALUE_NONE, 'Give the user the ROLE_ADMIN'),
            ))
            ->setHelp(<<<EOT
The <info>user:create</info> command creates a user:

  <info>php app/console user:create matthieu</info>

This interactive shell will ask you for a password.

You can alternatively specify the password as the second argument:

  <info>php app/console user:create matthieu mypassword</info>

You can create an admin user via the admin flag:

  <info>php app/console user:create admin --admin</info>

EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $username   = $input->getArgument('username');
        $password   = $input->getArgument('password');
        $admin      = $input->getOption('admin');

        $encoded = $this->app['security.encoder.digest']->encodePassword($password, '');

        //$manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        //$manipulator->create($username, $password, $email, !$inactive, $superadmin);

        $this->app['db']->insert('users', array(
            'username' => $username,
            'password' => $encoded,
            'roles' => $admin ? 'ROLE_ADMIN' :  'ROLE_USER'
    ));

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a username:',
                function($username) {
                    if (empty($username)) {
                        throw new \Exception('Username can not be empty');
                    }

                    return $username;
                }
            );
            $input->setArgument('username', $username);
        }

/*        if (!$input->getArgument('email')) {
            $email = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an email:',
                function($email) {
                    if (empty($email)) {
                        throw new \Exception('Email can not be empty');
                    }

                    return $email;
                }
            );
            $input->setArgument('email', $email);
        }
*/
        if (!$input->getArgument('password')) {
            $password = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a password:',
                function($password) {
                    if (empty($password)) {
                        throw new \Exception('Password can not be empty');
                    }

                    return $password;
                }
            );
            $input->setArgument('password', $password);
        }
    }
}
