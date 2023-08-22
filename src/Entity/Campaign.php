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


#[ORM\Entity(repositoryClass: CampaignRepository::class)]
#[UniqueEntity(fields: ['campaignName', 'campaignId'])]
#[Vich\Uploadable]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $campaignName = null;

 
    
 
    #[Vich\UploadableField(mapping: 'campaign_bo', fileNameProperty: 'bo')]
    private ?File $boFile = null;

    #[Vich\UploadableField(mapping: 'campaign_brief', fileNameProperty: 'brief')]
    private ?File $briefFile = null;

    #[Vich\UploadableField(mapping: 'campaign_lineItemsFile', fileNameProperty: 'lifile')]
    #[Assert\File(
        mimeTypes: [
            'application/vnd.ms-excel',
            'text/plain',
            'text/csv',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.msexcel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
        mimeTypesMessage: 'Please upload a valid CSV or XLS file',
    )]
    private ?File $LineItemsFile = null;

    
    #[ORM\Column(type: 'text',  nullable: true)]
    private ?string $campaignId = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $taskId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $pipedriveId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $submissionId = null;

    #[ORM\Column(type: 'string', length: 38, nullable: true)]
    private ?string $agency = null;

    #[ORM\Column(type: 'string', length: 38, nullable: true)]
    private ?string $bookingType = null;

    #[ORM\Column(type: 'string', length: 38, nullable: true)]
    private ?string $inclusionList = null;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private ?string $globalBudget = null;

    #[ORM\Column(type: 'string', length: 35, nullable: true)]
    private ?string $advertiser = null;

    #[ORM\Column(type: 'string', length: 35, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: 'string', length: 35, nullable: true)]
    private ?string $format = null;

    #[ORM\Column(type: 'string', length: 17, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $markets = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $kpis = null;

    #[ORM\Column(type: 'string', length: 38, nullable: true)]
    private ?string $agencyDsp = null;

    #[ORM\Column(type: 'string', length: 9, nullable: true)]
    private ?string $seatId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $bo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brief;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lifile;

    #[ORM\Column(type: 'date', nullable: true)]
    private $startDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $endDate;


    // #[ORM\ManyToOne( inversedBy: 'campaigns')]


    #[ORM\OneToMany(mappedBy: 'campaignId', targetEntity: Campaign::class)]
    private Collection $campaigns;

    #[ORM\Column(type: 'boolean')]
    private ?bool $confirmed = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $deleted = null;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: LineItem::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $lineItems;

    public function __construct()
    {
        $this->campaigns = new ArrayCollection();
        $this->lineItems = new ArrayCollection();
    }

    /**
     * @return Collection|LineItem[]
     */
    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }

    public function addLineItem(LineItem $lineItem): self
    {
        if (!$this->lineItems->contains($lineItem)) {
            $this->lineItems[] = $lineItem;
            $lineItem->setCampaign($this); 
        }

        return $this;
    }

    public function removeLineItem(LineItem $lineItem) {
        $this->lineItems->removeElement($lineItem);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampaignName(): ?string
    {
        return $this->campaignName;
    }

    public function setCampaignName(string $campaignName): self
    {
        $this->campaignName = $campaignName;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCampaigns(): Collection
    {
        return $this->campaigns;
    }

    public function addCampaign(self $campaign): self
    {
        if (!$this->campaigns->contains($campaign)) {
            $this->campaigns[] = $campaign;
           
        }

        return $this;
    }

    public function removeCampaign(self $campaign): self
    {
        if ($this->campaigns->contains($campaign)) {
            $this->campaigns->removeElement($campaign);
           
        }

        return $this;
    }

    public function getConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->campaignName;
    }

    // my table campaign has the following columns : id campaignId taskID campaign_name pipedriveId submissionId agency globalBudget advertiser period email bo seatId confirmed ; 
    // now i want to you to create the setters and getters for all the columns except the id
    // create a function to get the campaign id
    public function getCampaignId(): ?string
    {
        return $this->campaignId;
    }
        // create a function to get the task id
        public function getTaskId(): ?string
    {
        return $this->taskId;
    }
    // create a function to get the pipedrive id

    public function getPipedriveId(): ?int
    {
        return $this->pipedriveId;
    }
    // create a function to get the submission id
    public function getSubmissionId(): ?int
    {
        return $this->submissionId;
    }
    // create a function to get the agency
    public function getAgency(): ?string
    {
        return $this->agency;
    }
    // create a function to get the global budget
    public function getGlobalBudget(): ?string
    {
        return $this->globalBudget;
    }
    // create a function to get the advertiser
    public function getAdvertiser(): ?string
    {
        return $this->advertiser;
    }
    // create a function to get the email
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    //create get bo
    public function getBo(): ?string
    {
        return $this->bo;
    }
    

    // create a function to get the seat id
    public function getSeatId(): ?string
    {
        return $this->seatId;
    }
   
    // create a function to get the deleted
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }
    // same for the setters
    // create a function to set the campaign id
    public function setCampaignId(string $campaignId): self
    {
        $this->campaignId = $campaignId;

        return $this;
    }
        // create a function to set the task id for a specific campaign
        public function setTaskId(string $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }
    // create a function to set the pipedrive id for a specific campaign
    public function setPipedriveId(int $pipedriveId): self
    {
        $this->pipedriveId = $pipedriveId;

        return $this;
    }
    // create a function to set the submission id for a specific campaign
    public function setSubmissionId(int $submissionId): self
    {
        $this->submissionId = $submissionId;

        return $this;
    }
    // create a function to set the agency for a specific campaign
    public function setAgency(string $agency): self
    {
        $this->agency = $agency;

        return $this;
    }
    // create a function to set the global budget for a specific campaign

    public function setGlobalBudget(string $globalBudget): self
    {
        $this->globalBudget = $globalBudget;

        return $this;
    }
    // create a function to set the advertiser for a specific campaign
    public function setAdvertiser(string $advertiser): self
    {
        $this->advertiser = $advertiser;

        return $this;
    }
    // create a function to set the email for a specific campaign
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    //create set bo
    public function setBo(string $bo): self
    {
        $this->bo = $bo;

        return $this;
    }

    public function setBoFile(?File $boFile = null): void
    {
        $this->boFile = $boFile;
        if (null !== $boFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getBoFile(): ?File
    {
        return $this->boFile;
    }
 
   
    // create a function to set the seat id for a specific campaign
    public function setSeatId(string $seatId): self
    {
        $this->seatId = $seatId;

        return $this;
    }

    // create a function to set the deleted for a specific campaign
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

      // create a function to get the brief
      public function getBrief(): ?string
      {
          return $this->brief;
      }
      // create a function to set the brief
      public function setBrief(string $brief): self
      {
          $this->brief = $brief;
  
          return $this;
      }
      // create a function to get the start date
      public function getStartDate(): ?\DateTime
      {
          return $this->startDate;
      }
      // create a function to set the start date
      public function setStartDate(?\DateTime $startDate): self
      {
          $this->startDate = $startDate;
  
          return $this;
      }
      // create a function to get the end date
      public function getEndDate(): ?\DateTime
      {
          return $this->endDate;
      }
      // create a function to set the end date
      public function setEndDate(?\DateTime $endDate): self
      {
          $this->endDate = $endDate;
  
          return $this;
      }
      // create a function to get the brief file
      public function getBriefFile(): ?File
      {
          return $this->briefFile;
      }
      // create a function to set the brief file
      public function setBriefFile(?File $briefFile = null): void
      {
          $this->briefFile = $briefFile;
          if (null !== $briefFile) {
              $this->updatedAt = new \DateTimeImmutable();
          }
      }


        public function getFormat(): ?string
        {
            return $this->format;
        }
        public function setFormat(string $format): self
        {
            $this->format = $format;
    
            return $this;
        }

        

        public function getLineItemsFile()
        {
            return $this->LineItemsFile;
        }

        public function setLineItemsFile($LineItemsFile)
        {
            $this->LineItemsFile = $LineItemsFile;
        }

        public function getKpis(): ?string
        {
            return $this->kpis;
        }
        
        public function setKpis(string $kpis): self
        {
            $this->kpis = $kpis;
    
            return $this;
        }

        public function getMarkets(): ?string
        {
            return $this->markets;
        }

        public function setMarkets(string $markets): self
        {
            $this->markets = $markets;
    
            return $this;
        }
        
        public function getLifile(): ?string
        {
            return $this->lifile;
        }
        
        public function setLifile(string $lifile): self
        {
            $this->lifile = $lifile;
    
            return $this;
        }

    
        public function getStatus(): ?string
        {
            return $this->status;
        }

        public function setStatus(string $status): self
        {
            $this->status = $status;
    
            return $this;
        }

        public function getInclusionList(): ?string
        {
            return $this->inclusionList;
        }

        public function setInclusionList(string $inclusionList): self
        {
            $this->inclusionList = $inclusionList;
    
            return $this;
        }

        public function getBookingType(): ?string
        {
            return $this->bookingType;
        }

        public function setBookingType(string $bookingType): self
        {
            $this->bookingType = $bookingType;
    
            return $this;
        }

        public function getAgencyDsp(): ?string
        {
            return $this->agencyDsp;
        }

        public function setAgencyDsp(string $agencyDsp): self
        {
            $this->agencyDsp = $agencyDsp;
    
            return $this;
        }

        

      
        






  


}
