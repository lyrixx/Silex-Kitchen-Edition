<?php

namespace DA\Service;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use DA\Service;

class ConfigService extends Service
{

    public function configureDoctrine(Application $app, $params=array())
    {
        foreach($params as $key=>$val) {
            $this->$key = $val;
        }
        if(!isset($this->isDevMode)) {
            $this->isDevMode = true;
        }
        if(!isset($params['paths'])) {
            $params['paths'] = array(
                __DIR__."/../Model/Entity"
            );
        }
        $config = Setup::createAnnotationMetadataConfiguration($params['paths'], $this->isDevMode);

        $entityManager = EntityManager::create($app['db.options'], $config);

        $app['db.orm'] = $entityManager;

        $this->entityManager = $entityManager;

        $app['db.orm.helper_set'] = new \Symfony\Component\Console\Helper\HelperSet(array(
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
        ));


        return $app;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}