<?php

declare(strict_types=1);

namespace App\Modules\System\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonyHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlSanitizer
{
    private static ?SymfonyHtmlSanitizer $instance = null;

    /**
     * Clean the given HTML content to prevent XSS.
     *
     * @param string $html
     * @return string
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        if (self::$instance === null) {
            $config = (new HtmlSanitizerConfig())
                ->allowSafeElements()
                // Allow Trix custom rich text attachment tags
                ->allowElement('rich-text-attachment', [
                    'sgid', 'content-type', 'width', 'height', 'filename', 
                    'filesize', 'url', 'href', 'caption', 'presentation'
                ])
                // Allow standard Trix layout wrappers (figure and figcaption)
                ->allowElement('figure', [
                    'data-trix-attachment', 'data-trix-attributes', 
                    'data-trix-content-type', 'class'
                ])
                ->allowElement('figcaption', ['class'])
                // Allow spans and divs with inline styles/classes
                ->allowElement('span', ['style', 'class'])
                ->allowElement('div', ['class', 'style']);

            self::$instance = new SymfonyHtmlSanitizer($config);
        }

        return self::$instance->sanitize($html);
    }
}
