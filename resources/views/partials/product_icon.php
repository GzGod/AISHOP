<?php
/** @var \App\Models\Product $product */
$cat = $product->category;
$iconSvg = match($cat) {
    'chatgpt'    => '<path d="M22.28 9.82a5.98 5.98 0 00-.52-4.91 6.05 6.05 0 00-6.51-2.9A6.07 6.07 0 004.98 4.18a5.98 5.98 0 00-4 2.9 6.05 6.05 0 00.74 7.1 5.98 5.98 0 00.51 4.91 6.05 6.05 0 006.51 2.9A5.98 5.98 0 0013.26 24a6.06 6.06 0 005.77-4.21 5.99 5.99 0 004-2.9 6.06 6.06 0 00-.75-7.07z"/>',
    'claude'     => '<path d="M13.5 2h-3L4 22h3l1.5-4h7l1.5 4h3zm-4 13l2.5-6.67L14.5 15H9.5z"/>',
    'grok'       => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
    'midjourney' => '<path d="M4 5a1 1 0 000 2h1v10H4a1 1 0 100 2h16a1 1 0 100-2h-1V7h1a1 1 0 100-2H4zm3 2h10v10H7V7z"/>',
    default      => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>',
};
$iconColor = match($cat) {
    'chatgpt'    => '#10A37F',
    'claude'     => '#C06B42',
    'grok'       => '#E5E7EB',
    'midjourney' => '#00E0FF',
    default      => '#7C90AC',
};
?>
<svg viewBox="0 0 24 24" fill="<?= $iconColor ?>" class="w-5 h-5" aria-hidden="true"><?= $iconSvg ?></svg>
