<?php

namespace App\Console;

use App\Application as App;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends SymfonyApplication
{
    private $silexApp;

    public function __construct(App $silexApp)
    {
        parent::__construct('Silex - Kitchen Edition', '0.1');

        $this->silexApp = $silexApp;
        $this->silexApp->boot();

        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', $this->silexApp->getEnv()));

        $this
            ->register('doctrine:schema:show')
            ->setDescription('Output schema declaration')
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $schema = require $this->silexApp->getRootDir().'/resources/db/schema.php';

                foreach ($schema->toSql($this->silexApp['db']->getDatabasePlatform()) as $sql) {
                    $output->writeln($sql.';');
                }
            })
        ;

        $this
            ->register('doctrine:schema:load')
            ->setDescription('Load schema')
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $schema = require $this->silexApp->getRootDir().'/resources/db/schema.php';

                foreach ($schema->toSql($this->silexApp['db']->getDatabasePlatform()) as $sql) {
                    $this->silexApp['db']->exec($sql.';');
                }
            })
        ;

        $this
            ->register('doctrine:database:drop')
            ->setName('doctrine:database:drop')
            ->setDescription('Drops the configured databases')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Set this parameter to execute this action')
            ->setHelp(
                <<<EOT
The <info>doctrine:database:drop</info> command drops the default connections
database:

<info>php app/console doctrine:database:drop</info>

The --force parameter has to be used to actually drop the database.

You can also optionally specify the name of a connection to drop the database
for:

<info>php app/console doctrine:database:drop --connection=default</info>

<error>Be careful: All data in a given database will be lost when executing
this command.</error>
EOT
            )
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $connection = $this->silexApp['db'];

                $params = $connection->getParams();

                $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);

                if (!$name) {
                    throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
                }

                if ($input->getOption('force')) {
                    // Only quote if we don't have a path
                    if (!isset($params['path'])) {
                        $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
                    }

                    try {
                        $connection->getSchemaManager()->dropDatabase($name);
                        $output->writeln(sprintf('<info>Dropped database for connection named <comment>%s</comment></info>', $name));
                    } catch (\Exception $e) {
                        $output->writeln(sprintf('<error>Could not drop database for connection named <comment>%s</comment></error>', $name));
                        $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

                        return 1;
                    }
                } else {
                    $output->writeln('<error>ATTENTION:</error> This operation should not be executed in a production environment.');
                    $output->writeln('');
                    $output->writeln(sprintf('<info>Would drop the database named <comment>%s</comment>.</info>', $name));
                    $output->writeln('Please run the operation with --force to execute');
                    $output->writeln('<error>All data will be lost!</error>');

                    return 2;
                }
            })
        ;

        $this
            ->register('doctrine:database:create')
            ->setDescription('Creates the configured databases')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
            ->setHelp(
                <<<EOT
The <info>doctrine:database:create</info> command creates the default
connections database:

<info>php app/console doctrine:database:create</info>

You can also optionally specify the name of a connection to create the
database for:

<info>php app/console doctrine:database:create --connection=default</info>
EOT
            )
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $connection = $this->silexApp['db'];

                $params = $connection->getParams();
                $name = isset($params['path']) ? $params['path'] : $params['dbname'];

                unset($params['dbname']);

                $tmpConnection = DriverManager::getConnection($params);

                // Only quote if we don't have a path
                if (!isset($params['path'])) {
                    $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
                }

                $error = false;
                try {
                    $tmpConnection->getSchemaManager()->createDatabase($name);
                    $output->writeln(sprintf('<info>Created database for connection named <comment>%s</comment></info>', $name));
                } catch (\Exception $e) {
                    $output->writeln(sprintf('<error>Could not create database for connection named <comment>%s</comment></error>', $name));
                    $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
                    $error = true;
                }

                $tmpConnection->close();

                return $error ? 1 : 0;
            })
        ;

        $this
            ->register('fixture:load')
            ->setDescription('Load some fixture')
            ->addOption('size', 's', InputOption::VALUE_REQUIRED, 'The size', 20)
            ->setCode(function (InputInterface $input, OutputInterface $output) {

                for ($i = 0; $i < $input->getOption('size'); ++$i) {
                    $this->silexApp['db']->insert('post', ['title' => 'hello #'.rand()]);
                }
            });
    }
}
