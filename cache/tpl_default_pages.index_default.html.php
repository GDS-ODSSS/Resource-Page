<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo (isset($this->_rootref['POST_TITLE'])) ? $this->_rootref['POST_TITLE'] : ''; ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo (isset($this->_rootref['POST_TITLE'])) ? $this->_rootref['POST_TITLE'] : ''; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<div class="container padding_80">
    <div class="row">
        <div class="col-md-12">
            <!-- single post -->
            <div class="post-inner">
                <div class="post-content">
                    <?php echo (isset($this->_rootref['POST_CONTENT'])) ? $this->_rootref['POST_CONTENT'] : ''; ?>
                </div><!-- end post content -->
                <div class="clearfix"></div>
            </div><!-- end single post -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $this->_tpl_include('comments_disqus.html'); ?>
        </div>
    </div>
</div>
<?php $this->_tpl_include('overall_footer.html'); ?>