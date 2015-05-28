<?php

namespace Polcode\CasperBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * 
 * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.", groups={"registration"})
 * @UniqueEntity(fields="username", message="Sorry, this username is already taken.", groups={"registration"})
 *
 */
class User extends BaseUser {

    public function __construct() {
        parent::__construct();
        $this->features = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    private $facebook_id;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     * @Assert\Date
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
     * */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="joinedUsers")
     */
    private $joinedEvents;

    /**
     * @ORM\OneToMany(targetEntity="Invitations", mappedBy="sender")
     * */
    private $sendInvitations;

    /**
     * @ORM\ManyToMany(targetEntity="Invitations", mappedBy="receivers")
     */
    private $receivedInvitations;

    /**
     * Get joinedEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinedEvents() {
        return $this->joinedEvents;
    }

    public function joinToEvent(\Polcode\CasperBundle\Entity\Event $event) {
        $this->addJoinedEvent($event);
        $event->addJoinedUser($this);
    }

    /**
     * Add joinedEvents
     *
     * @param \Polcode\CasperBundle\Entity\Event $joinedEvents
     * @return User
     */
    private function addJoinedEvent(\Polcode\CasperBundle\Entity\Event $joinedEvents) {
        $this->joinedEvents[] = $joinedEvents;
        return $this;
    }

    public function resignFromEvent(\Polcode\CasperBundle\Entity\Event $event) {
        $this->removeJoinedEvent($event);
        $event->removeJoinedUser($this);
    }

    /**
     * Remove joinedEvents
     *
     * @param \Polcode\CasperBundle\Entity\Event $joinedEvents
     */
    public function removeJoinedEvent(\Polcode\CasperBundle\Entity\Event $joinedEvents) {
        $this->joinedEvents->removeElement($joinedEvents);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    function getEvents() {
        return $this->events;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return User
     */
    public function setSex($sex) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate() {
        return $this->birthdate;
    }

    function setEvents($events) {
        $this->events = $events;
        return $this;
    }

    /**
     * Add events
     *
     * @param \Polcode\CasperBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\Polcode\CasperBundle\Entity\Event $events) {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Polcode\CasperBundle\Entity\Event $events
     */
    public function removeEvent(\Polcode\CasperBundle\Entity\Event $events) {
        $this->events->removeElement($events);
    }

    /**
     * Set facebook_id
     *
     * @param integer $facebookId
     * @return User
     */
    public function setFacebookId($facebookId) {
        $this->facebook_id = $facebookId;
        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return integer 
     */
    public function getFacebookId() {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken) {
        $this->facebook_access_token = $facebookAccessToken;
        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken() {
        return $this->facebook_access_token;
    }

}
