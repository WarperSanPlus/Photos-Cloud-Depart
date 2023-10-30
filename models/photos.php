<?php

include_once 'models/records.php';
include_once 'php/guid.php';
include_once 'php/formUtilities.php';
include_once 'php/imageFiles.php';

const PhotosFile = "data/photos.data";
const photosPath = "data/images/photos/";

class Photo extends Record
{
    private $ownerId; // Id du propriétaire de la photo
    private $title; // Titre de la photo
    private $description; // Description de la photo
    private $creationDate; // Date de création
    private $shared; // Indicateur de partage ("true" ou "false")
    private $image; // Url relatif de l'image

    public function __construct($recordData)
    {
        $this->creationDate = time();
        $this->shared = false;
        parent::__construct($recordData);
        date_default_timezone_set("America/New_York");
    }
    public function setOwnerId($ownerId)
    {
        $id = (int) $ownerId;
        if ($id > 0)
            $this->ownerId = $id;
    }
    public function setTitle($title)
    {
        if (is_string($title))
            $this->title = $title;
    }
    public function setDescription($description)
    {
        if (is_string($description))
            $this->description = $description;
    }
    public function setShared($shared)
    {
        if ($shared == "on")
            $this->shared = "true";
        else
            $this->shared = $shared == "true" ? "true" : "false";
    }
    public function setImage($image)
    {
        if (is_string($image))
            $this->image = $image;
    }
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
    public function OwnerId()
    {
        return $this->ownerId;
    }
    public function Title()
    {
        return $this->title;
    }
    public function Description()
    {
        return $this->description;
    }
    public function CreationDate()
    {
        return $this->creationDate;
    }
    public function Shared()
    {
        return $this->shared;
    }
    public function Image()
    {
        return $this->image;
    }
    public function render($isAdmin)
    {
        $photoHTML = "";
        
        $id = $this->OwnerId();
        $title = $this->Title();
        $description = $this->Description();
        $image = $this->Image();
        $owner = UsersFile()->Get($this->OwnerId());
        $ownerName = $owner->Name();
        $ownerAvatar = $owner->Avatar();
        $shared = $this->Shared() == "true";

        $sharedIndicator = "";
        $privateIndicator = "";

        $editCmd = "";
        $visible = $shared;

        if (($id == (int) $_SESSION["currentUserId"])) {
            $visible = true;
            $editCmd = <<<HTML
            <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
            <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
            HTML;

            if ($shared) {
                $sharedIndicator = createIndicator('images/shared.png', 'partagée');
            }
        }

        if ($isAdmin || $visible) {
            if (!$shared && !$visible) { // Disable private indicator if own image is private
                //if (!$shared) { // Show if own image is private 
                $privateIndicator = createIndicator('images/private.png', 'privée');
            }

            $photoHTML = <<<HTML
            <div class="photoLayout" photo_id="$id">
                <div class="photoTitleContainer" title="$description">
                    <div class="photoTitle ellipsis">$title</div> $editCmd</div>
                <a href="viewPhoto.php" target="_blank">
                    <div class="photoImage" style="background-image:url('$image')">
                        <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                        $sharedIndicator
                        $privateIndicator
                    </div>
                </a>
            </div>           
            HTML;
        }
        return $photoHTML;
    }
    public static function compare($photo_a, $photo_b)
    {
        $time_a = (int) $photo_a->CreationDate();
        $time_b = (int) $photo_b->CreationDate();
        if ($time_a == $time_b)
            return 0;
        if ($time_a > $time_b)
            return -1;
        return 1;
    }
    static function keyCompare($photo_a, $photo_b)
    {
        return 1;
    }
}

class PhotosFile extends Records
{
    public function add($photo)
    {
        $photo->setImage(saveImage(photosPath, $photo->Image()));
        parent::add($photo);
    }
    public function update($photo)
    {
        $photoToUpdate = $this->get($photo->Id());
        if ($photoToUpdate != null) {
            $photo->setImage(saveImage(photosPath, $photo->Image(), $photoToUpdate->Image()));
            parent::update($photo);
        }
    }
    public function remove($id)
    {
        $photoToRemove = $this->get($id);
        if ($photoToRemove != null) {
            unlink($photoToRemove->image());
            return parent::remove($id);
        }
        return false;
    }
}

function PhotosFile()
{
    return new PhotosFile(PhotosFile);
}