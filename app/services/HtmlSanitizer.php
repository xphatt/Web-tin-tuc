<?php
class HtmlSanitizer {
    public static function clean(string $html): string {
        // Cho phép các thẻ HTML an toàn từ Editor (TinyMCE/CKEditor) chống XSS
        $allowedTags = "<p><a><strong><b><em><i><u><ul><ol><li><br><img><h1><h2><h3><h4><h5><h6><blockquote><code><pre><table><thead><tbody><tr><th><td>";
        return strip_tags($html, $allowedTags);
    }
}

