<?php

namespace App\Controller;

use App\Service\StatisticsService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelController extends AbstractController
{
    /**
     * @Route("/excel", name="app_excel")
     */
    public function categoryStatistics(StatisticsService $statisticsService): Response
    {
        $categoryStatistics = $statisticsService->getCategoryStatistics();

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $sheet->setCellValue('A1', 'Category');
        $sheet->setCellValue('B1', 'Product Count');

        // Add data
        $row = 2;
        foreach ($categoryStatistics as $categoryStat) {
            $sheet->setCellValue('A' . $row, $categoryStat['category']);
            $sheet->setCellValue('B' . $row, $categoryStat['product_count']);
            $row++;
        }

        // Generate Excel file
        $writer = new Xlsx($spreadsheet);

        // Create a temporary file to store the Excel
        $tempFilePath = tempnam(sys_get_temp_dir(), 'category_statistics_');
        $writer->save($tempFilePath);

        // Set response headers for Excel download
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="category_statistics.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->setContent(file_get_contents($tempFilePath));

        // Delete the temporary file
        unlink($tempFilePath);

        return $response;
    }
}
