<?php

namespace App\Service;

use App\Repository\BilanFinancierRepository;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfGenerato
{
    private $pdf;
    private $twig;
    private $bilanFinancierRepository;

    public function __construct(Pdf $pdf, Environment $twig, BilanFinancierRepository $bilanFinancierRepository)
    {
        $this->pdf = $pdf;
        $this->twig = $twig;
        $this->bilanFinancierRepository = $bilanFinancierRepository;
    }

    public function generatePdf(int $id): Response
    {
        // Retrieve the specific financial statement (Bilan Financier)
        $bilanFinancier = $this->bilanFinancierRepository->find($id);

        // Check if the financial statement is found
        if (!$bilanFinancier) {
            throw $this->createNotFoundException('Bilan financier non trouvé.');
        }

        // Determine the month and year based on the financial statement's start date
        $month = $bilanFinancier->getDateDebut()->format('n');
        $year = $bilanFinancier->getDateDebut()->format('Y');

        // Generate HTML content for the PDF using Twig template
        $html = $this->twig->render('bilan_financier/pdf.html.twig', [
            'month' => $month,
            'year' => $year,
            'revenusAbonnements' => $bilanFinancier->getRevenusAbonnements(),
            'revenusProduits' => $bilanFinancier->getRevenusProduits(),
            'depenses' => $bilanFinancier->getDepenses(),
            'profit' => $bilanFinancier->getProfit(),
            // Add other necessary data for the financial report
        ]);

        // Convert HTML content to PDF
        $pdfContent = $this->pdf->getOutputFromHtml($html);

        // Return a response with the PDF as an attachment
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="rapport_financier.pdf"',
            ]
        );
    }
}
