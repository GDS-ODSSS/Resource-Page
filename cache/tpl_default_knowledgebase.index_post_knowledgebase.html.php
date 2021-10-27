<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <a href="<?php echo (isset($this->_rootref['PERMALINK_KNOWLEDGEBASE'])) ? $this->_rootref['PERMALINK_KNOWLEDGEBASE'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_KNOWLEDGEBASE'])) ? $this->_rootref['LANG_KNOWLEDGEBASE'] : ((get_languages('KNOWLEDGEBASE')) ? get_languages('KNOWLEDGEBASE') : '{ KNOWLEDGEBASE }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <a href="<?php echo (isset($this->_rootref['CATEGORIES_PERMANETLINK'])) ? $this->_rootref['CATEGORIES_PERMANETLINK'] : ''; ?>"><?php echo (isset($this->_rootref['CATEGORIES_TITLE'])) ? $this->_rootref['CATEGORIES_TITLE'] : ''; ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current current-post"><?php echo (isset($this->_rootref['POST_TITLE'])) ? $this->_rootref['POST_TITLE'] : ''; ?></span>
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
            <div class="head-title"><h3><?php echo (isset($this->_rootref['POST_TITLE'])) ? $this->_rootref['POST_TITLE'] : ''; ?></h3></div>
            <div class="knowledgebase-content">
                <div class="post-meta">
                    <span class="meta-author"><i class="pe-7s-user"></i> <a href="#"><?php echo (isset($this->_rootref['POST_AUTHOR'])) ? $this->_rootref['POST_AUTHOR'] : ''; ?></a></span>
                    <span class="meta-comment"><i class="pe-7s-look"></i> <a href="#"> <?php echo (isset($this->_rootref['POST_VIEW'])) ? $this->_rootref['POST_VIEW'] : ''; ?> <?php echo ((isset($this->_rootref['LANG_VIEWS'])) ? $this->_rootref['LANG_VIEWS'] : ((get_languages('VIEWS')) ? get_languages('VIEWS') : '{ VIEWS }')); ?></a></span>
                    <span class="meta-date"><i class="pe-7s-date"></i> <a href="#"><?php echo (isset($this->_rootref['POST_TIME_AGO'])) ? $this->_rootref['POST_TIME_AGO'] : ''; ?></a></span>
                    <span class="meta-categories"><i class="pe-7s-ticket"></i> <a href="<?php echo (isset($this->_rootref['POST_TERM_PERMANETLINK'])) ? $this->_rootref['POST_TERM_PERMANETLINK'] : ''; ?>"><?php echo (isset($this->_rootref['POST_TERM_NAME'])) ? $this->_rootref['POST_TERM_NAME'] : ''; ?></a></span>
                </div>
                <div class="highlight"><?php echo (isset($this->_rootref['POST_CONTENT'])) ? $this->_rootref['POST_CONTENT'] : ''; ?></div>
            </div>
            <?php if ($this->_rootref['IS_TAGS']) {  ?>
            <div class="head-title"><h5><?php echo ((isset($this->_rootref['LANG_TAGS'])) ? $this->_rootref['LANG_TAGS'] : ((get_languages('TAGS')) ? get_languages('TAGS') : '{ TAGS }')); ?></h5></div>
            <div class="post-tags">
                <?php $_loop_post_tags_count = (isset($this->_tpldata['loop_post_tags'])) ? sizeof($this->_tpldata['loop_post_tags']) : 0;if ($_loop_post_tags_count) {for ($_loop_post_tags_i = 0; $_loop_post_tags_i < $_loop_post_tags_count; ++$_loop_post_tags_i){$_loop_post_tags_val = &$this->_tpldata['loop_post_tags'][$_loop_post_tags_i]; ?><a href="<?php echo $_loop_post_tags_val['TAG_LINK']; ?>"><?php echo $_loop_post_tags_val['TAG_NAME']; ?></a><?php echo $_loop_post_tags_val['TAG_CHAR']; }} ?>
            </div>
            <?php } if ($this->_rootref['IS_POST_RELATED'] && $this->_rootref['KNOWOP_RELATED']) {  ?>
            <div class="related-post mt-20">
                <div class="head-title"><h5><?php echo ((isset($this->_rootref['LANG_RELATED'])) ? $this->_rootref['LANG_RELATED'] : ((get_languages('RELATED')) ? get_languages('RELATED') : '{ RELATED }')); ?></h5></div>
                <ul class="knowledgebase-list">
                    <?php $_loop_knowledgebase_related_count = (isset($this->_tpldata['loop_knowledgebase_related'])) ? sizeof($this->_tpldata['loop_knowledgebase_related']) : 0;if ($_loop_knowledgebase_related_count) {for ($_loop_knowledgebase_related_i = 0; $_loop_knowledgebase_related_i < $_loop_knowledgebase_related_count; ++$_loop_knowledgebase_related_i){$_loop_knowledgebase_related_val = &$this->_tpldata['loop_knowledgebase_related'][$_loop_knowledgebase_related_i]; ?>
                    <li>
                        <h4><a href="<?php echo $_loop_knowledgebase_related_val['POST_PERMANETLINK']; ?>"><?php echo $_loop_knowledgebase_related_val['POST_TITLE']; ?></a></h4>
                        <div class="post-meta">
                            <span class="meta-author"><i class="pe-7s-user"></i> <?php echo $_loop_knowledgebase_related_val['POST_AUTHOR']; ?></span>
                            <span class="meta-comment"><i class="pe-7s-look"></i> <?php echo $_loop_knowledgebase_related_val['POST_VIEW']; ?> <?php echo ((isset($this->_rootref['LANG_VIEWS'])) ? $this->_rootref['LANG_VIEWS'] : ((get_languages('VIEWS')) ? get_languages('VIEWS') : '{ VIEWS }')); ?></span>
                            <span class="meta-date"><i class="pe-7s-date"></i> <a href="#"><?php echo $_loop_knowledgebase_related_val['POST_TIME_AGO']; ?></a></span>
                            <span class="meta-categories"><i class="pe-7s-ticket"></i> <a href="<?php echo $_loop_knowledgebase_related_val['POST_TERM_PERMANETLINK']; ?>"><?php echo $_loop_knowledgebase_related_val['POST_TERM_NAME']; ?></a></span>
                        </div>
                    </li>
                    <?php }} ?>
                </ul>
            </div>
            <?php } $this->_tpl_include('comments_disqus.html'); ?>
        </div>
        <?php if ($this->_rootref['THOP_SHOW_SIDEBAR'] && $this->_rootref['KNOWOP_SIDEBAR'] && $this->_rootref['THOP_POSITION_SIDEBAR'] == ('right')) {  $this->_tpl_include('knowledgebase/sidebar_knowledgebase.html'); } ?>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>