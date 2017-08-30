<head>
    <!-- BASE META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$this->lang->line('application') ?> » <?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $desc ?>" />
    <meta name="keywords" content="<?=$tags?>" />
    <meta name="author" content="<?=$this->lang->line('autor')?>" />
    <meta name="generator" content="<?=$this->lang->line('application')?>" />
    <meta name="robots" content="INDEX,FOLLOW" />
    <!-- BASE META -->

    <!-- ICONS -->
    <link rel="shortcut icon" href="/frontend/assets/img/favicon.ico" />
    <!-- ICONS -->

    <!-- THEME -->
    <?php include(FRONTEND . '/view/website/parts/app_colors.php'); ?>
    <?php include(FRONTEND . '/view/website/parts/base_styles.php'); ?>
    <link href="/frontend/view/website/assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- THEME -->

    <!-- HTML5 SUPPORT -->
    <?php include(FRONTEND . '/view/website/parts/html5.php'); ?>
    <!-- HTML5 SUPPORT -->
</head>