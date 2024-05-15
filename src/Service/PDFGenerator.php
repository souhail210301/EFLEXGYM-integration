<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PDFGenerator
{
  private $pdf;
  private $twig;

  public function __construct(Environment $twig)
  {
    $this->pdf = new Dompdf();
    $this->twig = $twig;
  }

  public function generatePdfFromTemplate($template, $data)
  {
    $html = $this->twig->render($template, $data);
    $this->pdf->loadHtml($html);
    $this->pdf->render();

    return $this->pdf->output();
  }
}
