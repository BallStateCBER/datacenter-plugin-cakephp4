# Ball State CBER DataCenter plugin for CakePHP 4

## Installation

Install this plugin in your CakePHP application using [composer](http://getcomposer.org).

```
composer require ballstatecber/datacenter-plugin-cakephp4:dev-master
```

Add `$this->addPlugin('DataCenter');` in `App\Application:bootstrap()`.

## Configuration
 - Set `'data_center_subsite_title'` in `app_local.php`.
 - Copy the plugin's `config/datacenter.php` configuration file to the application's `config` directory and change
   values where appropriate

## View layer

### Page title
 - Set `$pageTitle` in each action's view variables where you would like to prepend the site title with the page title
   and display it as a header.
 - Set the `$hidePageTitle` view variable to `true` to only put the title in `<title>` and prevent it from being
   displayed.

### Layout
Replace `templates/layout/default.php` with the following:
```
<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$this->extend('DataCenter.default');

// If you have a /templates/elements/sidebar.php file
$this->assign('sidebar', $this->element('sidebar'));
?>

<?php
    // If you'd like to have a masthead between the navbar and main content
    $this->append('subsite_title');
?>
    <h1 id="subsite_title" class="max_width">
        <a href="/">
            <img src="/img/logo.jpg" alt="<?= Configure::read('data_center_subsite_title') ?>" />
        </a>
    </h1>
<?php $this->end(); ?>

<div id="content">
    <?= $this->fetch('content') ?>
</div>
```

## CSS
Create `webroot/css/style.scss` with these imports at the top:
```
@import "./cake.css";
@import "../../vendor/twbs/bootstrap/scss/bootstrap.scss";
@import "../../vendor/ballstatecber/datacenter-plugin-cakephp4/webroot/css/datacenter.scss";
```

If you're using the tag editor, also add
```
@import "../../vendor/ballstatecber/datacenter-plugin-cakephp4/webroot/css/tag_editor.scss";
```

## Authentication / Authorization
Refer to the plugin's [auth docs](docs/auth.md) for information about using its standard auth setup.
