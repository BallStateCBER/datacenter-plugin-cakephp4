<?php
use Cake\Core\Configure;

$tabs = [
    ['Projects and Publications', 'https://projects.cberdata.org'],
    ['Economic Indicators', 'https://indicators.cberdata.org'],
    ['Weekly Commentary', 'https://commentaries.cberdata.org'],
    ['County Profiles', 'https://profiles.cberdata.org'],
    ['Community Asset Inventory', 'https://cair.cberdata.org'],
    ['Brownfield Grant Writers\' Toolbox', 'https://brownfield.cberdata.org'],
    ['Manufacturing Scorecard', 'https://mfgscorecard.cberdata.org'],
];
$thisSiteUrl = Configure::read('DataCenter.siteUrl');
?>

<nav aria-labelledby="data-center-site-links" class="navbar navbar-light navbar-expand-lg">
    <a href="https://cberdata.org/" id="data-center-nameplate" class="navbar-brand">
        CBER Data Center
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-navbar"
            aria-controls="top-navbar" aria-expanded="false" aria-label="Toggle Data Center navigation links">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="top-navbar">
        <ul class="navbar-nav list-unstyled ml-auto">
            <?php foreach ($tabs as $tab): ?>
                <li class="nav-item <?= $tab[1] == $thisSiteUrl ? 'active' : null ?>">
                    <a href="<?= $tab[1] ?>" class="nav-link">
                        <?= $tab[0] ?>
                        <?php if ($tab[1] == $thisSiteUrl): ?>
                            <span class="sr-only">
                                (current)
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
