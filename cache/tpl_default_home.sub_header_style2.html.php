<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><div id="sub_header" class="bg-effectiv">
    <div class="container padding_150_180" id="sub_content">
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
    <div class="effectiv-container">
        <div class="effectiv-animation">
            <!-- Wave Animation -->
            <svg class="effectiv" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff"></use>
                </g>
            </svg>
        </div>
    </div>
</div>