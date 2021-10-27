<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><aside class="col-lg-3 col-md-4 col-sm-12 sidebar sticky-sidebar">
    <div class="theiaStickySidebar">
        <?php $this->_tpl_include('banner_sidebar.html'); if ($this->_rootref['DOCUOP_WIDGET_SEARCH']) {  ?>
        <div class="widget widget-search-input">
            <div class="widget-title"><i class="pe-7s-search"></i> <?php echo ((isset($this->_rootref['LANG_SEARCH'])) ? $this->_rootref['LANG_SEARCH'] : ((get_languages('SEARCH')) ? get_languages('SEARCH') : '{ SEARCH }')); ?></div>
            <form action="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>/documentation" method="get">
                <div class="box-input">
                    <input class="input" type="text" name="search" placeholder="<?php if ($this->_rootref['DOCUOP_SEARCH_TEXT']) {  echo (isset($this->_rootref['DOCUOP_SEARCH_TEXT'])) ? $this->_rootref['DOCUOP_SEARCH_TEXT'] : ''; } else { echo (isset($this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'])) ? $this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'] : ''; } ?>" maxlength="50" value="<?php echo (isset($this->_rootref['SEARCH_TXT'])) ? $this->_rootref['SEARCH_TXT'] : ''; ?>">
                </div>
            </form>
        </div>
        <?php } if ($this->_rootref['DOCUOP_WIDGET_CATEGORIES']) {  ?>
        <div class="widget widget-list">
            <div class="widget-title"><i class="pe-7s-ticket"></i> <?php echo ((isset($this->_rootref['LANG_CATEGORIES'])) ? $this->_rootref['LANG_CATEGORIES'] : ((get_languages('CATEGORIES')) ? get_languages('CATEGORIES') : '{ CATEGORIES }')); ?></div>
            <ul>
                <?php $_terms_documentation_count = (isset($this->_tpldata['terms_documentation'])) ? sizeof($this->_tpldata['terms_documentation']) : 0;if ($_terms_documentation_count) {for ($_terms_documentation_i = 0; $_terms_documentation_i < $_terms_documentation_count; ++$_terms_documentation_i){$_terms_documentation_val = &$this->_tpldata['terms_documentation'][$_terms_documentation_i]; ?>
                <li class="cat-item <?php if ($_terms_documentation_val['TERM_ID'] == $this->_rootref['THE_CATEGORIE_ID']) {  ?>active<?php } ?>">
					<a href="<?php echo $_terms_documentation_val['TERM_PERMANENT_LINK']; ?>">
						<?php echo $_terms_documentation_val['TERM_NAME']; ?>
						<span class="count"><?php echo $_terms_documentation_val['TERM_COUNT_POSTS']; ?></span>
					</a>
				</li>
                <?php }} ?>
            </ul>
        </div>
        <?php } if ($this->_rootref['DOCUOP_WIDGET_RECENT']) {  ?>
        <div class="widget widget-list">
            <div class="widget-title"><i class="pe-7s-gleam"></i> <?php echo ((isset($this->_rootref['LANG_RECENT'])) ? $this->_rootref['LANG_RECENT'] : ((get_languages('RECENT')) ? get_languages('RECENT') : '{ RECENT }')); ?></div>
            <ul>
                <?php $_loop_recent_documentation_count = (isset($this->_tpldata['loop_recent_documentation'])) ? sizeof($this->_tpldata['loop_recent_documentation']) : 0;if ($_loop_recent_documentation_count) {for ($_loop_recent_documentation_i = 0; $_loop_recent_documentation_i < $_loop_recent_documentation_count; ++$_loop_recent_documentation_i){$_loop_recent_documentation_val = &$this->_tpldata['loop_recent_documentation'][$_loop_recent_documentation_i]; ?>
                <li><a href="<?php echo $_loop_recent_documentation_val['POST_PERMANETLINK']; ?>"><?php echo $_loop_recent_documentation_val['POST_TITLE']; ?></a></li>
                <?php }} ?>
            </ul>
        </div>
        <?php } if ($this->_rootref['DOCUOP_WIDGET_POPULAR']) {  ?>
        <div class="widget widget-list">
            <div class="widget-title"><i class="pe-7s-like"></i> <?php echo ((isset($this->_rootref['LANG_POPULAR'])) ? $this->_rootref['LANG_POPULAR'] : ((get_languages('POPULAR')) ? get_languages('POPULAR') : '{ POPULAR }')); ?></div>
            <ul>
                <?php $_loop_popular_documentation_count = (isset($this->_tpldata['loop_popular_documentation'])) ? sizeof($this->_tpldata['loop_popular_documentation']) : 0;if ($_loop_popular_documentation_count) {for ($_loop_popular_documentation_i = 0; $_loop_popular_documentation_i < $_loop_popular_documentation_count; ++$_loop_popular_documentation_i){$_loop_popular_documentation_val = &$this->_tpldata['loop_popular_documentation'][$_loop_popular_documentation_i]; ?>
                <li><a href="<?php echo $_loop_popular_documentation_val['POST_PERMANETLINK']; ?>"><?php echo $_loop_popular_documentation_val['POST_TITLE']; ?></a></li>
                <?php }} ?>
            </ul>
        </div>
        <?php } if ($this->_rootref['DOCUOP_WIDGET_TAGS']) {  ?>
        <div class="widget widget-list">
            <div class="widget-title"><i class="pe-7s-ticket"></i> <?php echo ((isset($this->_rootref['LANG_TAGS'])) ? $this->_rootref['LANG_TAGS'] : ((get_languages('TAGS')) ? get_languages('TAGS') : '{ TAGS }')); ?></div>
            <div class="post-tags">
                <?php $_loop_widget_tags_count = (isset($this->_tpldata['loop_widget_tags'])) ? sizeof($this->_tpldata['loop_widget_tags']) : 0;if ($_loop_widget_tags_count) {for ($_loop_widget_tags_i = 0; $_loop_widget_tags_i < $_loop_widget_tags_count; ++$_loop_widget_tags_i){$_loop_widget_tags_val = &$this->_tpldata['loop_widget_tags'][$_loop_widget_tags_i]; ?>
                <a href="<?php echo $_loop_widget_tags_val['TAG_LINK']; ?>"><?php echo $_loop_widget_tags_val['TAG_NAME']; ?> (<?php echo $_loop_widget_tags_val['TAG_COUNT']; ?>)</a>
                <?php }} ?>
            </div>
        </div>
        <?php } ?>
    </div>
</aside>