<?php
/** @Entity */
namespace DA\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManager;

class ModuleEntity
{
    /** @Id @Column(type="integer") */
    private $id;

    /** @Column(length=64) */
    private $module_name;

    /** @Column(length=64) */
    private $controller_name;

    /** @Column(length=20) */
    private $action_name;

    public function __construct()
    {
        $this->action_name = new ArrayCollection;
        $this->module_name = new ArrayCollection;
        $this->controller_name = new ArrayCollection;
    }

    public function getAction()
    {
        return $this->action_name;
    }

    public function getModule()
    {
        return $this->module_name;
    }

    public function getController()
    {
        return $this->controller_name;
    }

}