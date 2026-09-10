<?php

namespace GorillaSoft\Grimlock\Tests\Module\Pdf;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Collection\HashMap;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Person;
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

        //Placeholders
        $placeholders = new StringMap();
        $placeholders->put('title', 'Grimlock Pdf Generator');

        $batman = new Person('Batman', 'batman@example.com');
        $superman = new Person('Superman', 'superman@example.com');

        //Variables
        $variables = new HashMap();
        $superheroes = new Collection();
        $superheroes->append($batman);
        $superheroes->append($superman);
        $variables->put('superheroes', $superheroes);
        $variables->put('name', 'Super Heroes');

        $pdf = new PdfGenerator();
        $pdf->loadTemplate($pathHtml, $placeholders, $variables);
        $pathFilePdf = $pdf->generate($namePdf, $pathPdf);

        $this->assertFileExists($pathFilePdf);
    }

}
