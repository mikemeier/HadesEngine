<html>
    <head>
        <title><?php echo tpl::title() ?></title>
    </head>
    <?php tpl::printJS() ?>
    <?php tpl::printCSS() ?>
    <link rel="stylesheet" href="themes/default/style.css" type="text/css">
    <body>
        <div id="wrapper">
            <div id="header">
                <div class="title">
                    <?php echo core::setting('core', 'site_name') ?>
                </div>
            </div>
            <div id="content">

