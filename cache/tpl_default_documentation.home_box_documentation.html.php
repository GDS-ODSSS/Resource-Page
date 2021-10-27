<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['FAQOP_BOX_HOME']) {  ?>
<div class="<?php if ($this->_rootref['DOCUOP_COLUMN']) {  echo (isset($this->_rootref['DOCUOP_COLUMN'])) ? $this->_rootref['DOCUOP_COLUMN'] : ''; } else { ?>col-md-4<?php } ?> <?php echo (isset($this->_rootref['DOCUOP_CLASSES'])) ? $this->_rootref['DOCUOP_CLASSES'] : ''; ?>">
    <div class="services color2">
        <?php if ($this->_rootref['DOCUOP_IMAGE']) {  ?>
        <div class="image"><img src="<?php echo (isset($this->_rootref['DOCUOP_IMAGE'])) ? $this->_rootref['DOCUOP_IMAGE'] : ''; ?>" /></div>
        <?php } else { ?>
        <div class="icon"><i class="<?php if ($this->_rootref['DOCUOP_ICON']) {  echo (isset($this->_rootref['DOCUOP_ICON'])) ? $this->_rootref['DOCUOP_ICON'] : ''; } else { ?>fa fa-files-o<?php } ?>"></i></div>
        <?php } ?>
        <h4><a href="<?php echo (isset($this->_rootref['PERMALINK_DOCUMENTATION'])) ? $this->_rootref['PERMALINK_DOCUMENTATION'] : ''; ?>"><?php if ($this->_rootref['DOCUOP_TITLE']) {  echo (isset($this->_rootref['DOCUOP_TITLE'])) ? $this->_rootref['DOCUOP_TITLE'] : ''; } else { echo ((isset($this->_rootref['LANG_DOCUMENTATION'])) ? $this->_rootref['LANG_DOCUMENTATION'] : ((get_languages('DOCUMENTATION')) ? get_languages('DOCUMENTATION') : '{ DOCUMENTATION }')); } ?></a></h4>
        <p class="description"><?php if ($this->_rootref['DOCUOP_DESCRIPTION']) {  echo (isset($this->_rootref['DOCUOP_DESCRIPTION'])) ? $this->_rootref['DOCUOP_DESCRIPTION'] : ''; } ?></p>
        <a class="link" href="<?php echo (isset($this->_rootref['PERMALINK_DOCUMENTATION'])) ? $this->_rootref['PERMALINK_DOCUMENTATION'] : ''; ?>"><?php if ($this->_rootref['DOCUOP_TEXT_LINK']) {  echo (isset($this->_rootref['DOCUOP_TEXT_LINK'])) ? $this->_rootref['DOCUOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_VIEW_DOCUMENTATION'])) ? $this->_rootref['LANG_VIEW_DOCUMENTATION'] : ((get_languages('VIEW_DOCUMENTATION')) ? get_languages('VIEW_DOCUMENTATION') : '{ VIEW_DOCUMENTATION }')); } ?></a>
    </div>
</div>
<?php } ?>