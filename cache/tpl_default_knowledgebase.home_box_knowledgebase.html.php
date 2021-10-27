<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['FAQOP_BOX_HOME']) {  ?>
<div class="<?php if ($this->_rootref['KNOWOP_COLUMN']) {  echo (isset($this->_rootref['KNOWOP_COLUMN'])) ? $this->_rootref['KNOWOP_COLUMN'] : ''; } else { ?>col-md-4<?php } ?> <?php echo (isset($this->_rootref['KNOWOP_CLASSES'])) ? $this->_rootref['KNOWOP_CLASSES'] : ''; ?>">
    <div class="services color1">
        <?php if ($this->_rootref['KNOWOP_IMAGE']) {  ?>
        <div class="image"><img src="<?php echo (isset($this->_rootref['KNOWOP_IMAGE'])) ? $this->_rootref['KNOWOP_IMAGE'] : ''; ?>" /></div>
        <?php } else { ?>
        <div class="icon"><i class="<?php if ($this->_rootref['KNOWOP_ICON']) {  echo (isset($this->_rootref['KNOWOP_ICON'])) ? $this->_rootref['KNOWOP_ICON'] : ''; } else { ?>fa fa-life-ring<?php } ?>"></i></div>
        <?php } ?>
        <h4><a href="<?php echo (isset($this->_rootref['PERMALINK_KNOWLEDGEBASE'])) ? $this->_rootref['PERMALINK_KNOWLEDGEBASE'] : ''; ?>"><?php if ($this->_rootref['KNOWOP_TITLE']) {  echo (isset($this->_rootref['KNOWOP_TITLE'])) ? $this->_rootref['KNOWOP_TITLE'] : ''; } else { echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); } ?></a></h4>
        <p class="description"><?php if ($this->_rootref['KNOWOP_DESCRIPTION']) {  echo (isset($this->_rootref['KNOWOP_DESCRIPTION'])) ? $this->_rootref['KNOWOP_DESCRIPTION'] : ''; } ?></p>
        <a class="link" href="<?php echo (isset($this->_rootref['PERMALINK_KNOWLEDGEBASE'])) ? $this->_rootref['PERMALINK_KNOWLEDGEBASE'] : ''; ?>"><?php if ($this->_rootref['KNOWOP_TEXT_LINK']) {  echo (isset($this->_rootref['KNOWOP_TEXT_LINK'])) ? $this->_rootref['KNOWOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_CHECK_AN_ARTICLE'])) ? $this->_rootref['LANG_CHECK_AN_ARTICLE'] : ((get_languages('CHECK_AN_ARTICLE')) ? get_languages('CHECK_AN_ARTICLE') : '{ CHECK_AN_ARTICLE }')); } ?></a>
    </div>
</div>
<?php } ?>