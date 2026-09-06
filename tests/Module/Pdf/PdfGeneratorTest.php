<?php

namespace GorillaSoft\Grimlock\Tests\Module\Pdf;

use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Pdf\PdfGenerator;
use PHPUnit\Framework\TestCase;

class PdfGeneratorTest extends TestCase
{

    /**
     * @throws CoreException
     */
    public function testGeneratePdf(): void
    {
        $pathHtml = "\\tests\\resources\\template.html.php";
        $pathPdf = "\\tests\\resources";
        $namePdf = "test.pdf";
        $vars = array("name" => "Test");

        $pdf = new PdfGenerator();
        $pdf->loadHTML($pathHtml, $vars);
        $pathFilePdf = $pdf->generatePDF($namePdf, $pathPdf);

        $this->assertFileExists($pathFilePdf);
    }

}
