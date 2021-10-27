<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['NEWSOP_BOX_HOME']) {  ?>
<div class="<?php if ($this->_rootref['NEWSOP_COLUMN']) {  echo (isset($this->_rootref['NEWSOP_COLUMN'])) ? $this->_rootref['NEWSOP_COLUMN'] : ''; } else { ?>col-md-4<?php } ?> <?php echo (isset($this->_rootref['NEWSOP_CLASSES'])) ? $this->_rootref['NEWSOP_CLASSES'] : ''; ?>">
    <div class="services color5">
        <?php if ($this->_rootref['NEWSOP_IMAGE']) {  ?>
        <div class="image"><img src="<?php echo (isset($this->_rootref['NEWSOP_IMAGE'])) ? $this->_rootref['NEWSOP_IMAGE'] : ''; ?>" /></div>
        <?php } else { ?>
        <div class="icon"><i class="<?php if ($this->_rootref['NEWSOP_ICON']) {  echo (isset($this->_rootref['NEWSOP_ICON'])) ? $this->_rootref['NEWSOP_ICON'] : ''; } else { ?>fa fa-question-circle<?php } ?>"></i></div>
        <?php } ?>
        <h4><a href="<?php echo (isset($this->_rootref['PERMALINK_NEWS'])) ? $this->_rootref['PERMALINK_NEWS'] : ''; ?>"><?php if ($this->_rootref['NEWSOP_TITLE']) {  echo (isset($this->_rootref['NEWSOP_TITLE'])) ? $this->_rootref['NEWSOP_TITLE'] : ''; } else { echo ((isset($this->_rootref['LANG_NEWS'])) ? $this->_rootref['LANG_NEWS'] : ((get_languages('NEWS')) ? get_languages('NEWS') : '{ NEWS }')); } ?></a></h4>
        <p class="description"><?php if ($this->_rootref['NEWSOP_DESCRIPTION']) {  echo (isset($this->_rootref['NEWSOP_DESCRIPTION'])) ? $this->_rootref['NEWSOP_DESCRIPTION'] : ''; } ?></p>
        <a class="link" href="<?php echo (isset($this->_rootref['PERMALINK_NEWS'])) ? $this->_rootref['PERMALINK_NEWS'] : ''; ?>"><?php if ($this->_rootref['NEWSOP_TEXT_LINK']) {  echo (isset($this->_rootref['NEWSOP_TEXT_LINK'])) ? $this->_rootref['NEWSOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_SEE_MORE'])) ? $this->_rootref['LANG_SEE_MORE'] : ((get_languages('SEE_MORE')) ? get_languages('SEE_MORE') : '{ SEE_MORE }')); } ?></a>
    </div>
</div>
<?php } ?>