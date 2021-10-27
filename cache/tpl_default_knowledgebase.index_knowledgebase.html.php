<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container padding_80" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></span>
                    </div>
                    <div class="custom-search-input">
                        <form method="get" action="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>/knowledgebase">
                            <div class="input-group">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" id="search" class="search-query" placeholder="<?php echo (isset($this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'])) ? $this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'] : ''; ?>" value="<?php echo (isset($this->_rootref['SEARCH_TXT'])) ? $this->_rootref['SEARCH_TXT'] : ''; ?>" />
                                <input type="submit" value="<?php echo ((isset($this->_rootref['LANG_SEARCH'])) ? $this->_rootref['LANG_SEARCH'] : ((get_languages('SEARCH')) ? get_languages('SEARCH') : '{ SEARCH }')); ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<div class="container padding_80">
    <div class="row">
        <?php $_terms_knowledgebase_count = (isset($this->_tpldata['terms_knowledgebase'])) ? sizeof($this->_tpldata['terms_knowledgebase']) : 0;if ($_terms_knowledgebase_count) {for ($_terms_knowledgebase_i = 0; $_terms_knowledgebase_i < $_terms_knowledgebase_count; ++$_terms_knowledgebase_i){$_terms_knowledgebase_val = &$this->_tpldata['terms_knowledgebase'][$_terms_knowledgebase_i]; ?>
        <div class="col-md-6">
            <div class="topics-list">
                <h5><a href="<?php echo $_terms_knowledgebase_val['TERM_PERMANENT_LINK']; ?>"><?php echo $_terms_knowledgebase_val['TERM_NAME']; ?> <span class="badge"><?php echo $_terms_knowledgebase_val['TERM_COUNT_POSTS']; ?></span></a></h5>
                <ul>
                    <?php $_loop_knowledgebase_count = (isset($_terms_knowledgebase_val['loop_knowledgebase'])) ? sizeof($_terms_knowledgebase_val['loop_knowledgebase']) : 0;if ($_loop_knowledgebase_count) {for ($_loop_knowledgebase_i = 0; $_loop_knowledgebase_i < $_loop_knowledgebase_count; ++$_loop_knowledgebase_i){$_loop_knowledgebase_val = &$_terms_knowledgebase_val['loop_knowledgebase'][$_loop_knowledgebase_i]; ?> 
                    <li><a href="<?php echo $_loop_knowledgebase_val['POST_PERMANETLINK']; ?>" title="<?php echo $_loop_knowledgebase_val['POST_TITLE']; ?>"><i class="pe-7s-note2"></i> <?php echo $_loop_knowledgebase_val['POST_TITLE']; ?></a></li>
                    <?php }} ?>
                </ul>
                <a href="<?php echo $_terms_knowledgebase_val['TERM_PERMANENT_LINK']; ?>" class="readmore"><?php echo ((isset($this->_rootref['LANG_VIEW_ALL'])) ? $this->_rootref['LANG_VIEW_ALL'] : ((get_languages('VIEW_ALL')) ? get_languages('VIEW_ALL') : '{ VIEW_ALL }')); ?> <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
        <?php }} ?>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>