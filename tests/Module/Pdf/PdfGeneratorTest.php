<?php

namespace GorillaSoft\Grimlock\Tests\Module\Pdf;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Collection\HashMap;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Dto\Data;
use GorillaSoft\Grimlock\Core\Dto\Param;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailPerson;
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

        $batman = new MailPerson();
        $batman->email = 'batman@example.com';
        $batman->name = 'Batman';
        $superman = new MailPerson();
        $superman->email = 'superman@example.com';
        $superman->name = 'Superman';

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
