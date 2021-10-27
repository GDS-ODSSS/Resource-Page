<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><!DOCTYPE html>
<html dir="<?php echo (isset($this->_rootref['SITE_DIR'])) ? $this->_rootref['SITE_DIR'] : ''; ?>" lang="<?php echo (isset($this->_rootref['SITE_LANG'])) ? $this->_rootref['SITE_LANG'] : ''; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?> - <?php echo (isset($this->_rootref['PAGE_TITLE'])) ? $this->_rootref['PAGE_TITLE'] : ''; ?></title>
    <meta name="description" content="<?php echo (isset($this->_rootref['SITE_DESCRIPTION'])) ? $this->_rootref['SITE_DESCRIPTION'] : ''; ?>">
    <meta name="keywords" content="<?php echo (isset($this->_rootref['SITE_KEYWORDS'])) ? $this->_rootref['SITE_KEYWORDS'] : ''; ?>,<?php echo (isset($this->_rootref['GET_POST_TAGS'])) ? $this->_rootref['GET_POST_TAGS'] : ''; ?>">
    <meta name="author" content="nawaaugustine.com">
    <meta name="robots" content="<?php echo (isset($this->_rootref['THOP_METAROBOTS'])) ? $this->_rootref['THOP_METAROBOTS'] : ''; ?>">
    <link rel="canonical" href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>">
    <meta property="og:title" content="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?> - <?php echo (isset($this->_rootref['PAGE_TITLE'])) ? $this->_rootref['PAGE_TITLE'] : ''; ?>">
    <meta property="og:description" content="<?php echo (isset($this->_rootref['SITE_DESCRIPTION'])) ? $this->_rootref['SITE_DESCRIPTION'] : ''; ?>">
    <meta property="og:url" content="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>">
    <meta property="og:site_name" content="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="<?php echo (isset($this->_rootref['THOP_METATWITTER'])) ? $this->_rootref['THOP_METATWITTER'] : ''; ?>">
    <meta name="twitter:title" content="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?> - <?php echo (isset($this->_rootref['PAGE_TITLE'])) ? $this->_rootref['PAGE_TITLE'] : ''; ?>">
    <meta name="twitter:description" content="<?php echo (isset($this->_rootref['SITE_DESCRIPTION'])) ? $this->_rootref['SITE_DESCRIPTION'] : ''; ?>">
    <link rel="shortcut icon" href="<?php if ($this->_rootref['THOP_FAVICON']) {  echo (isset($this->_rootref['THOP_FAVICON'])) ? $this->_rootref['THOP_FAVICON'] : ''; } else { echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/images/favicon.ico<?php } ?>"> <?php echo (isset($this->_rootref['THOP_LINK_GOOGLE_FONT'])) ? $this->_rootref['THOP_LINK_GOOGLE_FONT'] : ''; ?>
    <link rel="stylesheet" href="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/css/global.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tipsy/1.0.3/jquery.tipsy.min.css">
    <?php if ($this->_rootref['THOP_PAGE_LOADED']) {  ?><link rel="stylesheet" href="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/css/loaded.css"><?php } ?>
    <link rel="stylesheet" href="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/css/responsive.css">
    <?php if ($this->_rootref['IS_RTL']) {  ?><link rel="stylesheet" href="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/css/rtl.min.css"><?php } ?>
    <?php echo (isset($this->_rootref['ENQUEUE_STYLE'])) ? $this->_rootref['ENQUEUE_STYLE'] : ''; echo (isset($this->_rootref['THOP_SKINS_COLOR'])) ? $this->_rootref['THOP_SKINS_COLOR'] : ''; echo (isset($this->_rootref['THOP_STYLESHEET'])) ? $this->_rootref['THOP_STYLESHEET'] : ''; echo (isset($this->_rootref['THOP_CUSTOMCSS'])) ? $this->_rootref['THOP_CUSTOMCSS'] : ''; echo (isset($this->_rootref['THOP_CUSTOMJS'])) ? $this->_rootref['THOP_CUSTOMJS'] : ''; echo (isset($this->_rootref['THOP_BODY_GOOGLE_FONT'])) ? $this->_rootref['THOP_BODY_GOOGLE_FONT'] : ''; echo (isset($this->_rootref['THOP_HEADERCODE'])) ? $this->_rootref['THOP_HEADERCODE'] : ''; ?>
</head>
<body class="<?php echo (isset($this->_rootref['THOP_BODY_CLASS'])) ? $this->_rootref['THOP_BODY_CLASS'] : ''; ?>">
    <?php if ($this->_rootref['USER_STATUS'] == ('0')) {  ?><div class="activate-account-notes"><?php echo ((isset($this->_rootref['LANG_PLEASE_ACTIVATE_YOUR_ACCOUNT'])) ? $this->_rootref['LANG_PLEASE_ACTIVATE_YOUR_ACCOUNT'] : ((get_languages('PLEASE_ACTIVATE_YOUR_ACCOUNT')) ? get_languages('PLEASE_ACTIVATE_YOUR_ACCOUNT') : '{ PLEASE_ACTIVATE_YOUR_ACCOUNT }')); ?></div><?php } if ($this->_rootref['THOP_PAGE_LOADED']) {  ?>
    <div id="preloader">
        <div class="cube-wrapper">
            <div class="cube-folding"><span class="leaf1"></span><span class="leaf2"></span><span class="leaf3"></span><span class="leaf4"></span></div>
            <span class="loading">Loading...</span>
        </div>
    </div>
    <?php } ?>
    <div id="wrapper">
        <?php if ($this->_rootref['THOP_HEADER_STYLE'] == ('1')) {  $this->_tpl_include('header/header_style1.html'); } else if ($this->_rootref['THOP_HEADER_STYLE'] == ('2')) {  $this->_tpl_include('header/header_style2.html'); } else if ($this->_rootref['THOP_HEADER_STYLE'] == ('3')) {  $this->_tpl_include('header/header_style3.html'); } else if ($this->_rootref['THOP_HEADER_STYLE'] == ('4')) {  $this->_tpl_include('header/header_style4.html'); } else if ($this->_rootref['THOP_HEADER_STYLE'] == ('5')) {  $this->_tpl_include('header/header_style5.html'); } else { $this->_tpl_include('header/header_style1.html'); } ?>