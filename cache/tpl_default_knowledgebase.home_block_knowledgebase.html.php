<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['HOME_KNOWLEDGEBASE']) {  ?>
<div class="padding_80 <?php echo (isset($this->_rootref['KNOWOP_HOME_CLASS'])) ? $this->_rootref['KNOWOP_HOME_CLASS'] : ''; ?>">
    <div class="container">
        <div class="main_title">
            <h2><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <?php $_terms_knowledgebase_count = (isset($this->_tpldata['terms_knowledgebase'])) ? sizeof($this->_tpldata['terms_knowledgebase']) : 0;if ($_terms_knowledgebase_count) {for ($_terms_knowledgebase_i = 0; $_terms_knowledgebase_i < $_terms_knowledgebase_count; ++$_terms_knowledgebase_i){$_terms_knowledgebase_val = &$this->_tpldata['terms_knowledgebase'][$_terms_knowledgebase_i]; ?>
            <div class="col-md-6 col-sm-12">
                <div class="topics-list">
                    <h5><a href="<?php echo $_terms_knowledgebase_val['TERM_PERMANENT_LINK']; ?>"><?php echo $_terms_knowledgebase_val['TERM_NAME']; ?> <span class="badge"><?php echo $_terms_knowledgebase_val['TERM_COUNT_POSTS']; ?></span></a></h5>
                    <ul>
                        <?php $_loop_knowledgebase_count = (isset($_terms_knowledgebase_val['loop_knowledgebase'])) ? sizeof($_terms_knowledgebase_val['loop_knowledgebase']) : 0;if ($_loop_knowledgebase_count) {for ($_loop_knowledgebase_i = 0; $_loop_knowledgebase_i < $_loop_knowledgebase_count; ++$_loop_knowledgebase_i){$_loop_knowledgebase_val = &$_terms_knowledgebase_val['loop_knowledgebase'][$_loop_knowledgebase_i]; ?> 
                        <li><a href="<?php echo $_loop_knowledgebase_val['POST_PERMANETLINK']; ?>"><i class="pe-7s-note2"></i> <?php echo $_loop_knowledgebase_val['POST_TITLE']; ?></a></li>
                        <?php }} ?>
                    </ul>
                    <a href="<?php echo $_terms_knowledgebase_val['TERM_PERMANENT_LINK']; ?>" class="readmore"><?php echo ((isset($this->_rootref['LANG_VIEW_ALL'])) ? $this->_rootref['LANG_VIEW_ALL'] : ((get_languages('VIEW_ALL')) ? get_languages('VIEW_ALL') : '{ VIEW_ALL }')); ?> <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
            <?php }} ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="home-more">
                    <a href="<?php echo (isset($this->_rootref['PERMALINK_KNOWLEDGEBASE'])) ? $this->_rootref['PERMALINK_KNOWLEDGEBASE'] : ''; ?>"><i class="<?php if ($this->_rootref['KNOWOP_ICON']) {  echo (isset($this->_rootref['KNOWOP_ICON'])) ? $this->_rootref['KNOWOP_ICON'] : ''; } else { ?>fa fa-life-ring<?php } ?>"></i> <?php if ($this->_rootref['KNOWOP_TEXT_LINK']) {  echo (isset($this->_rootref['KNOWOP_TEXT_LINK'])) ? $this->_rootref['KNOWOP_TEXT_LINK'] : ''; } else { echo ((isset($this->_rootref['LANG_CHECK_AN_ARTICLE'])) ? $this->_rootref['LANG_CHECK_AN_ARTICLE'] : ((get_languages('CHECK_AN_ARTICLE')) ? get_languages('CHECK_AN_ARTICLE') : '{ CHECK_AN_ARTICLE }')); } ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>