<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'])) ? $this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'] : ((get_languages('FREQUENTLY_ASKED_QUESTIONS')) ? get_languages('FREQUENTLY_ASKED_QUESTIONS') : '{ FREQUENTLY_ASKED_QUESTIONS }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_FAQS'])) ? $this->_rootref['LANG_FAQS'] : ((get_languages('FAQS')) ? get_languages('FAQS') : '{ FAQS }')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<div class="container padding_80">
    <div class="row">
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['FAQOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('left')) {  $this->_tpl_include('faqs/sidebar_faqs.html'); } ?>
        <div class="<?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['FAQOP_SIDEBAR']) {  ?>col-lg-9 col-md-8 col-sm-12<?php } else { ?>col-md-12<?php } ?>">
            
            <div class="accordion toggle-accordion">
                <?php $_loop_faqs_count = (isset($this->_tpldata['loop_faqs'])) ? sizeof($this->_tpldata['loop_faqs']) : 0;if ($_loop_faqs_count) {for ($_loop_faqs_i = 0; $_loop_faqs_i < $_loop_faqs_count; ++$_loop_faqs_i){$_loop_faqs_val = &$this->_tpldata['loop_faqs'][$_loop_faqs_i]; ?>
                <div class="section-content">
                    <h4 class="accordion-title"><a href="#"><?php echo $_loop_faqs_val['POST_TITLE']; ?><i class="pe-7s-angle-down"></i></a></h4>
                    <div class="accordion-inner">
                        <?php echo $_loop_faqs_val['POST_CONTENT']; ?>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['FAQOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('right')) {  $this->_tpl_include('faqs/sidebar_faqs.html'); } ?>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>