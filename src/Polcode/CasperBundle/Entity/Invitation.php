<?php

namespace Polcode\CasperBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Invitation {
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sendInvitations")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $sender;
    
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="receivedInvitations")
     * @ORM\JoinTable(name="users_receivedInvitations")
     **/
    private $receivers;
    
    /**
     * @ORM\OneToOne(targetEntity="Event", inversedBy="invitation")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $event;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set description
     *
     * @param string $description
     * @return Invitation
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set sender
     *
     * @param \AppBundle\Entity\User $sender
     * @return Invitation
     */
    public function setSender(\PolcodeCasperBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;
        return $this;
    }
    /**
     * Get sender
     *
     * @return \AppBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }
    /**
     * Add receivers
     *
     * @param \AppBundle\Entity\User $receivers
     * @return Invitation
     */
    public function addReceiver(\PolcodeCasperBundle\Entity\User $receivers)
    {
        $this->receivers[] = $receivers;
        return $this;
    }
    /**
     * Remove receivers
     *
     * @param \AppBundle\Entity\User $receivers
     */
    public function removeReceiver(\PolcodeCasperBundle\Entity\User $receivers)
    {
        $this->receivers->removeElement($receivers);
    }
    /**
     * Get receivers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivers()
    {
        return $this->receivers;
    }
    /**
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     * @return Invitation
     */
    public function setEvent(\PolcodeCasperBundle\Entity\Event $event = null)
    {
        $this->event = $event;
        return $this;
    }
    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    public function removeAllReceiver() {
        $this->receivers->clear();
    }
}