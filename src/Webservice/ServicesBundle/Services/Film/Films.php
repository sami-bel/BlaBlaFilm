<?php
/**
 * Created by PhpStorm.
 * User: vdmdev12
 * Date: 11/03/2016
 * Time: 18:32
 */

namespace Webservice\ServicesBundle\Services\Film;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Webservice\MainBundle\Entity\Film;
use Webservice\ServicesBundle\Libs\Allocine\AllocineApi;


/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="films")
 */
class Films extends Controller
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected $serializer;

    public function __construct(EntityManager $em, Serializer $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * Créer un film
     *
     * @param $title
     * @param $category
     * @param $isan
     * @param $description
     * @param $image
     * @return Response
     */
    public function createFilm($title, $category, $isan, $description, $image)
    {
        $film = new Film();
        $filmExist = $this->findFilmByTitle($title);

        if(!$filmExist)
        {
            $film->setTitle($title);
            $film->setCategory($category);
            $film->setIsan($isan);
            $film->setDescription($description);
            $film->setImage($image);

            $this->em->persist($film);
            $this->em->flush();

            return "Le film ".$title." a bien été créer";
        }

        return "Le film existe déjà";

    }

    /**
     * Récupèrer un film par son titre
     *
     * @param $title
     * @return array
     */
    public function findFilmByTitle($title)
    {
        $title = $this->em->getRepository("WebserviceMainBundle:Film")->findBy(array("title" => $title));

        //Convert l'object en array
        $title = $this->serializer->toArray($title);

        return $title;
    }


    /**
     * Récupèrere un film par son id
     *
     * @param $id
     * @return array|null|object|string
     */
    public function findFilmById($id)
    {
        $id = $this->em->getRepository("WebserviceMainBundle:Film")->find($id);
        if(!$id) {
            return "Le film avec l".$id. "n'existe pas ";
        }
        $id = $this->serializer->toArray($id, 'json');
       return $id;
    }

    /**
     * Récupèrer un film par son titre ou son id
     *
     * @param $idOrTitle
     * @return array|null|object|string
     */
    public function findFilmByIdOrTitle($idOrTitle)
    {
        if(is_int($idOrTitle)) {
            return $this->findFilmById($idOrTitle);
        }
        return $this->findFilmByTitle($idOrTitle);
    }


    public function getInfoFilmFromAllocine($name)
    {
        $allocine = new AllocineApi("100043982026", "29d185d98c984a359e6e6f26a0474269");
        $name = $allocine->search($name, "movie");

        // l'object json qu'on récupère de l'Api Allocine contient des antislash
        // l'object semble être mal formaté
        // on le clean avec la fonction json_decode et l'option true
        $name = json_decode($name, true);

        foreach($name["feed"]["movie"] as $synopsisInfo) {
            $code = $synopsisInfo["code"];
            $synopsis =  $allocine->get($code, "movie");
        }
        $synopsis = json_decode($synopsis, true);
        $result = array_merge($name, $synopsis);

        dump($result); exit();



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

        $result =  json_decode($result, true);

    }


}