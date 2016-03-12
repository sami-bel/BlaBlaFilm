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
use Webservice\MainBundle\Entity\Message;



class Messages extends Controller
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
     * Créer un message
     * @param $idSend
     * @param $idRecived
     * @param $content
     * @return Response
     */
    public function createMessage($idSend, $idRecived, $content)
    {
        $sender =$this->em->getRepository("WebserviceMainBundle:User")->find($idSend);
        $recived =$this->em->getRepository("WebserviceMainBundle:User")->find($idRecived);

        $message= new Message();
            $message->setIdSend($sender);
            $message->setIdRecived($recived);
            $message->setContent($content);
            $message->setMsgRead(false);
            $message->setFlag(0); // 0 :message n'est pas supprimé ; 1 message supprimé par le sender ;2 par le recived; 3 supprimé par les 2
            $this->em->persist($message);
            $this->em->flush();
            return "le message  a bien été bien envoyé";
    }


    /**
     * recuperer les messages récus
     *
     * @param $id
     * @return array
     */
    public function getMessagesRecived($id)
    {
        $messages = $this->em->getRepository("WebserviceMainBundle:Message")->findBy(array("idRecived" => $id));
        //Convert l'object en array
        $messages = $this->serializer->toArray($messages);
        return $messages;
    }

    /**
     * recuperer les messages envoyés
     * @param $id
     * @return array
     */
    public function getMessagesSend($id)
    {
        $messages = $this->em->getRepository("WebserviceMainBundle:Message")->findBy(array("ididSend" => $id));
        //Convert l'object en array
        $messages = $this->serializer->toArray($messages);
        return $messages;
    }

    /**
     * recuperer le message selection pour affiché
     * @param $id
     * @return array
     */
    public function getMessage($id)
    {
        $message = $this->em->getRepository("WebserviceMainBundle:Message")->findBy(array("id" => $id));
        $message = $this->serializer->toArray($message);
        return $message;
    }

    /**
     * delete message
     *     $idMessage : le message à supprimer
     *     $idUser :le user qui veut supprimer le message
     * @param $idMessage
     * @param $idUser
     * @return Response
     */
    public function deleteMessage($idMessage, $idUser)
    {
        $message = $this->em->getRepository("WebserviceMainBundle:Message")->findBy(array("id" => $idMessage));

        var_dump($message);
        exit();
        if(!$idMessage) {
            return "Le message n'existe pas ";
        }
        if ($message->getIdSend()== $idUser )

        {

            if ($message->getFlag() == 0 and $message->getFlag() != 1  )
                $message->setFlag(1); // le message serai supprimé par le sendre
            else $message->setFlag(3);
        }
        elseif ($message->getFlag() == 0 and $message->getFlag() != 2 )
        {
            $message->setFlag(2);
        }
        else $message->setFlag(3);

        $this->em->flush();
    }

}