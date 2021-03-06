<?php
use Cake\Core\Configure;

// Only used if a sidebar is present
$lgSidebarWidth = $lgSidebarWidth ?? 2;
$lgMainWidth = 12 - $lgSidebarWidth;
$mdSidebarWidth = $mdSidebarWidth ?? 3;
$mdMainWidth = 12 - $mdSidebarWidth;
$hasSidebar = (bool)$this->fetch('sidebar');
?>
<?php $this->start('main'); ?>
    <main id="content">
        <div id="flash-messages">
            <?= $this->Flash->render() ?>
        </div>
        <?= $this->element('DataCenter.page_title') ?>
        <?= $this->fetch('content') ?>
    </main>
<?php $this->end(); ?>

<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
    <head>
        <?= $this->Html->charset() ?>

        <?php if (Configure::read('googleTagManagerId')): ?>
            <?= $this->element('DataCenter.google_tag_manager') ?>
        <?php endif; ?>

        <link rel="dns-prefetch" href="https://ajax.googleapis.com" />
        <title>
            <?php
                $title = Configure::read('DataCenter.siteTitle');
                if (isset($pageTitle) && $pageTitle) {
                    $title = $pageTitle . ' - ' . $title;
                }
                echo $title;
            ?>
        </title>
        <meta name="title" content="<?= $title ?>" />
        <meta name="author" content="Center for Business and Economic Research, Ball State University" />
        <meta name="language" content="en" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <?= $this->fetch('meta') ?>
        <?= $this->element('DataCenter.social_media_tags') ?>
        <link rel="apple-touch-icon" sizes="180x180" href="/data_center/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="/data_center/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/data_center/favicon-16x16.png" />
        <link rel="manifest" href="/data_center/manifest.json" />
        <link rel="mask-icon" href="/data_center/safari-pinned-tab.svg" color="#5bbad5" />
        <meta name="theme-color" content="#cc0022" />
        <link href="https://fonts.googleapis.com/css?family=Asap:400,400italic,700" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/data_center/fontawesome-free-5.15.1-web/css/all.min.css" />
        <?= $this->Html->css('style') ?>
        <?= $this->fetch('css') ?>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <?= $this->fetch('scriptTop') ?>
        <?= $this->Html->script('/data_center/js/slide.js') ?>
        <?= $this->Html->script('/data_center/js/flash.js') ?>

        <?= $this->fetch('script') ?>
        <?= $this->element('DataCenter.analytics') ?>
    </head>
    <body class="default-layout">
        <?php if (Configure::read('googleTagManagerId')): ?>
            <?= $this->element('DataCenter.google_tag_manager_noscript') ?>
        <?php endif; ?>

        <?= $this->fetch('top-html') ?>

        <header>
            <div class="container">
                <h1 id="cber-bsu-links">
                    <a href="https://bsu.edu/cber">
                        Center for Business and Economic Research
                    </a>
                    -
                    <a href="https://bsu.edu">
                        Ball State University
                    </a>
                </h1>
                <?= $this->element('DataCenter.nav') ?>
            </div>
        </header>

        <?php if ($this->fetch('site_title')): ?>
            <div class="container" id="site-title">
                <div class="row">
                    <div class="col-12">
                        <?= $this->fetch('site_title') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div id="content-wrapper" class="container <?= $hasSidebar ? 'with-sidebar' : '' ?>">
            <?php if ($hasSidebar): ?>
                <div class="row">
                    <nav id="sidebar" class="<?= "col-lg-$lgSidebarWidth col-md-$mdSidebarWidth col-12 mb-5 mb-md-0" ?>"
                         aria-labelledby="site-navigation">
                        <?= $this->fetch('sidebar') ?>
                    </nav>
                    <div class="<?= "col-lg-$lgMainWidth col-md-$mdMainWidth col-12 px-0 pl-md-4" ?>">
                        <?= $this->fetch('main') ?>
                    </div>
                </div>
            <?php else: ?>
                <?= $this->fetch('main') ?>
            <?php endif; ?>
        </div>

        <?= $this->fetch('below_content') ?>

        <?= $this->element('DataCenter.footer') ?>

        <noscript id="noscript" class="alert alert-warning">
            <div>
                JavaScript is currently disabled in your browser. For full functionality of this website, JavaScript must
                be enabled. If you need assistance,
                <a href="https://www.enable-javascript.com/" target="_blank">Enable-JavaScript.com</a> provides instructions.
            </div>
        </noscript>

        <?= $this->fetch('scriptBottom') ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                <?= $this->fetch('buffered') ?>
            });
        </script>
    </body>
</html>
