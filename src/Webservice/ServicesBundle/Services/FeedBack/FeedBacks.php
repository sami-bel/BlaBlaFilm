<?php
/**
 * Created by PhpStorm.
 * User: vdmdev12
 * Date: 11/03/2016
 * Time: 18:32
 */

namespace Webservice\ServicesBundle\Services\Message;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Webservice\MainBundle\Entity\Feedback;
use Webservice\MainBundle\Repository\FeedbackRepository;



class FeedBacks extends Controller
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
     * Créer un feedback
     * @param $idExaminer
     * @param $idTarget
     * @param $typeFeedBack
     * @param $evaluation
     * @param $rating
     * @return Response
     */
    public function createFeddBack($idExaminer ,$idTarget,$typeFeedBack,$evaluation,$rating)
    {
        $feedBack= new Feedback();
            $feedBack->setIdExaminer($idExaminer);
            $feedBack->setIdTarget($idTarget);
            $feedBack->setTypeFeedback($typeFeedBack);
            $feedBack->setEvaluation($evaluation);
            $feedBack->setRating($rating);
            $this->em->persist($feedBack);
            $this->em->flush();

        return "le feedBack est bien enrigistré";
    }

    /**
     * recuperer les feed back d une seance
     * @param $idSeance
     * @return array
     */
    public function getFeedBackSenace($idSeance)
    {
        $seances =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findAllFeedbackSeance($idSeance);
        $seances = $this->serializer->toArray($seances);
        return $seances;
    }

    /**
     * supprimer le feedBack des films
     * @param $idSeance
     * @param $idExaminer
     * @return string
     */
    public function deleteFeedBackSenace($idSeance, $idExaminer ,$idFeedback)
    {
        $seances =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findFbSeanceDelete($idSeance,$idExaminer,$idFeedback);
        foreach($seances as $feedback)
        {
            $this->em->remove($feedback);
        }
        $this->em->flush();
        return "le feedBack a été bien supprimé ";
    }

    /**
     * recuperer les feed back d un film par rapport à une seance
     * @param $idFilm
     * @return array
     */
    public function getFeedBackFilm($idFilm)
    {
        $films =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findAllFeedbackFilm($idFilm);
        $films = $this->serializer->toArray($films);
        return $films;
    }

    /**
     * delete le feedback d un film
     * @param $idFilm
     * @param $idExaminer
     * @param $idFeedback
     * @return string
     */
    public function deleteFeedBackFilm($idFilm, $idExaminer ,$idFeedback)
    {
        $films =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findFbFilmDelete($idFilm,$idExaminer,$idFeedback);
        foreach($films as $feedback)
        {
            $this->em->remove($feedback);
        }
        $this->em->flush();
        return "le feedBack a été bien supprimé ";
    }

    /**
     * recuperer les feed back d un user (participant ou bien organisateur)
     * @param $idUser
     * @return array
     */
    public function getFeedBackUser($idUser)
    {
        $users =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findAllFeedbackUser($idUser);
        $users = $this->serializer->toArray($users);
        return $users;
    }

    /**
     * delete le feedback d un user ( pour supprimer l avais qu on a laisé à un user
     * @param $idUser
     * @param $idExaminer
     * @param $idFeedback
     * @return string
     */
    public function deleteFeedBackUser($idUser, $idExaminer ,$idFeedback)
    {
        $users =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findFbUserDelete($idUser,$idExaminer,$idFeedback);
        foreach($users as $feedback)
        {
            $this->em->remove($feedback);
        }
        $this->em->flush();
        return "le feedBack a été bien supprimé ";
    }

    /**
     *  recuperer les feed back d un equipment 
     * @param $idEquip
     * @return array
     */

    public function getFeedBackEquipment($idEquip)
    {
        $equipment =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findAllFeedbackEquipment($idEquip);
        $equipment = $this->serializer->toArray($equipment);
        return $equipment;
    }

    /**
     * delete le feedback d un equipment
     * @param $idEquipment
     * @param $idExaminer
     * @param $idFeedback
     * @return string
     */
    public function deleteFeedBackEquipment($idEquipment, $idExaminer ,$idFeedback)
    {
        $Equipments =$this->em->getRepository("WebserviceMainBundle:FeedBack")->findFbEquipmentDelete($idEquipment,$idExaminer,$idFeedback);
        foreach($Equipments as $feedback)
        {
            $this->em->remove($feedback);
        }
        $this->em->flush();
        return "le feedBack a été bien supprimé ";
    }




}