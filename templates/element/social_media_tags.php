<?php
/**
 * @var \DataCenter\View\DataCenterView $this
 * @var array $ogMetaTags
 */
use App\View\AppView;
use Cake\Core\Configure;
use Cake\Utility\Text;

$pageTitle = $pageTitle ?? false;
$siteTitle = Configure::read('DataCenter.siteTitle');
$defaultTitle = $pageTitle ? "$pageTitle - $siteTitle" : null;
$ogLogoPath = Configure::read('DataCenter.defaultOpenGraphLogoPath');
$defaultOgImage = $ogLogoPath ? $this->Url->image($ogLogoPath) : null;
$twitterLogoPath = Configure::read('DataCenter.defaultTwitterLogoPath');
$defaultTwitterImage = $twitterLogoPath ? $this->Url->image($twitterLogoPath) : null;
$uri = $this->getRequest()->getUri();
$canonicalUrl = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
$defaultOgMetaTags = [
    'og:title' => $defaultTitle,
    'og:type' => 'website',
    'og:image' => $defaultOgImage,
    'og:url' => $canonicalUrl,
    'og:site_name' => $siteTitle,
    'og:description' => Configure::read('DataCenter.openGraphDescription'),
    'og:locale' => 'en_US',
    'twitter:site' => Configure::read('DataCenter.twitterUsername'),
    'twitter:image' => $defaultTwitterImage,
    'fb:app_id' => Configure::read('DataCenter.facebookAppId'),
];

$ogMetaTags = $ogMetaTags ?? [];
$ogMetaTags = array_merge($defaultOgMetaTags, $ogMetaTags);
foreach ($ogMetaTags as $property => $contents) {
    $contents = (array)$contents;
    foreach ($contents as $content) {
        if (empty($content)) {
            continue;
        }
        if ($property == 'og:description') {
            $content = Text::truncate(strip_tags($content), 1000, ['exact' => false]);
        }
        echo sprintf('<meta property="%s" content="%s" />', $property, htmlentities($content));
    }
}
