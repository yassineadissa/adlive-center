<?php 

namespace App\Entity;

use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

 // My lineitem table has the following fields: id , lineitemId, orderNumber, name,  geo, bookingType, kpi, language, volume, goal, unit, budget, format, startDate, endDate, creditNote, seatId.

 #[ORM\Entity]
 #[ORM\Table(name: "lineItem")]
 class LineItem
 {
     #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
     private $id;
 
     #[ORM\Column(type: "integer")]
     private $lineitemId;
 
     #[ORM\Column(type: "integer",  nullable: true)]
     private $orderNumber;
 
     #[ORM\Column(type: "string")]
     private $name;
 
     #[ORM\Column(type: "string")]
     private $geo;
 
     #[ORM\Column(type: "string")]
     private $bookingType;
 
     #[ORM\Column(type: "string")]
     private $kpi;
 
     #[ORM\Column(type: "string",  nullable: true)]
     private $language;
 
     #[ORM\Column(type: "integer")]
     private $volume;

     #[ORM\Column(type: "integer")]
     private $volumeDelivred;
 
     #[ORM\Column(type: "integer")]
     private $goal;
 
     #[ORM\Column(type: "string")]
     private $unit;
 
     #[ORM\Column(type: "integer")]
     private $budgetSpent;

     #[ORM\Column(type: "integer")]
     private $budget;
 
     #[ORM\Column(type: "string")]
     private $format;
 
     #[ORM\Column(type: "date")]
     private $startDate;
 
     #[ORM\Column(type: "date")]
     private $endDate;
 
     #[ORM\Column(type: "string",  nullable: true)]
     private $creditNote;
 
     #[ORM\Column(type: "integer",  nullable: true)]
     private $seatId;   

     #[ORM\ManyToOne(targetEntity: Campaign::class , inversedBy: 'lineItems')]
     #[ORM\JoinColumn(name:"campaign_id", referencedColumnName:"id")]
     private $campaign;

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    // create getter and setter for all fields
    public function getId()
    {
        return $this->id;
    }
    public function getLineitemId()
    {
        return $this->lineitemId;
    }
    public function setLineitemId($lineitemId)
    {
        $this->lineitemId = $lineitemId;
    }
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getGeo()
    {
        return $this->geo;
    }
    public function setGeo($geo)
    {
        $this->geo = $geo;
    }
    public function getBookingType()
    {
        return $this->bookingType;
    }
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;
    }
    public function getKpi()
    {
        return $this->kpi;
    }
    public function setKpi($kpi)
    {
        $this->kpi = $kpi;
    }
    public function getLanguage()
    {
        return $this->language;
    }
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    public function getVolume()
    {
        return $this->volume;
    }
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }
    public function getGoal()
    {
        return $this->goal;
    }
    public function setGoal($goal)
    {
        $this->goal = $goal;
    }
    public function getUnit()
    {
        return $this->unit;
    }
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }
    public function getBudget()
    {
        return $this->budget;
    }
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }
    public function getFormat()
    {
        return $this->format;
    }
    public function setFormat($format)
    {
        $this->format = $format;
    }
    public function getStartDate()
    {
        return $this->startDate;
    }
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }
    public function getEndDate()
    {
        return $this->endDate;
    }
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }
    public function getCreditNote()
    {
        return $this->creditNote;
    }
    public function setCreditNote($creditNote)
    {
        $this->creditNote = $creditNote;
    }
    public function getSeatId()
    {
        return $this->seatId;
    }
    public function setSeatId($seatId)
    {
        $this->seatId = $seatId;
    }

    public function getBudgetSpent()
    {
        return $this->budgetSpent;
    }

    public function setBudgetSpent($budgetSpent)
    {
        $this->budgetSpent = $budgetSpent;
    }

    public function getVolumeDelivred()
    {
        return $this->volumeDelivred;
    }

    public function setVolumeDelivred($volumeDelivred)
    {
        $this->volumeDelivred = $volumeDelivred;
    }
    

}
 



?>