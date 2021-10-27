<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['HOME_DOCUMENTATION']) {  ?>
<div class="padding_80 <?php echo (isset($this->_rootref['DOCUOP_HOME_CLASS'])) ? $this->_rootref['DOCUOP_HOME_CLASS'] : ''; ?>">
    <div class="container">
        <div class="main_title">
            <h2><?php echo ((isset($this->_rootref['LANG_DOCUMENTATION'])) ? $this->_rootref['LANG_DOCUMENTATION'] : ((get_languages('DOCUMENTATION')) ? get_languages('DOCUMENTATION') : '{ DOCUMENTATION }')); ?></h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <?php $_loop_documentation_count = (isset($this->_tpldata['loop_documentation'])) ? sizeof($this->_tpldata['loop_documentation']) : 0;if ($_loop_documentation_count) {for ($_loop_documentation_i = 0; $_loop_documentation_i < $_loop_documentation_count; ++$_loop_documentation_i){$_loop_documentation_val = &$this->_tpldata['loop_documentation'][$_loop_documentation_i]; ?>
            <div class="col-md-6 col-sm-6 col-12">
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
        <div class="row">
            <div class="col-md-12">
                <div class="home-more">
                    <a href="<?php echo (isset($this->_rootref['PERMALINK_DOCUMENTATION'])) ? $this->_rootref['PERMALINK_DOCUMENTATION'] : ''; ?>"><i class="<?php if ($this->_rootref['DOCUOP_ICON']) {  echo (isset($this->_rootref['DOCUOP_ICON'])) ? $this->_rootref['DOCUOP_ICON'] : ''; } else { ?>fa fa-files-o<?php } ?>"></i> <?php if ($this->_rootref['DOCUOP_TEXT_LINK']) {  echo (isset($this->_rootref['DOCUOP_TEXT_LINK'])) ? $this->_rootref['DOCUOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_VIEW_DOCUMENTATION'])) ? $this->_rootref['LANG_VIEW_DOCUMENTATION'] : ((get_languages('VIEW_DOCUMENTATION')) ? get_languages('VIEW_DOCUMENTATION') : '{ VIEW_DOCUMENTATION }')); } ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>