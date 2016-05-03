<?php

namespace Adweb;

class AdImage
{
    /**
     * path image source
     * @var string
     */
    public $img_source;

    /**
     * path image destination
     * @var string
     */
    public $img_destination;

    /**
     * largeur de l'image source
     * @var INT
     */
    protected $width;

    /**
     * Hauteur de l'image source
     * @var INT
     */
    protected $height;

    /**
     *  chaîne à placer dans les balises IMG : height="xxx" width="yyy".
     * @var string
     */
    protected $size_htm;

    /**
     * type d'image
     * @var int
     */
    protected $type_image;

    /**
     * type d'image
     * @var str
     */
    protected $str_type_image;

    /**
     * Dernière erreur rencontré
     * @var string
     */
    protected $lastError;

    /**
     * Class image
     * @param string $image image
     */
    public function __construct($image)
    {
        // test existance du fichier
        if (file_exists($image)) {
            $this->img_source = $image;
            if ($this->getInfos()) {
                $this->str_type_image = $this->getType();
            }
        }
    }

    /**
     * Récupération des informations de l'image
     * @method getInfos
     * @return array or false;
     */
    private function getInfos()
    {
        // récupération des infos de l'image
        $size = getimagesize($this->img_source);

        // stockage des valeurs
        $this->width      = $size[0];
        $this->height     = $size[1];
        $this->type_image = $size[2];
        $this->size_htm   = $size[3];
        $this->mime       = $size['mime'];

        // retour du tableau
        return $size;
    }

    /**
     * Crée une nouvelle image suivant le type
     * @method create
     * @return resource Retourne un identifiant de ressource d'image ou False en cas d'erreur
     */
    public function create()
    {
        // récupération du type de l'image

        switch ($this->getType()) {
            case 'bmp':
                $img = @imagecreatefromwbmp($this->img_source);
                break;
            case 'gif':
                $img = @imagecreatefromgif($this->img_source);
                break;
            case 'jpg':
                $img = @imagecreatefromjpeg($this->img_source);
                break;
            case 'png':
                $img = @imagecreatefrompng($this->img_source);
                break;
            default:
                $this->lastError = "'". $this->img_source . "' unknow type (" . $this->getType() . ") file ";
                return false;
                break;
        }

        // vérification retour imagecreatefrom...
        if (!$img) {
            $this->lastError = "'". $this->img_source . "' is not valid (" . $this->getType() . ") file ";
            return false;
        } else {
            return $img;
        }
    }

    /**
     * Sauve une image
     * @method save
     * @param  string   $type
     * @param  resource $image      identifiant de ressource d'image
     * @param  string   $destination  chemin de destination de l'image
     * @return boolean
     */
    public function save($type, $image, $destination)
    {
        switch ($type) {
            case 'bmp':
                return imagewbmp($image, $destination);
                break;
            case 'gif':
                return imagegif($image, $destination);
                break;
            case 'jpg':
                return imagejpeg($image, $destination);
                break;
            case 'png':
                return imagepng($image, $destination);
                break;
        }
        return false;
    }

