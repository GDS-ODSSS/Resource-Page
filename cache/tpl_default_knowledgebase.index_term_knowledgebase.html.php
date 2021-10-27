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
                        <a href="<?php echo (isset($this->_rootref['PERMALINK_KNOWLEDGEBASE'])) ? $this->_rootref['PERMALINK_KNOWLEDGEBASE'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo (isset($this->_rootref['CATEGORIES_TITLE'])) ? $this->_rootref['CATEGORIES_TITLE'] : ''; ?></span>
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
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['KNOWOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('left')) {  $this->_tpl_include('knowledgebase/sidebar_knowledgebase.html'); } ?>
        <div class="<?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['KNOWOP_SIDEBAR']) {  ?>col-lg-9 col-md-8 col-sm-12<?php } else { ?>col-md-12<?php } ?>">
            <div class="head-title">
                <?php if ($this->_rootref['THE_CATEGORIE_ID']) {  ?>
                <h4><?php echo (isset($this->_rootref['CATEGORIES_TITLE'])) ? $this->_rootref['CATEGORIES_TITLE'] : ''; ?></h4>
                <?php } else if ($this->_rootref['IS_SEARCH']) {  ?>
                <h4><?php echo ((isset($this->_rootref['LANG_SEARCH'])) ? $this->_rootref['LANG_SEARCH'] : ((get_languages('SEARCH')) ? get_languages('SEARCH') : '{ SEARCH }')); ?> : <small><?php echo (isset($this->_rootref['SEARCH_TXT'])) ? $this->_rootref['SEARCH_TXT'] : ''; ?></small></h4>
                <?php } else { ?>
                <h4><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></h4>
                <?php } ?>
            </div>
            <?php if ($this->_rootref['IS_SEARCH'] && $this->_rootref['SEARCH_FOUND'] == ('0')) {  ?>
            <div class="alert alert-warning text-center" role="alert"><?php echo ((isset($this->_rootref['LANG_NOT_FOUND'])) ? $this->_rootref['LANG_NOT_FOUND'] : ((get_languages('NOT_FOUND')) ? get_languages('NOT_FOUND') : '{ NOT_FOUND }')); ?>!</div>
            <?php } ?>
            
            <ul class="knowledgebase-list">
            <?php $_loop_knowledgebase_count = (isset($this->_tpldata['loop_knowledgebase'])) ? sizeof($this->_tpldata['loop_knowledgebase']) : 0;if ($_loop_knowledgebase_count) {for ($_loop_knowledgebase_i = 0; $_loop_knowledgebase_i < $_loop_knowledgebase_count; ++$_loop_knowledgebase_i){$_loop_knowledgebase_val = &$this->_tpldata['loop_knowledgebase'][$_loop_knowledgebase_i]; ?>
                <li>
                    <h4><a href="<?php echo $_loop_knowledgebase_val['POST_PERMANETLINK']; ?>"><?php echo $_loop_knowledgebase_val['POST_TITLE']; ?></a></h4>
                    <div class="post-meta">
                        <span class="meta-author"><i class="pe-7s-user"></i> <?php echo $_loop_knowledgebase_val['POST_AUTHOR']; ?></span>
                        <span class="meta-comment"><i class="pe-7s-look"></i> <?php echo $_loop_knowledgebase_val['POST_VIEW']; ?> <?php echo ((isset($this->_rootref['LANG_VIEWS'])) ? $this->_rootref['LANG_VIEWS'] : ((get_languages('VIEWS')) ? get_languages('VIEWS') : '{ VIEWS }')); ?></span>
                        <span class="meta-date"><i class="pe-7s-date"></i> <a href="#"><?php echo $_loop_knowledgebase_val['POST_TIME_AGO']; ?></a></span>
                        <span class="meta-categories"><i class="pe-7s-ticket"></i> <a href="<?php echo $_loop_knowledgebase_val['POST_TERM_PERMANETLINK']; ?>"><?php echo $_loop_knowledgebase_val['POST_TERM_NAME']; ?></a></span>
                    </div>
                </li>
            <?php }} ?>
            </ul>
            <?php if ($this->_rootref['SHOW_PAGINATION']) {  ?><nav class="navigation pagination"><div class="nav-links"><?php echo (isset($this->_rootref['PAGINATION'])) ? $this->_rootref['PAGINATION'] : ''; ?></div></nav><?php } ?>
        </div>
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['KNOWOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('right')) {  $this->_tpl_include('knowledgebase/sidebar_knowledgebase.html'); } ?>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>