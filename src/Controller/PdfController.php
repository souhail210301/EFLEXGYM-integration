<?php

namespace App\Controller;

use App\Service\PdfGenerato;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route('/generate-pdf/{id}', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(int $id, PdfGenerato $pdfGenerator): Response
    {
        // Generate PDF using the PdfGeneratorService
        return $pdfGenerator->generatePdf($id);
    }
}
