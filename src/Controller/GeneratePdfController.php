<?php

namespace App\Controller;

use App\Service\Gotenberg;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeneratePdfController extends AbstractController
{
    private $gotenberg;

    public function __construct(Gotenberg $gotenberg)
    {
        $this->gotenberg = $gotenberg;
    }

    /**
     * Reçoit l'URL et génère un PDF
     *
     * @Route('/generate/pdf', name: 'app2_generate_pdf', methods: ['POST'])]
     */
    #[Route('/generate/pdf', name: 'app2_generate_pdf', methods: ['POST'])]
    public function generate(Request $request): Response
    {
        // Récupère l'URL depuis la requête
        $data = json_decode($request->getContent(), true);
        $url = $data['url'] ?? '';

        // Convertit l'URL en PDF
        try {
            $pdfContent = $this->gotenberg->convertUrlToPdf($url);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            return new Response($e->getMessage(), 500);
        }

        // Retourne le PDF en tant que réponse HTTP
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
    }
}
