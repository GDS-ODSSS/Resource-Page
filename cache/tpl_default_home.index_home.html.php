<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); if ($this->_rootref['THOP_SUB_HEADER_STYLE'] == ('1')) {  $this->_tpl_include('home/sub_header_style1.html'); } else if ($this->_rootref['THOP_SUB_HEADER_STYLE'] == ('2')) {  $this->_tpl_include('home/sub_header_style2.html'); } else if ($this->_rootref['THOP_SUB_HEADER_STYLE'] == ('3')) {  $this->_tpl_include('home/sub_header_style3.html'); } else if ($this->_rootref['THOP_SUB_HEADER_STYLE'] == ('4')) {  $this->_tpl_include('home/sub_header_style4.html'); } else { $this->_tpl_include('home/sub_header_style1.html'); } if ($this->_rootref['THOP_BOXS_STATUS']) {  ?>
<div class="padding_80 <?php echo (isset($this->_rootref['THOP_BOXS_CLASS'])) ? $this->_rootref['THOP_BOXS_CLASS'] : ''; ?>">
    <div class="container">
        <div class="main_title">
            <h2><?php echo (isset($this->_rootref['THOP_BOXS_TITLE'])) ? $this->_rootref['THOP_BOXS_TITLE'] : ''; ?></h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <?php $_loop_boxs_home_count = (isset($this->_tpldata['loop_boxs_home'])) ? sizeof($this->_tpldata['loop_boxs_home']) : 0;if ($_loop_boxs_home_count) {for ($_loop_boxs_home_i = 0; $_loop_boxs_home_i < $_loop_boxs_home_count; ++$_loop_boxs_home_i){$this->_tpl_include('documentation/home_box_documentation.html');$this->_tpl_include('knowledgebase/home_box_knowledgebase.html');$this->_tpl_include('faqs/home_box_faqs.html'); }} ?>
        </div>
    </div>
</div>
<?php } $_loop_section_home_count = (isset($this->_tpldata['loop_section_home'])) ? sizeof($this->_tpldata['loop_section_home']) : 0;if ($_loop_section_home_count) {for ($_loop_section_home_i = 0; $_loop_section_home_i < $_loop_section_home_count; ++$_loop_section_home_i){ }} $this->_tpl_include('overall_footer.html'); ?>