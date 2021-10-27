<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><div class="parallax-window">
    <div id="sub_header">
        <div class="container padding_150" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo (isset($this->_rootref['THOP_SUB_HEADER_TITLE'])) ? $this->_rootref['THOP_SUB_HEADER_TITLE'] : ''; ?></h1>
                    <h4><?php echo (isset($this->_rootref['THOP_SUB_HEADER_TITLE_DESC'])) ? $this->_rootref['THOP_SUB_HEADER_TITLE_DESC'] : ''; ?></h4>
                    <div class="custom-search-input">
                        <form method="get" action="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>/knowledgebase">
                            <div class="input-group">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" id="search" class="search-query" placeholder="<?php echo (isset($this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'])) ? $this->_rootref['THOP_SUB_HEADER_SEARCH_TEXT'] : ''; ?>" value="<?php echo (isset($this->_rootref['SEARCH_TXT'])) ? $this->_rootref['SEARCH_TXT'] : ''; ?>">
                                <input type="submit" value="<?php echo ((isset($this->_rootref['LANG_SEARCH'])) ? $this->_rootref['LANG_SEARCH'] : ((get_languages('SEARCH')) ? get_languages('SEARCH') : '{ SEARCH }')); ?>">
                            </div>
                        </form>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>