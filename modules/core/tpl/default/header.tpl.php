<html>
    <head>
        <title><?php echo tpl::title() ?></title>
    </head>
    <?php tpl::printJS() ?>
    <?php tpl::printCSS() ?>
    <style type="text/css">
        @import url('themes/default/style.css');
    </style>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="title">
                    <?php echo utils::setting('core', 'site_name') ?>
                </div>
            </div>
            <div class="content">
                
