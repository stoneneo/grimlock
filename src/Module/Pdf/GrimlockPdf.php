<?php

namespace GorillaSoft\Grimlock\Module\Pdf;

use Dompdf\Dompdf;
use Exception;
use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Core\Util\GrimlockUtil;
use GorillaSoft\Grimlock\Module\Pdf\Enum\PdfOrientation;
use GorillaSoft\Grimlock\Module\Pdf\Enum\PdfSize;

/**
 * Class GrimlockPdf
 * Class that facilitates the use of the DOMPDF library to load HTML and render it as PDF.
 * @package Grimlock
 * @author Rubén Darío Huamaní Ucharima
 */
class GrimlockPdf
{

    private Dompdf $pdf;
    private string $basePath;

    /**
     * @param Dompdf|null $dompdf
     * @param string|null $basePath
     */
    public function __construct(?Dompdf $dompdf = null, ?string $basePath = null)
    {
        $this->pdf = $dompdf ?? new Dompdf();
        $this->basePath = $basePath ?? dirname(__DIR__, 3);
    }

    /**
     * @param string $pathHTML
     * @param string $size
     * @param string $orientation
     * @param array $vars
     * @return void
     * @throws GrimlockException
     */
    public function loadHTML(string $pathHTML, array $vars = [], string $size = PdfSize::A4->value, string $orientation = PdfOrientation::VERTICAL->value): void
    {
        try {
            $path = GrimlockUtil::resolvePath($this->basePath, $pathHTML);
            if (PdfSize::tryFrom($size) === null) {
                throw new GrimlockException(self::class, 'Size PDF not exist');
            }

            if (PdfOrientation::tryFrom($orientation) === null) {
                throw new GrimlockException(self::class, 'Orientation PDF not exist');
            }

            $html = $this->renderTemplate($path, $vars);

            $this->pdf->loadHtml($html, 'UTF-8');
            $this->pdf->setPaper($size, $orientation);
            $this->pdf->render();

        } catch (Exception $e) {
            throw new GrimlockException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $nomPDF
     * @param string $pathPdf
     * @return string
     * @throws GrimlockException
     */
    public function generatePDF(string $nomPDF, string $pathPdf): string
    {
        if ($nomPDF === '') {
            throw new GrimlockException(self::class, 'Path PDF cannot be null or empty');
        }

        if ($pathPdf === '') {
            throw new GrimlockException(self::class, 'Name PDF cannot be null or empty');
        }
        try {
            $path = GrimlockUtil::resolvePath($this->basePath, $pathPdf);
            file_put_contents($path.DIRECTORY_SEPARATOR.$nomPDF, $this->pdf->output());

            return $path;
        } catch (Exception $e) {
            throw new GrimlockException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $nomPDF
     * @return void
     * @throws GrimlockException
     */
    public function downloadPDF(string $nomPDF = ''): void
    {
        if ($nomPDF === '') {
            throw new GrimlockException(self::class, 'Name PDF cannot be null or empty');
        }

        try {
            $options = ['MailAttachment' => 1];
            $this->pdf->stream($nomPDF, $options);
        } catch (Exception $e) {
            throw new GrimlockException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $path
     * @param array $vars
     * @return string
     * @throws GrimlockException
     */
    private function renderTemplate(string $path, array $vars = []): string
    {
        $real = realpath($path);
        if ($real === false || !is_readable($real)) {
            throw new GrimlockException(self::class, 'Template not readable: ' . $path);
        }

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        if (!in_array($ext, ['php', 'html', 'htm'], true)) {
            throw new GrimlockException(self::class, 'Invalid template extension: ' . $ext);
        }

        // --- Templates PHP ---
        if ($ext === 'php') {
            ob_start();
            try {
                extract($vars, EXTR_SKIP);
                require $real;
                $html = ob_get_clean();
            } catch (\Throwable $e) {
                if (ob_get_level()) {
                    ob_end_clean();
                }
                throw new GrimlockException(self::class, $e->getMessage());
            }

            return $this->applyVars($html, $vars);
        }

        // --- Templates HTML ---
        $html = file_get_contents($real);
        if ($html === false) {
            throw new GrimlockException(self::class, 'Error reading HTML template');
        }

        return $this->applyVars($html, $vars);
    }

    /**
     * @param string $html
     * @param array $vars
     * @return string
     */
    private function applyVars(string $html, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }
        return $html;
    }

}
