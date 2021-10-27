<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['POST_COMMENT_STATS'] && $this->_rootref['THOP_DISQUS_STATUS']) {  ?>
<div id="disqus_thread"></div>
<script>
    (function () {
        var d = document, s = d.createElement('script');
        s.src = 'https://<?php echo (isset($this->_rootref['THOP_DISQUS_USERNAME'])) ? $this->_rootref['THOP_DISQUS_USERNAME'] : ''; ?>.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<?php } ?>