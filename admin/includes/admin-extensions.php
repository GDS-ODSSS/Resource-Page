<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2021.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.7                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
if (!defined('IN_PHPMEGATEMP_CP')){exit;}

class admin_extensions
{
    public $categories      = array('count' => 0);
    public $extensionscount = 0;
    protected $available    = array();
    protected $activated    = array();
    private $headers        = array(
		'name'        => 'Extension Name',
		'website'     => 'Extension URI',
		'version'     => 'Version',
		'requires'    => 'Requires',
		'description' => 'Description',
		'author'      => 'Author',
		'authoruri'   => 'Author URI',
		'category'    => 'Category',
	);
    // construct
	public function __construct() {
	  
	}
    
    public function index_extensions()
    {
        global $hooks;
        admin_header(get_admin_languages('extensions'));
        admin_content_header(array(
            'title' => get_admin_languages('extensions'), 
            'linkmore' => array('title' => get_admin_languages('add_extensions'), 'link' => 'http://nawaaugustine.com/php-help-manager/extensions')
        ));
        
        admin_content_section_start();
        $this->load();
        if (isset($_REQUEST['action']) and in_array($_REQUEST['action'], array('activate','deactivate')) and isset($_REQUEST['checked'])):
            $action  = $_REQUEST['action'];
            $checked = $_REQUEST['checked'];
        	if ( $action === 'activate' ):
        		$errors = $this->activate( $checked );
        	elseif ( $action === 'deactivate' ):
        		$errors = $this->deactivate( $checked );
        	endif;
            @header("Location:extensions.php");
        endif;
        $fetched_extensions = $this->fetch();
        echo '<div class="col-md-12"><div class="theme-browser"><div class="themes extensions-page"><div class="row">';
            foreach ( $fetched_extensions as $directory => $extension ):
            echo '<div class="col-md-3"><div class="theme"><div class="theme-screenshot"><img src="'.$extension['screenshot'].'" alt="'.$extension['name'].'"></div>
                <footer>
                    <h4>'.$extension['name'].'</h4>
                    <div class="extensions-version-author-uri">
                        '.get_admin_languages('version').' '.$extension['version'].' | '.get_admin_languages('by').' <a href="'.$extension['authoruri'].'" target="_blank">'.$extension['author'].'</a>
                    </div>
                    <div class="extensions-row-actions">';
					if ( $extension['compatible'] ): 
						if ( $extension['activated'] ): 
                            echo '<a class="btn btn-small btn-danger" href="extensions.php?action=deactivate&checked='.$directory.'">'.get_admin_languages('deactivate').'</a> ';
						else: 
                            echo '<a class="btn btn-small btn-success" href="extensions.php?action=activate&checked='.$directory.'">'.get_admin_languages('activate' ).'</a> ';
						endif;
                    else: 
                        echo '<a class="btn btn-small btn-success" href="#" target="_blank">'.get_admin_languages('requires_version').' '.$extension['requires'].'</a> ';
        			endif;
                        echo '<a class="btn btn-small btn-info" href="'.$extension['website'].'" target="_blank">'.get_admin_languages('extension_page').'</a>';
					echo '</div></footer></div></div>';
            endforeach;
        echo '</div></div></div></div>';
        admin_content_section_end();
        admin_footer();
    }
    // Get activated extensions
    function load() {
        global $config;
		return $this->activated = (is_serialized($config['start_extensions']))? maybe_unserialize($config['start_extensions']) : array() ;
	}
    // fetch extensions
    function fetch() {
		// Fetch extensions directory
		$extensions = glob( ABSPATH . 'extensions/*', GLOB_ONLYDIR );
        if ( ! empty( $extensions ) ):
			$this->load();
		endif;
		foreach ( $extensions as $extension ):
			$slug = basename( $extension );
			$file = $extension . '/extension.php';
			if ( file_exists( $file ) ):
                $this->extensionscount ++;
				$headers                    = get_file_data( $file, $this->headers );
				$headers['compatible']      = version_compare( $headers['requires'], SCRIPT_VERSION, '<=' );
				$headers['activated']       = $headers['compatible'] && in_array( $slug, $this->activated );
				$headers['basename']        = $slug . '/extension.php';
                $headers['screenshot']      = '../extensions/' . $slug . '/screenshot.png';
				$this->available[ $slug ]   = $headers;
			endif;
		endforeach;
		return $this->available;
	}
    // activate
    function activate( $extensions ) {
		$activated = array_unique( array_merge( $this->activated, (array) $extensions ) );
		return set_config( 'start_extensions', maybe_serialize($activated) );
	}
    // deactivate
	function deactivate( $extensions ) {
		$activated = array_diff( $this->activated, (array) $extensions );
		return set_config( 'start_extensions', maybe_serialize($activated) );
	}
}    
?>