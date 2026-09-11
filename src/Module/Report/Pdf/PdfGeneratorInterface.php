<?php

namespace GorillaSoft\Grimlock\Module\Report\Pdf;

use GorillaSoft\Grimlock\Core\Collection\HashMap;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Module\Report\Pdf\Enum\PdfOrientation;
use GorillaSoft\Grimlock\Module\Report\Pdf\Enum\PdfSize;

interface PdfGeneratorInterface
{
    /**
     * @param string $path
     * @param StringMap<string> $placeholders
     * @param HashMap<mixed> $variables
     * @param PdfSize $size
     * @param PdfOrientation $orientation
     * @return void
     */
    public function loadTemplate(string $path, StringMap $placeholders = new StringMap(), HashMap $variables = new HashMap(), PdfSize $size = PdfSize::A4, PdfOrientation $orientation = PdfOrientation::VERTICAL): void;

    /**
     * @param string $name
     * @param string $path
     * @return string
     */
    public function generate(string $name, string $path): string;

    /**
     * @param string $name
     * @return void
     */
    public function download(string $name): void;

}