    /**
     * redimensionnement d'image et crop
     * @param  STRING   $src    path source de l'image
     * @param  STRING   $destination    path destination de l'image
     * @param  INT      $width  largeur de l'image ( en pixel )
     * @param  INT      $height longueur de l'image ( en pixel )
     * @param  Boolean  $crop   Crop oui/non
     * @return Boolean          renvoi true en cas de succès et false en cas d'erreur
     */
    public function resize($destination, $newWidth, $newHeight, $crop = false)
    {
        if (!$this->getInfos()) {
            $this->lastError = "'". $this->img_source . "' this file is not an image";
            return false;
        }

        // récupération du type de l'image
        $type = $this->getType();

        switch ($type) {
            case 'bmp':
                $img = @imagecreatefromwbmp($this->img_source);
                break;
            case 'gif':
                $img = @imagecreatefromgif($this->img_source);
                break;
            case 'jpg':
                $img = @imagecreatefromjpeg($this->img_source);
                break;
            case 'png':
                $img = @imagecreatefrompng($this->img_source);
                break;
            default:
                $this->lastError = "'". $this->img_source . "' unknow type (" . $type . ") file ";
                return false;
                break;
        }

        // vérification retour imagecreatefrom...
        if (!$img) {
            $this->lastError = "'". $this->img_source . "' is not valid (" . $type . ") file ";
            return false;
        }

        // crop
        if ($crop) {
            if ($this->width < $newWidth or $this->height < $newHeight) {
                $this->lastError = "'". $this->img_source . "' picture is too small for resizing";
                return false;
            }
            $ratio = max($newWidth / $this->width, $newHeight / $this->height);
            $h = $newHeight / $ratio;
            $x = ($this->width - $newWidth / $ratio) / 2;
            $w = $newWidth / $ratio;
        } else {
            if ($w < $newWidth and $h < $newHeight) {
                $this->lastError = "'". $this->img_source . "' picture is too small for resizing";
                return false;
            }
            $ratio = min($newWidth/$w, $newHeight/$h);
            $newWidth = $w * $ratio;
            $newHeight = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($newWidth, $newHeight);

        // preserve transparency
        if ($type === 'gif' or $type === 'png') {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $newWidth, $newHeight, $w, $h);

        switch ($type) {
            case 'bmp':
                imagewbmp($new, $destination);
                break;

            case 'gif':
                imagegif($new, $destination);
                break;

            case 'jpg':
                imagejpeg($new, $destination);
                break;

            case 'png':
                imagepng($new, $destination);
                break;
        }
        return true;
    }

    /**
     * Redimensionne une image en fonction de sa largeur
     * @method resizeWidth
     * @param  string        $destination
     * @param  integer       $newWidth
     * @return boolean
     */
    public function resizeWidth($destination, $newWidth)
    {
        if (!$this->getInfos()) {
            $this->lastError = "'". $this->img_source . "' this file is not an image";
            return false;
        }

        $img = $this->create();

        // vérification retour imagecreatefrom...
        if (!$img) {
            return false;
        }

        // vérification de la taille demandé
        if ($this->width < $newWidth) {
            $this->lastError = "'". $this->img_source . "' picture is too small for resizing";
            return false;
        }

        // calul de la nouvelle largeur
        $ratio =  $newWidth / $this->width;
        $newHeight = $this->height * $ratio;

        // création de la nouvelle image
        $new = imagecreatetruecolor($newWidth, $newHeight);

        // preserve transparency
        if ($this->getType() === 'gif' or $this->getType() === 'png') {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);

        return $this->save($this->getType(), $new, $destination);
    }

    /**
     * Redimensionne une image en fonction de sa Hauteur
     * @method resizeHeight
     * @param  string       $destination [description]
     * @param  integer       $newHeight   [description]
     * @return boolean
     */
    public function resizeHeight($destination, $newHeight)
    {
        if (!$this->getInfos()) {
            $this->lastError = "'". $this->img_source . "' this file is not an image";
            return false;
        }

        $img = $this->create();

        // vérification retour imagecreatefrom...
        if (!$img) {
            return false;
        }

        // vérification de la taille demandé
        if ($this->height < $newHeight) {
            $this->lastError = "'". $this->img_source . "' picture is too small for resizing";
            return false;
        }

        // calul de la nouvelle largeur
        $ratio =  $newHeight / $this->height;
        $newWidth = $this->width * $ratio;

        // création de la nouvelle image
        $new = imagecreatetruecolor($newWidth, $newHeight);

        // preserve transparency
        if ($this->getType() === 'gif' or $this->getType() === 'png') {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);

        return $this->save($this->getType(), $new, $destination);
    }

    /**
     * retourne le ratio de l'image
     * @method getRatio
     * @return integer
     */
    public function getRatio()
    {
        return $this->width / $this->height;
    }

    /**
     * Retourne la largeur d'une image
     * @method getWidth
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Retourne la hauteur d'une image
     * @method getHeight
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Retourne le type de l'image
     * @method getType
     * @return string
     */
    public function getType()
    {
        switch ($this->mime) {
            case 'image/x-ms-bmp':
                return 'bmp';
                break;

            case 'image/gif':
                return 'gif';
                break;

            case 'image/jpeg':
                return 'jpg';
                break;

            case 'image/png':
                return 'png';
                break;

            default:
                return false;
                break;
        }
        return null;
    }

    /**
     * Retourne un nom unique pour la nouvelle image avec l'extension du fichier d'origine
     * @method getNewImageName
     * @return string
     */
    public function getNewImageName()
    {

        return uniqid() . '_' . date('dmY') . '.' . $this->getType();

    }

    /**
     * retourne la dernière erreur rencontré
     * @method error
     * @return string
     */
    public function error()
    {
        return $this->lastError;
    }
}
