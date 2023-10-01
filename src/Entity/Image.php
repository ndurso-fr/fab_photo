<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Gedmo\Mapping\Annotation\Timestampable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    // mapping: 'upload_images' => value 'upload_images' because mappings name is 'upload_images'
    //                             into fab_photo/config/packages/vich_uploader.yaml

    #[Vich\UploadableField(mapping: 'upload_images',// mapping => mandatory It's a link with the mapping system name declared into config/packages/vich_uploader.yaml
        fileNameProperty: 'imageName',// fileNameProperty => mandatory but classe property name can named as we want
        size: 'imageSize',
        mimeType: 'mineType',
        originalName:  'originalName',
        dimensions: 'dimensions')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $mineType = null;

    #[ORM\Column(type: "string", length: 250)]
    private ?string $originalName = null;

    #[ORM\Column(nullable: true)]
    private ?array $dimensions = [];

    /**
     * @return array|null
     */
    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    /**
     * @param array|null $dimensions
     */
    public function setDimensions(?array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    /**
     * @param string|null $originalName
     */
    public function setOriginalName(?string $originalName): void
    {
        $this->originalName = $originalName;
    }


    /**
     * @return string|null
     */
    public function getMineType(): ?string
    {
        return $this->mineType;
    }

    /**
     * @param string|null $mineType
     */
    public function setMineType(?string $mineType): void
    {
        $this->mineType = $mineType;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
