<?php
// src/Controller/ArtistAlbumSongController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArtistRepository;
use App\Repository\AlbumRepository;
use App\Repository\SongRepository;

class ArtistAlbumSongController extends AbstractController
{
    private $artistRepository;
    private $albumRepository;
    private $songRepository;

    public function __construct(ArtistRepository $artistRepository, AlbumRepository $albumRepository, SongRepository $songRepository)
    {
        $this->artistRepository = $artistRepository;
        $this->albumRepository = $albumRepository;
        $this->songRepository = $songRepository;
    }

    public function getSong(int $artistId, int $albumId, int $songId): JsonResponse
    {
        $artist = $this->artistRepository->find($artistId);
        if (!$artist) {
            return $this->json(['error' => 'Artist not found'], 404);
        }

        $album = $this->albumRepository->find($albumId);
        if (!$album || $album->getArtist()->getId() !== $artistId) {
            return $this->json(['error' => 'Album not found or does not belong to the artist'], 404);
        }

        $song = $this->songRepository->find($songId);
        if (!$song || $song->getAlbum()->getId() !== $albumId) {
            return $this->json(['error' => 'Song not found or does not belong to the album'], 404);
        }

        return $this->json($song);
    }
}