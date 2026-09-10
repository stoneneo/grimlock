<?php

namespace GorillaSoft\Grimlock\Module\Pdf;

use Dompdf\Dompdf;
use Exception;
use GorillaSoft\Grimlock\Core\Collection\HashMap;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Helper\FileHelper;
use GorillaSoft\Grimlock\Core\Helper\TemplateHelper;
use GorillaSoft\Grimlock\Module\Pdf\Enum\PdfOrientation;
use GorillaSoft\Grimlock\Module\Pdf\Enum\PdfSize;
use Throwable;

/**
 * Class PdfGenerator
 * Class that facilitates the use of the DOMPDF library to load HTML and render it as PDF.
 * @package Grimlock
 * @author Rubén Darío Huamaní Ucharima
 */
class PdfGenerator implements PdfGeneratorInterface
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
     * @param string $path
     * @param StringMap<string> $placeholders
     * @param HashMap<mixed> $variables
     * @param PdfSize $size
     * @param PdfOrientation $orientation
     * @return void
     * @throws CoreException
     */
    public function loadTemplate(string $path, StringMap $placeholders = new StringMap(), HashMap $variables = new HashMap(), PdfSize $size = PdfSize::A4, PdfOrientation $orientation = PdfOrientation::VERTICAL): void
    {
        try {
            $file = FileHelper::resolvePath($this->basePath, $path);

            $html = $this->renderTemplate($file, $placeholders, $variables);

            $this->pdf->loadHtml($html, 'UTF-8');
            $this->pdf->setPaper($size->value, $orientation->value);
            $this->pdf->render();

        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $name
     * @param string $path
     * @return string
     * @throws CoreException
     */
    public function generate(string $name, string $path): string
    {
        if (empty($name)) {
            throw new CoreException(self::class, 'Name cannot be null or empty');
        }

        if (empty($path)) {
            throw new CoreException(self::class, 'Path cannot be null or empty');
        }
        try {
            $file = FileHelper::resolvePath($this->basePath, $path);
            file_put_contents($file.DIRECTORY_SEPARATOR.$name, $this->pdf->output());
            return $file;
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $name
     * @return void
     * @throws CoreException
     */
    public function download(string $name): void
    {
        if (empty($name)) {
            throw new CoreException(self::class, 'Name cannot be null or empty');
        }

        try {
            $options = ['Attachment' => 1];
            $this->pdf->stream($name, $options);
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $path
     * @param StringMap<string>|null $params
     * @param HashMap<mixed>|null $variables
     * @return string
     * @throws CoreException
     */
    private function renderTemplate(string $path, ?StringMap $params = null, ?HashMap $variables = null): string
    {
        $real = realpath($path);
        if ($real === false || !is_readable($real)) {
            throw new CoreException(self::class, 'Template not readable: ' . $path);
        }

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        if (!in_array($ext, ['php', 'html', 'htm'], true)) {
            throw new CoreException(self::class, 'Invalid template extension: ' . $ext);
        }

        // --- Templates PHP ---
        if ($ext === 'php') {
            ob_start();
            try {
                $data = [];
                if ($variables !== null) {
                    foreach ($variables as $key => $value) {
                        $data[$key] = $value;
                    }
                }
                extract($data, EXTR_SKIP);

                require $real;
                $html = ob_get_clean();
            } catch (Throwable $e) {
                if (ob_get_level() > 0) {
                    ob_end_clean();
                }
                throw new CoreException(
                    self::class,
                    $e->getMessage(),
                    previous: $e
                );
            }
            if ($html === false) {
                throw new CoreException(self::class, 'Failed to capture template output buffer');
            }

            return TemplateHelper::replaceParams($html, $params);
        }

        // --- Templates HTML ---
        $html = file_get_contents($real);
        if ($html === false) {
            throw new CoreException(self::class, 'Error reading HTML template');
        }

        return TemplateHelper::replaceParams($html, $params);
    }

}
