<?php

namespace ModelBundle\Entity;

/**
 * ItemVote
 */
class ItemVote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $creationdate;

    /**
     * @var \ModelBundle\Entity\User
     */
    private $user;

    /**
     * @var \ModelBundle\Entity\Item
     */
    private $item;


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
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return ItemVote
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * Get creationdate
     *
     * @return \DateTime
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * Set user
     *
     * @param \ModelBundle\Entity\User $user
     *
     * @return ItemVote
     */
    public function setUser(\ModelBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ModelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set item
     *
     * @param \ModelBundle\Entity\Item $item
     *
     * @return ItemVote
     */
    public function setItem(\ModelBundle\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \ModelBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }
    /**
     * @var string
     */
    private $ipv4;


    /**
     * Set ipv4
     *
     * @param string $ipv4
     *
     * @return ItemVote
     */
    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    /**
     * Get ipv4
     *
     * @return string
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }
}
