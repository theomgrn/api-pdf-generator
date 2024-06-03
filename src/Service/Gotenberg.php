<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Gotenberg
{
    private $client;
    private $gotenbergUri;

    /**
     * Constructeur du service Gotenberg
     *
     * @param HttpClientInterface $client Le client HTTP pour effectuer les requêtes
     * @param string $gotenbergUri L'URI du service Gotenberg
     */
    public function __construct(HttpClientInterface $client, string $gotenbergUri)
    {
        $this->client = $client;
        $this->gotenbergUri = $gotenbergUri;
    }

    /**
     * Convertit une URL en PDF en utilisant le service Gotenberg
     *
     * @param string $url L'URL à convertir en PDF
     * @return string Le contenu du PDF
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function convertUrlToPdf(string $url): string
    {
        // Envoie une requête POST au service Gotenberg pour convertir l'URL en PDF
        $response = $this->client->request('POST', $this->gotenbergUri, [
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
            'body' => [
                'url' => $url,
            ],
        ]);

        // Retourne le contenu du PDF
        return $response->getContent();
    }
}
