<?php
// FILE: app/Support/QrCode.php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCode
{
    // QR sebagai SVG siap ditempel langsung di HTML ({!! !!})
    public static function svg(string $text, int $size = 300): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle($size, 2), new SvgImageBackEnd()));

        // Buang deklarasi XML di awal karena SVG ditanam di dalam halaman
        return trim(preg_replace('/^<\?xml.*?\?>/', '', $writer->writeString($text)));
    }
}
