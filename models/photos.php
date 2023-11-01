<?php

include_once 'models/records.php';
include_once 'php/guid.php';
include_once 'php/formUtilities.php';
include_once 'php/imageFiles.php';

const PhotosFile = "data/photos.data";
const photosPath = "data/images/photos/";

class Photo extends Record
{
    /**
     * ID du propriétaire de la photo
     */
    private $ownerId;

    /**
     * Titre de la photo
     */
    private $title;

    /**
     * Description de la photo
     */
    private $description;

    /**
     * Date de publication de la photo
     */
    private $creationDate;

    /**
     * Indicateur de partage ("true" ou "false")
     */
    private $shared;

    /**
     * URL relatif de l'image
     */
    private $image;

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
        return $this->shared == "true";
    }
    public function Image()
    {
        return $this->image;
    }
    public function render($isAdmin)
    {
        $id = $this->OwnerId();
        $title = $this->Title();
        $description = $this->Description();
        $image = $this->Image();
        $owner = UsersFile()->Get($id);
        $ownerName = $owner->Name();
        $ownerAvatar = $owner->Avatar();
        $shared = $this->Shared();

        $indicators = [
            Photo::createIndicator($ownerAvatar, $ownerName)
        ];

        $editCmd = "";
        $visible = $shared;

        if (($id == (int) $_SESSION["currentUserId"])) {
            $visible = true;
            $editCmd = <<<HTML
            <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
            <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
            HTML;

            // Show shared indicator only when it's your own image
            if ($shared) {
                array_push($indicators, Photo::createIndicator('images/shared.png', 'Photo partagée', 'photosList.php?sort=shared'));
            }
        }

        // Always show shared indicator
        // if ($shared) {
        //     array_push($indicators, Photo::createIndicator('images/shared.png', 'Photo partagée', 'photosList.php?sort=shared'));
        // }

        // Disable private indicator if own image is private
        if (!$shared && !$visible) { 
            //if (!$shared) { // Show if own image is private 
            array_push($indicators, Photo::createIndicator('images/private.png', 'Photo privée', 'photosList.php?sort=privated'));
        }

        $photoHTML = "";

        if ($isAdmin || $visible) {
            $indicatorsHtml = "";

            foreach ($indicators as $indicator) {
                $indicatorsHtml .= $indicator;
            }

            $photoHTML = <<<HTML
            <div class="photoLayout" photo_id="$id">
                <div class="photoTitleContainer" title="$description">
                    <div class="photoTitle ellipsis">
                        $title
                    </div>
                    $editCmd
                </div>
                <div style="position:relative;">
                    <a href="viewPhoto.php" target="_blank">
                        <div class="photoImage" style="background-image:url('$image');"></div>
                    </a>
                    <div style="position:absolute; top:5px; left:5px">
                        $indicatorsHtml
                    </div>
                </div>
            </div>
            HTML;

            // $photoHTML = <<<HTML
            // <div class="photoLayout" photo_id="$id">
            //     <div class="photoTitleContainer" title="$description">
            //         <div class="photoTitle ellipsis">$title</div> $editCmd</div>
            //     <a href="viewPhoto.php" target="_blank">
            //         <div class="photoImage" style="background-image:url('$image')">
            //             <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
            //             $sharedIndicator
            //             $privateIndicator
            //         </div>
            //     </a>
            // </div>           
            // HTML;
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

    static function createIndicator($src, $title, $action = null)
    {
        $actionSet = isset($action);

        $indicatorHtml = $actionSet ? "<a" : "<div";
        $indicatorHtml .= ' class="UserAvatarSmall transparentBackground UserIndicator"';
        $indicatorHtml .= 'style="background-image:url(\'' . $src . '\');"';

        if ($actionSet) {
            $indicatorHtml .= 'href="' . $action . '"';
        }

        $indicatorHtml .= 'tite="' . $title . '"';
        $indicatorHtml .= ">";
        return $indicatorHtml . ($actionSet ? "</a>" : "</div>");
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