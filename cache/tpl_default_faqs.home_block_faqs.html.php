<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['HOME_FAQS']) {  ?>
<div class="padding_80 <?php echo (isset($this->_rootref['FAQOP_HOME_CLASS'])) ? $this->_rootref['FAQOP_HOME_CLASS'] : ''; ?>">
    <div class="container">
        <div class="main_title">
            <h2><?php echo ((isset($this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'])) ? $this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'] : ((get_languages('FREQUENTLY_ASKED_QUESTIONS')) ? get_languages('FREQUENTLY_ASKED_QUESTIONS') : '{ FREQUENTLY_ASKED_QUESTIONS }')); ?></h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="accordion toggle-accordion">
                    <?php $_loop_faqs_count = (isset($this->_tpldata['loop_faqs'])) ? sizeof($this->_tpldata['loop_faqs']) : 0;if ($_loop_faqs_count) {for ($_loop_faqs_i = 0; $_loop_faqs_i < $_loop_faqs_count; ++$_loop_faqs_i){$_loop_faqs_val = &$this->_tpldata['loop_faqs'][$_loop_faqs_i]; ?>
                    <div class="section-content">
                        <h4 class="accordion-title"><a href="#"><?php echo $_loop_faqs_val['POST_TITLE']; ?><i class="pe-7s-angle-down"></i></a></h4>
                        <div class="accordion-inner"><?php echo $_loop_faqs_val['POST_CONTENT']; ?></div>
                    </div>
                    <?php }} ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="home-more">
                    <a href="<?php echo (isset($this->_rootref['PERMALINK_FAQS'])) ? $this->_rootref['PERMALINK_FAQS'] : ''; ?>"><i class="pe-7s-help1"></i> <?php echo ((isset($this->_rootref['LANG_SEE_ANSWERS'])) ? $this->_rootref['LANG_SEE_ANSWERS'] : ((get_languages('SEE_ANSWERS')) ? get_languages('SEE_ANSWERS') : '{ SEE_ANSWERS }')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>