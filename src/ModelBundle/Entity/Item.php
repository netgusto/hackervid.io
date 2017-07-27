<?php

namespace ModelBundle\Entity;

/**
 * Item
 */
class Item
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $creationdate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $votes;

    /**
     * @var \ModelBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Item
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Item
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return Item
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
     * Add comment
     *
     * @param \ModelBundle\Entity\ItemComment $comment
     *
     * @return Item
     */
    public function addComment(\ModelBundle\Entity\ItemComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \ModelBundle\Entity\ItemComment $comment
     */
    public function removeComment(\ModelBundle\Entity\ItemComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add vote
     *
     * @param \ModelBundle\Entity\ItemVote $vote
     *
     * @return Item
     */
    public function addVote(\ModelBundle\Entity\ItemVote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \ModelBundle\Entity\ItemVote $vote
     */
    public function removeVote(\ModelBundle\Entity\ItemVote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set user
     *
     * @param \ModelBundle\Entity\User $user
     *
     * @return Item
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
     * @var float
     */
    private $momentum;


    /**
     * Set momentum
     *
     * @param float $momentum
     *
     * @return Item
     */
    public function setMomentum($momentum)
    {
        $this->momentum = $momentum;

        return $this;
    }

    /**
     * Get momentum
     *
     * @return float
     */
    public function getMomentum()
    {
        return $this->momentum;
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
     * @return Item
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
