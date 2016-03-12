<?php

namespace Dys\ApiBundle\WebServices\Allocine;

use Doctrine\ORM\EntityManager;
use Wa72\JsonRpcBundle\Controller\JsonRpcController;

class Allocine extends JsonRpcController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * JsonRpc constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($this->container, $config = null);
        $this->em = $em;
        $this->addService("dys_api.allocine");

    }

    /**
     * Récupère les information depuis l'api allociné
     *
     * @param $name
     * @param $filter
     * @return mixed
     */
    public function getInfoMovie($name)
    {
        $allocine = new AllocineApi("100043982026", "29d185d98c984a359e6e6f26a0474269");
        $result = $allocine->search($name, "movie");

        return json_decode($result, true);
    }

    /**
     * Récupère les synopsis depuis l'api allociné
     *
     * @param $id
     * @param $filter
     * @return mixed
     */
    public function getSynopsisMovie($id, $filter)
    {
        $allocine = new AllocineApi("100043982026", "29d185d98c984a359e6e6f26a0474269");
        $result   = $allocine->get($id, $filter);

        return json_decode($result, true);
    }
}