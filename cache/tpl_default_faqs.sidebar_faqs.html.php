<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><aside class="col-lg-3 col-md-4 col-sm-12 sidebar sticky-sidebar">
    <div class="theiaStickySidebar">
        <!-- widget -->
        <div class="widget widget-list">
            <div class="widget-title">
                <i class="pe-7s-ticket"></i> <?php echo ((isset($this->_rootref['LANG_CATEGORIES'])) ? $this->_rootref['LANG_CATEGORIES'] : ((get_languages('CATEGORIES')) ? get_languages('CATEGORIES') : '{ CATEGORIES }')); ?>
            </div>
            <ul>
                <?php $_terms_faqs_count = (isset($this->_tpldata['terms_faqs'])) ? sizeof($this->_tpldata['terms_faqs']) : 0;if ($_terms_faqs_count) {for ($_terms_faqs_i = 0; $_terms_faqs_i < $_terms_faqs_count; ++$_terms_faqs_i){$_terms_faqs_val = &$this->_tpldata['terms_faqs'][$_terms_faqs_i]; ?>
                <li class="cat-item <?php if ($_terms_faqs_val['TERM_ID'] == $this->_rootref['THE_CATEGORIE_ID']) {  ?>active<?php } ?>">
					<a href="<?php echo $_terms_faqs_val['TERM_PERMANENT_LINK']; ?>">
						<?php echo $_terms_faqs_val['TERM_NAME']; ?>
						<span class="count"><?php echo $_terms_faqs_val['TERM_COUNT_POSTS']; ?></span>
					</a>
				</li>
                <?php }} ?>
            </ul>
        </div>
    </div>
</aside>