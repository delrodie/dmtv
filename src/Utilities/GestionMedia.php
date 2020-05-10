<?php


namespace App\Utilities;


use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GestionMedia
{
    private $media1920;
    private $media480;
    private $media250;
    private $mediaAlbum;

    public function __construct($media1920Directory, $media480Directory, $media250Directory, $mediaAlbum)
    {
        $this->media1920 = $media1920Directory;
        $this->media480 = $media480Directory;
        $this->media250 = $media250Directory;
        $this->mediaAlbum = $mediaAlbum;
    }

    public function upload(UploadedFile $file, $media = null)
    {
        $slugify = new Slugify();

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slugify($originalFileName);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'cover') $file->move($this->mediaAlbum, $newFilename);
            elseif ($media === 'img1920') $file->move($this->media1920, $newFilename);
            elseif ($media === 'img480') $file->move($this->media480, $newFilename);
            else $file->move($this->media250, $newFilename);
        }catch (FileException $e){

        }
        
        return $newFilename;

    }
}