<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UploadedFile
     */
    private $file;
    private $size;
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
    public function setSize($size): self
    {
        $this->size = $size;

        return $this;
    }
    public function getFilename(): ?string
    {
        return $this->filename;
    }
    public function getSize(): ?string
    {
        return $this->size;


    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

}

?>