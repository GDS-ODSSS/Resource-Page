<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_DOCUMENTATION'])) ? $this->_rootref['LANG_DOCUMENTATION'] : ((get_languages('DOCUMENTATION')) ? get_languages('DOCUMENTATION') : '{ DOCUMENTATION }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <a href="<?php echo (isset($this->_rootref['PERMALINK_DOCUMENTATION'])) ? $this->_rootref['PERMALINK_DOCUMENTATION'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_DOCUMENTATION'])) ? $this->_rootref['LANG_DOCUMENTATION'] : ((get_languages('DOCUMENTATION')) ? get_languages('DOCUMENTATION') : '{ DOCUMENTATION }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_TAGS'])) ? $this->_rootref['LANG_TAGS'] : ((get_languages('TAGS')) ? get_languages('TAGS') : '{ TAGS }')); ?> : <?php echo (isset($this->_rootref['TAG_NAME'])) ? $this->_rootref['TAG_NAME'] : ''; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<div class="container padding_80">
    <div class="row">
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['DOCUOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('left')) {  $this->_tpl_include('documentation/sidebar_documentation.html'); } ?>
        <div class="<?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['DOCUOP_SIDEBAR']) {  ?>col-lg-9 col-md-8 col-sm-12<?php } else { ?>col-md-12<?php } ?>">
            <div class="head-title">
                <h4><?php echo ((isset($this->_rootref['LANG_TAGS'])) ? $this->_rootref['LANG_TAGS'] : ((get_languages('TAGS')) ? get_languages('TAGS') : '{ TAGS }')); ?> : <small><?php echo (isset($this->_rootref['TAG_NAME'])) ? $this->_rootref['TAG_NAME'] : ''; ?></small></h4>
            </div>  
            <div class="row">
                <?php $_loop_documentation_count = (isset($this->_tpldata['loop_documentation'])) ? sizeof($this->_tpldata['loop_documentation']) : 0;if ($_loop_documentation_count) {for ($_loop_documentation_i = 0; $_loop_documentation_i < $_loop_documentation_count; ++$_loop_documentation_i){$_loop_documentation_val = &$this->_tpldata['loop_documentation'][$_loop_documentation_i]; ?>
                <div class="<?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['DOCUOP_SIDEBAR']) {  ?>col-md-12<?php } else { ?>col-md-6<?php } ?>">
                    <div class="document-item">
                        <h5><a href="<?php echo $_loop_documentation_val['POST_PERMANETLINK']; ?>"><?php if ($_loop_documentation_val['POST_PIN_POST']) {  ?><i class="fa fa-bolt post_pin"></i><?php } ?> <?php echo $_loop_documentation_val['POST_TITLE']; ?></a></h5>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-user"></i> <?php echo $_loop_documentation_val['POST_AUTHOR']; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-ticket"></i> <a href="<?php echo $_loop_documentation_val['POST_TERM_PERMANETLINK']; ?>"><?php echo $_loop_documentation_val['POST_TERM_NAME']; ?></a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-clock"></i> <?php echo $_loop_documentation_val['POST_MODIFIED']; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-anchor"></i> <?php echo ((isset($this->_rootref['LANG_VERSION'])) ? $this->_rootref['LANG_VERSION'] : ((get_languages('VERSION')) ? get_languages('VERSION') : '{ VERSION }')); ?> <?php echo $_loop_documentation_val['POST_VERSION']; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-notebook"></i> <a target="_blank" href="<?php echo $_loop_documentation_val['POST_ONLINEDOCUMENT']; ?>"><?php echo ((isset($this->_rootref['LANG_ONLINE_DOCUMENT'])) ? $this->_rootref['LANG_ONLINE_DOCUMENT'] : ((get_languages('ONLINE_DOCUMENT')) ? get_languages('ONLINE_DOCUMENT') : '{ ONLINE_DOCUMENT }')); ?></a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <i class="pe-7s-cloud-download"></i> <a target="_blank" href="<?php echo $_loop_documentation_val['POST_DOWNLOAD']; ?>"><?php echo ((isset($this->_rootref['LANG_DOWNLOAD'])) ? $this->_rootref['LANG_DOWNLOAD'] : ((get_languages('DOWNLOAD')) ? get_languages('DOWNLOAD') : '{ DOWNLOAD }')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }} ?>
            </div>
            <?php if ($this->_rootref['SHOW_PAGINATION']) {  ?><nav class="navigation pagination"><div class="nav-links"><?php echo (isset($this->_rootref['PAGINATION'])) ? $this->_rootref['PAGINATION'] : ''; ?></div></nav><?php } ?>
        </div>
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['DOCUOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('right')) {  $this->_tpl_include('documentation/sidebar_documentation.html'); } ?>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>