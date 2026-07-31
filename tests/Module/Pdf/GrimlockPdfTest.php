<?php

namespace GorillaSoft\Grimlock\Tests\Module\Pdf;

use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Module\Pdf\GrimlockPdf;
use PHPUnit\Framework\TestCase;

class GrimlockPdfTest extends TestCase
{

    /**
     * @throws GrimlockException
     */
    public function testGeneratePdf(): void
    {
        $pathHtml = "\\tests\\resources\\template.html.php";
        $pathPdf = "\\tests\\resources";
        $namePdf = "test.pdf";
        $vars = array("name" => "Test");

        $pdf = new GrimlockPdf();
        $pdf->loadHTML($pathHtml, $vars);
        $pathFilePdf = $pdf->generatePDF($namePdf, $pathPdf);

        $this->assertFileExists($pathFilePdf);
    }

}
