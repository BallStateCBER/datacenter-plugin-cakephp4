<?php
use Cake\Core\Configure;
?>
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
        <meta name="viewport" content="width=device-width" />
        <?= $this->fetch('meta') ?>
        <?= $this->element('DataCenter.social_media_tags') ?>
        <link rel="apple-touch-icon" sizes="180x180" href="/data_center/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="/data_center/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/data_center/favicon-16x16.png" />
        <link rel="manifest" href="/data_center/manifest.json" />
        <link rel="mask-icon" href="/data_center/safari-pinned-tab.svg" color="#5bbad5" />
        <meta name="theme-color" content="#cc0022" />
        <link href="https://fonts.googleapis.com/css?family=Asap:400,400italic,700" rel="stylesheet" type="text/css" />
        <?= $this->Html->css('style') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('scriptTop') ?>
        <?= $this->Html->script('/data_center/js/slide.js') ?>
        <?= $this->Html->script('/data_center/js/flash.js') ?>

        <?= $this->fetch('script') ?>
    </head>
    <body class="default-layout">
        <?php if (Configure::read('googleTagManagerId')): ?>
            <?= $this->element('DataCenter.google_tag_manager_noscript') ?>
        <?php endif; ?>

        <?= $this->fetch('top-html') ?>

        <header>
            <div class="max-width">
                <h1>
                    <a href="https://bsu.edu/cber">
                        Center for Business and Economic Research
                    </a>
                    -
                    <a href="https://bsu.edu">
                        Ball State University
                    </a>
                </h1>
                <br class="clear" />
                <a href="https://cberdata.org/" id="data-center-nameplate">
                    CBER Data Center
                </a>
                <?= $this->element('DataCenter.nav') ?>
                <br class="clear" />
            </div>
        </header>

        <?= $this->fetch('site_title') ?>

        <div id="content-wrapper" class="max-width">
            <?php if ($this->fetch('sidebar')): ?>
                <div id="two-col-wrapper">
                    <?php /*
                     * These two col-stretcher divs ensure that both the sidebar and content area have the appearance
                     * of having the same height.
                     */ ?>
                    <div id="menu-col-stretcher" class="col-stretcher"></div>
                    <div id="content-col-stretcher" class="col-stretcher"></div>
                    <div id="menu-column">
                        <?= $this->fetch('sidebar') ?>
                    </div>
                    <main id="content-column">
                        <div id="flash-messages">
                            <?= $this->Flash->render() ?>
                        </div>
                        <div id="content">
                            <?= $this->element('DataCenter.page_title') ?>
                            <?= $this->fetch('content') ?>
                        </div>
                        <br class="clear" />
                    </main>
                </div>
            <?php else: ?>
                <main class="container">
                    <?= $this->fetch('content') ?>
                </main>
                <br class="clear" />
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
