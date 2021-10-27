<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['FAQOP_BOX_HOME']) {  ?>
<div class="<?php if ($this->_rootref['FAQOP_COLUMN']) {  echo (isset($this->_rootref['FAQOP_COLUMN'])) ? $this->_rootref['FAQOP_COLUMN'] : ''; } else { ?>col-md-4<?php } ?> <?php echo (isset($this->_rootref['FAQOP_CLASSES'])) ? $this->_rootref['FAQOP_CLASSES'] : ''; ?>">
    <div class="services color4">
        <?php if ($this->_rootref['FAQOP_IMAGE']) {  ?>
        <div class="image"><img src="<?php echo (isset($this->_rootref['FAQOP_IMAGE'])) ? $this->_rootref['FAQOP_IMAGE'] : ''; ?>" /></div>
        <?php } else { ?>
        <div class="icon"><i class="<?php if ($this->_rootref['FAQOP_ICON']) {  echo (isset($this->_rootref['FAQOP_ICON'])) ? $this->_rootref['FAQOP_ICON'] : ''; } else { ?>fa fa-question-circle<?php } ?>"></i></div>
        <?php } ?>
        <h4><a href="<?php echo (isset($this->_rootref['PERMALINK_FAQS'])) ? $this->_rootref['PERMALINK_FAQS'] : ''; ?>"><?php if ($this->_rootref['FAQOP_TITLE']) {  echo (isset($this->_rootref['FAQOP_TITLE'])) ? $this->_rootref['FAQOP_TITLE'] : ''; } else { echo ((isset($this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'])) ? $this->_rootref['LANG_FREQUENTLY_ASKED_QUESTIONS'] : ((get_languages('FREQUENTLY_ASKED_QUESTIONS')) ? get_languages('FREQUENTLY_ASKED_QUESTIONS') : '{ FREQUENTLY_ASKED_QUESTIONS }')); } ?></a></h4>
        <p class="description"><?php if ($this->_rootref['FAQOP_DESCRIPTION']) {  echo (isset($this->_rootref['FAQOP_DESCRIPTION'])) ? $this->_rootref['FAQOP_DESCRIPTION'] : ''; } ?></p>
        <a class="link" href="<?php echo (isset($this->_rootref['PERMALINK_FAQS'])) ? $this->_rootref['PERMALINK_FAQS'] : ''; ?>"><?php if ($this->_rootref['FAQOP_TEXT_LINK']) {  echo (isset($this->_rootref['FAQOP_TEXT_LINK'])) ? $this->_rootref['FAQOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_SEE_ANSWERS'])) ? $this->_rootref['LANG_SEE_ANSWERS'] : ((get_languages('SEE_ANSWERS')) ? get_languages('SEE_ANSWERS') : '{ SEE_ANSWERS }')); } ?></a>
    </div>
</div>
<?php } ?>