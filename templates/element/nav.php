<?php
    use Cake\Core\Configure;

    $tabs = [
        ['Projects and<br />Publications', 'https://projects.cberdata.org'],
        ['Economic<br />Indicators', 'https://indicators.cberdata.org'],
        ['Weekly<br />Commentary', 'https://commentaries.cberdata.org'],
        ['County<br />Profiles', 'https://profiles.cberdata.org'],
        ['Community<br />Asset Inventory', 'https://cair.cberdata.org'],
        ['Brownfield Grant<br />Writers\' Toolbox', 'https://brownfield.cberdata.org'],
        ['Manufacturing<br />Scorecard', 'https://mfgscorecard.cberdata.org'],
    ];
    $thisSiteUrl = Configure::read('DataCenter.siteUrl');
?>

<nav aria-labelledby="data-center-site-links">
    <?php foreach ($tabs as $tab): ?>
        <?php if ($tab[1] == $thisSiteUrl): ?>
            <a href="<?= $tab[1] ?>" class="selected">
                <?= $tab[0] ?>
            </a>
        <?php else: ?>
            <a href="<?= $tab[1] ?>">
                <?= $tab[0] ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
