![Grimlock Logo](grimlock.png)


# Module Report - PDF Generation

---

## How to use

### 1. Generate PDF from HTML

```php
use GorillaSoft\Grimlock\Module\Report\Pdf\PdfGenerator;

$pathHtml = __DIR__ . '/../resources/template.html.php';
$pathPdf = __DIR__ . "/../resources";
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

//Generate PDF and return Path File PDF
$pathFilePdf = $pdf->generate($namePdf, $pathPdf);

```

### 2. Stream PDF from HTML

```php
use GorillaSoft\Grimlock\Module\Report\Pdf\PdfGenerator;

$pathHtml = __DIR__ . '/../resources/template.html.php';
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

//Streams the PDF to the client
$pdf->generate($namePdf);

```
