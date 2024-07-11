<?php

function printTruncated( $maxLength, $html, $isUtf8 = true ) {
	$printedLength = 0;
	$position = 0;
	$tags = array();

	// For UTF-8, we need to count multibyte sequences as one character.
	$re = $isUtf8
		? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
		: '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

	while ( $printedLength < $maxLength && preg_match( $re, $html, $match, PREG_OFFSET_CAPTURE, $position ) ) {
		list( $tag, $tagPosition ) = $match[0];

		// Print text leading up to the tag.
		$str = substr( $html, $position, $tagPosition - $position );
		if ( $printedLength + strlen( $str ) > $maxLength ) {
			print ( substr( $str, 0, $maxLength - $printedLength ) );
			$printedLength = $maxLength;
			break;
		}

		print ( $str );
		$printedLength += strlen( $str );
		if ( $printedLength >= $maxLength )
			break;

		if ( $tag[0] == '&' || ord( $tag ) >= 0x80 ) {
			// Pass the entity or UTF-8 multibyte sequence through unchanged.
			print ( $tag );
			$printedLength++;
		} else {
			// Handle the tag.
			$tagName = $match[1][0];
			if ( $tag[1] == '/' ) {
				// This is a closing tag.

				$openingTag = array_pop( $tags );
				assert( $openingTag == $tagName ); // check that tags are properly nested.

				print ( $tag );
			} else if ( $tag[ strlen( $tag ) - 2 ] == '/' ) {
				// Self-closing tag.
				print ( $tag );
			} else {
				// Opening tag.
				print ( $tag );
				$tags[] = $tagName;
			}
		}

		// Continue after the tag.
		$position = $tagPosition + strlen( $tag );
	}

	// Print any remaining text.
	if ( $printedLength < $maxLength && $position < strlen( $html ) )
		print ( substr( $html, $position, $maxLength - $printedLength ) );

	// Close any open tags.
	while ( ! empty( $tags ) )
		printf( '</%s>', array_pop( $tags ) );
}

function linkify( $text, $blank = false ) {

	if ( $blank ) {
		$text = preg_replace(
			'/(?<!=\")(?<!\")(?<!\">)(?<!\dw,\s)((https?|ftp)+(s)?:\/\/[^<>\s]+)/i',
			'<a target="_blank" href="$1">$1</a>',
			$text
		);
	} else {
		$text = preg_replace(
			'/(?<!=\")(?<!\")(?<!\">)(?<!\dw,\s)((https?|ftp)+(s)?:\/\/[^<>\s]+)/i',
			'<a href="$1">$1</a>',
			$text
		);
	}

	return $text;
}

/**
 * User functions
 */
// Check if current user is logged in
function logged_in() {
	// debug( current_user() );
	return current_user()->logged_in();
}

function get_user( $id = 0 ) {
	// current user
	if ( $id == 0 ) {
		return current_user();
	}
	$current = current_user();
	if ( $id == $current->id ) {
		return $current;
	}

	$user = new User();
	$user->get_user( $id );

	return $user;
}

function current_user() {
	if ( session()->get( "user_mimic" ) ) {
		if ( ! Registry::exists( "mimic_user" ) ) {
			$user = new User();
			$user->get_user( session()->get( "user_mimic" ) );
			$user->mimic();
			Registry::set( "mimic_user", $user );
		}
		return Registry::get( "mimic_user" );
	}
	if ( ! Registry::exists( "current_user" ) ) {
		$user = new User();
		$user->get_user();
		Registry::set( "current_user", $user );
	}

	return Registry::get( "current_user" );
}

/**
 * Check if string is a serialized array
 */
function is_serial( $string ) {
	return ( @unserialize( $string ) !== false );
}

/**
 * Curl remote request
 */
function get_data( $url ) {
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt( $curl, CURLOPT_REFERER, $url );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	$str = curl_exec( $curl );
	curl_close( $curl );

	return $str;
}

/**
 * Pages add
 */
function add_page( $info ) {
	global $rf_pages;

	$rf_pages[] = $info;
}
function get_pages() {
	global $rf_pages;

	$posts = new Post();
	$posttypes = new PostType();
	$rf_pages = array_merge( $rf_pages, $posts->query( "SELECT posts.* FROM {$posts->table} AS posts 
		LEFT JOIN {$posttypes->table} AS post_types ON posts.post_type = post_types.slug
		WHERE post_types.public = 1 
	" ) );

	$rf_pages = apply_filters( "pages", $rf_pages );
	return $rf_pages;
}

/**
 * Permission add
 */
$rf_permissions[] = array(
	"slug" => "administrator",
	"label" => "Administrator",
	"description" => "Roles with this permissions are granted all permissions, and supercede any other permission rules.",
);
$rf_permissions[] = array(
	"slug" => "manage_settings",
	"label" => "Manage Settings",
	"description" => "Users can edit website settings in the admin area.",
);
$rf_permissions[] = array(
	"slug" => "manage_users",
	"label" => "Manage Users",
	"description" => "Role can promote or demote users to ranks under their own, as well as create or delete users.",
);
$rf_permissions[] = array(
	"slug" => "manage_roles",
	"label" => "Manage Roles",
	"description" => "Role can create addition roles beneath their own and add or remove permissions.",
);
$rf_permissions[] = array(
	"slug" => "manage_post_types",
	"label" => "Manage Post Types",
	"description" => "Role can add, delete, or update Custom Post Types. Including their defaults or statuses.",
);
$rf_permissions[] = array(
	"slug" => "manage_custom_fields",
	"label" => "Manage Custom Fields",
	"description" => "Role can create, delete, or update custom field layouts.",
);
$rf_permissions[] = array(
	"slug" => "manage_forms",
	"label" => "Manage Forms",
	"description" => "Role can create, delete, or update forms. They can also view and manage form entries.",
);
$rf_permissions[] = array(
	"slug" => "manage_menus",
	"label" => "Manage Menus",
	"description" => "Role can create, delete, or update menus.",
);
$rf_permissions[] = array(
	"slug" => "manage_comments",
	"label" => "Manage Comments",
	"description" => "Role can create, delete, or update comments submitted by users with a lesser role.",
);
$rf_permissions[] = array(
	"slug" => "manage_partials",
	"label" => "Manage Partials",
	"description" => "Role can create, delete, or update partials and their logic and caching.",
);
$rf_permissions[] = array(
	"slug" => "upload_files",
	"label" => "Upload Files",
	"description" => "Role can upload files to the website, front end or backend.",
);
$rf_permissions[] = array(
	"slug" => "display_stream",
	"label" => "Display Stream",
	"description" => "Users with this role will have their stream shown on the site, when live.",
);
function add_permission( $info ) {
	global $rf_permissions;

	$rf_permissions[] = $info;
}
function get_permissions() {
	global $rf_permissions;
	$rf_permissions = apply_filters( "permissions", $rf_permissions );
	return $rf_permissions;
}


/**
 * ==============================================================
 * HTML Functions
 * ==============================================================
 */
function render_html_field( $field, $settings ) {
	// $field = (object) $field;
	// debug( $settings );

	// debug($field);
	$type = isset( $settings["type"] ) ? $settings["type"] : "";
	$name = isset( $settings["name"] ) ? $settings["name"] : "";
	$label = isset( $settings['label'] ) ? $settings['label'] : "";
	$choices = isset( $settings['choices'] ) ? $settings['choices'] : "";
	$instructions = isset( $settings['instructions'] ) ? $settings['instructions'] : "";
	$layout = isset( $settings["layout"] ) ? $settings["layout"] : "os";

	$value = is_string( $field ) ? $field : $field[ $settings["name"] ];

	$unset = array( "class", "default", "placeholder", "layout", "type", "choices", "instructions" );

	// set up defaults in the attrs array
	$attrs = array();
	$attrs['id'] = $settings['name'];
	$attrs['value'] = $value == "" && isset( $settings['default'] ) ? $settings['default'] : $value;
	$value = $attrs['value'];
	$attrs['class'] = isset( $settings['class'] ) ? $settings['class'] : "";
	$settings["label"] = isset( $settings['label'] ) ? $settings['label'] : "";
	$attrs['placeholder'] = isset( $settings["placeholder"] ) ? $settings["placeholder"] : $settings["label"];

	// unset things we don't want showing up on the input class
	foreach ( $unset as $k => $v ) {
		unset( $settings[ $v ] );
	}

	if ( $type == "wysiwyg" ) {
		$attrs['class'] .= " wysiwyg";
		unset( $attrs['value'] );
	} else if ( $type == "checkbox" ) {
		$attrs['checked'] = isset( $attrs['value'] ) && $attrs['value'] == 1 ? true : false;
		$attrs['value'] = "1";
	}

	// debug($attrs);
	// debug($settings);

	// now build input attribute string
	$attrs_array = array_merge( $attrs, $settings );
	$attrs = array_map( function ($key, $value) {
		if ( gettype( $value ) == "boolean" && $value === true ) {
			return $key;
		} else if ( gettype( $value ) == "string" ) {
			return $key . '="' . $value . '"';
		}
	}, array_keys( $attrs_array ), array_values( $attrs_array ) );

	$attrs = implode( ' ', $attrs );

	?>

	<div class="field <?= $layout; ?>">
		<div class="field_label">
			<? if ( $label ) { ?>
				<label for="<?= $name; ?>"><?= $label; ?></label>
			<? } ?>
			<? if ( $instructions ) { ?>
				<div class="field_instructions muted em"><?= $instructions; ?></div>
			<? } ?>
		</div>
		<div class="field_value">
			<? if ( $type == "select" ) {
				if ( gettype( $value ) != "array" ) {
					$value = array( $value );
				}

				?>
				<select <?= $attrs; ?>>
					<option value="" disabled selected>--Select</option>
					<?
					foreach ( $choices as $key => $option ) {
						if ( is_array( $option ) ) { ?>
							<optgroup label="<?= $key; ?>">
								<? foreach ( $option as $skey => $label ) { ?>
									<option value="<?= $skey; ?>" <? if ( in_array( $skey, $value ) == true ) {
										  echo "selected";
									  } ?>><?= $label; ?></option>
								<? } ?>
							</optgroup>
						<? } else { ?>
							<option value="<?= $key; ?>" <? if ( in_array( $key, $value ) == true ) {
								  echo "selected";
							  } ?>><?= $option; ?></option>
						<? } ?>
					<? } ?>
				</select>

			<? } else if ( $type == "textarea" ) { ?>
					<textarea type="text" rows="5" <?= $attrs; ?>><?= $attrs_array['value']; ?></textarea>
			<? } else if ( $type == "wysiwyg" ) {
				?>
						<textarea name="<?= $name; ?>" class="wysiwyg_input" style="display: none"><?= $value; ?></textarea>
						<div <?= $attrs; ?>><? if ( $value )
							  echo htmlspecialchars_decode( $value );
						  ; ?></div>
			<? } else { ?>
						<input type="<?= $type; ?>" <?= $attrs; ?>>
			<? } ?>
		</div>
	</div>


<?
}

/**
 * ==============================================================
 * Media Functions
 * ==============================================================
 */

function uploads_dir() {
	global $root;
	return $root . Media::instance()->path;
}

function uploads_url() {
	return Media::instance()->path;
}

function get_file_size( $id, $width = null, $height = null ) {
	$file = new File();
	debug( "id = $id" );
	$file->load( "*", array( "id = :id", ":id" => $id ) );

	return $file->get_size( $width, $height );
}

function get_file( $id ) {
	if ( ! $id ) {
		return false;
	}
	$arr = array();

	$file = new File();
	$file->load( "*", array( "id = :id", ":id" => $id ) );

	$arr['id'] = $file->id;
	$arr['name'] = $file->name;
	$arr['original'] = $file->original;
	$arr['sizes'] = unserialize( $file->sizes );

	return $arr;
}

/**
 * Clear global caches
 */
function resetCaches() {
	global $rf_caches;
	foreach ( $rf_caches as $c ) {
		$c->reset();
	}
}

/**
 * Load currently active plugins
 * @return null
 */
function load_plugins() {
	global $root;
	$plugins = get_option( 'active_plugins' );

	if ( ! $plugins ) {
		$plugins = array();
	}
	foreach ( $plugins as $key => $path ) {
		if ( file_exists( $root . $path ) ) {
			require $root . $path;
		}
	}
}

/**
 * Returns a list of all registered post types
 * @return array $cpts
 */
function get_post_types() {
	global $rf_custom_posts;

	$post = new PostType();
	$cpts = $post->get_admin_post_pages();

	$cpts = array_merge( $cpts, $rf_custom_posts );
	$cpts = apply_filters( "admin/post_types", $cpts );

	return $cpts;
}

function display_alerts( $level = "all" ) {
	\Alerts::instance()->display( $level );
}

function add_alert( $type, $message ) {
	\Alerts::instance()->$$type( $message );
}

function get_meta( $uid, $key = false ) {
	global $meta_cache;
	list( $type, $post_id ) = explode( "_", $uid );
	if ( $post_id === null ) {
		$post_id = $type;
		$type = null;
		$uid = "post_{$post_id}";
	}

	if ( ! isset( $meta_cache[ $uid ] ) || ( $key != false && ! isset( $meta_cache[ $uid ][ $key ] ) ) ) {
		$meta = new Meta();
		$extra = "";
		$vars = array(
			":pid" => $post_id,
		);
		if ( $type ) {
			$extra .= "AND meta_type = :type ";
			$vars[":type"] = $type;
		}
		if ( $key ) {
			$extra .= "AND meta_key = :key ";
			$vars[":key"] = $key;
		}

		$meta = $meta->query( "SELECT * FROM {$meta->table} WHERE meta_parent = :pid $extra", $vars );
		$meta = array_extract( $meta, "meta_key", "meta_value" );

		if ( $key !== false ) {
			$meta_cache[ $uid ][ $key ] = $meta[ $key ];
		} else {
			foreach ( $meta as $k => $v ) {
				$meta_cache[ $uid ][ $k ] = $v;
			}
		}
	}

	if ( $key ) {
		return $meta_cache[ $uid ][ $key ];
	} else {
		return $meta_cache[ $uid ];
	}

}

function set_meta( $uid, $key, $value ) {
	$meta = new Meta();
	list( $type, $post_id ) = explode( "_", $uid );
	if ( $post_id === null ) {
		$post_id = $type;
		$type = null;
		$uid = "post_{$post_id}";
	}

	$extra = "";
	$vars = array(
		":pid" => $post_id,
	);
	if ( $type ) {
		$extra .= "AND meta_type = :type ";
		$vars[":type"] = $type;
	}
	if ( $key ) {
		$extra .= "AND meta_key = :key ";
		$vars[":key"] = $key;
	}

	$query = array( "meta_parent = :pid $extra" );
	$query = array_merge( $query, $vars );
	$meta->load( "*", $query );

	$meta->meta_parent = $post_id;
	$meta->meta_key = $key;
	$meta->meta_type = $type;
	$meta->meta_value = $value;
	$meta->save();

	$meta_cache[ $uid ][ $key ] = $value;
}

function plugins_dir() {
	return "/content/plugins";
}
function theme_dir() {
	global $root;
	return $root . "/content/themes/" . get_option( "active_theme" );
}
function theme_url() {
	return "/content/themes/" . get_option( "active_theme" );
}

function theme_path() {
	global $root;
	return $root . "/content/themes/" . get_option( "active_theme" ) . "/";
}

function register_post_type( $options ) {
	global $rf_custom_posts;

	// debug($options);
	$slug = $options['slug'];

	$info = array(
		"order" => 5 + $options["order"],
		"statuses" => serialize( $options["statuses"] ),
		"icon" => $options["icon"],
		"slug" => $options["slug"],
		"label" => $options["label"],
		"label_plural" => $options["label_plural"],
		"route" => "/admin/posts/@slug",
		"link" => "/admin/posts/{$slug}",
	);

	$rf_custom_posts[ $slug ] = $info;
}

$shortcodes = array();
function add_shortcode( $tag, $callback ) {
	global $shortcodes;
	if ( is_callable( $callback ) ) {
		$shortcodes[ $tag ] = $callback;
	}
}

function remove_shortcode( $tag ) {
	global $shortcodes;
	unset( $shortcodes[ $tag ] );
}

function parse_shortcodes( $fullcontent ) {
	global $shortcodes;

	if ( gettype( $fullcontent ) !== "string" ) {
		return $fullcontent;
	}

	$fullcontent = stripslashes( $fullcontent );

	// if we don't have shortcodes or can't even find a bracket then we know there are none
	if ( strpos( $fullcontent, '[' ) === false || empty( $shortcodes ) ) {
		return $fullcontent;
	}

	$tagnames = array_keys( $shortcodes );
	$tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );

	foreach ( $shortcodes as $tag => $callback ) {
		$regex = "/\[$tag(.*?)\]/";

		$fullcontent = preg_replace_callback( $regex, function ($matches) {
			global $shortcodes;

			// thank you wordpress
			$atts = array();
			$full = $matches[0];

			$tag = explode( " ", $full );
			$tag = $tag[0];
			$tag = str_replace( "[", "", $tag );
			$tag = str_replace( "]", "", $tag );

			$callback = $shortcodes[ $tag ];

			if ( isset( $matches[1] ) ) {
				$attributes = trim( $matches[1] );
				$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
				$text = preg_replace( "/[\x{00a0}\x{200b}]+/u", " ", $attributes );

				if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
					foreach ( $match as $m ) {
						if ( ! empty( $m[1] ) )
							$atts[ strtolower( $m[1] ) ] = stripcslashes( $m[2] );
						elseif ( ! empty( $m[3] ) )
							$atts[ strtolower( $m[3] ) ] = stripcslashes( $m[4] );
						elseif ( ! empty( $m[5] ) )
							$atts[ strtolower( $m[5] ) ] = stripcslashes( $m[6] );
						elseif ( isset( $m[7] ) and strlen( $m[7] ) )
							$atts[] = stripcslashes( $m[7] );
						elseif ( isset( $m[8] ) )
							$atts[] = stripcslashes( $m[8] );
					}
				} else {
					$atts = ltrim( $text );
				}
			}

			if ( $atts == "" ) {
				$atts = [];
			}

			ob_start();
			call_user_func_array( $callback, array( $atts ) );
			$content = ob_get_contents();
			ob_get_clean();

			return $content;

		}, $fullcontent );
	}

	return $fullcontent;
}


function array_extract( $array, $key, $value ) {
	$new = array();
	foreach ( $array as $v ) {
		$new[ $v[ $key ] ] = $v[ $value ];
	}

	return $new;
}
function rekey_array( $key, $array ) {
	$new = array();
	foreach ( $array as $v ) {
		$new[ $v[ $key ] ] = $v;
	}

	return $new;
}


function dequeue_style( $path ) {
	global $rf_styles;
	foreach ( $rf_styles as $priority => $styles ) {
		foreach ( $rf_styles[ $priority ] as $key => $style ) {
			if ( $style == $path ) {
				unset( $rf_styles[ $priority ][ $key ] );
			}
		}
	}
}
function dequeue_script( $path ) {
	global $rf_scripts;
	foreach ( $rf_scripts as $priority => $scripts ) {
		foreach ( $rf_scripts[ $priority ] as $key => $script ) {
			if ( $script == $path ) {
				unset( $rf_scripts[ $priority ][ $key ] );
			}
		}
	}
}

function queue_scss( $path, $priority = 10 ) {
	global $rf_scss;

	if ( ! isset( $rf_scss[ $priority ] ) ) {
		$rf_scss[ $priority ] = array();
	}

	$rf_scss[ $priority ][] = $path;
}
function queue_style( $path, $priority = 10 ) {
	global $rf_styles;

	if ( ! isset( $rf_styles[ $priority ] ) ) {
		$rf_styles[ $priority ] = array();
	}

	$rf_styles[ $priority ][] = $path;
}

function queue_script( $path, $priority = 10 ) {
	global $rf_scripts;

	// debug($path);

	if ( ! isset( $rf_scripts[ $priority ] ) ) {
		$rf_scripts[ $priority ] = array();
	}

	$rf_scripts[ $priority ][] = $path;
}

function rf_styles() {
	global $rf_styles;
	ksort( $rf_styles );

	// print out queued styles
	if ( isset( $rf_styles ) ) {
		foreach ( $rf_styles as $priority => $styles ) {
			foreach ( $styles as $k => $path ) { ?>
				<link rel="stylesheet" href="<? echo $path; ?>">
			<? }
		}
	}
}

function rf_scripts() {
	global $rf_scripts;
	ksort( $rf_scripts );

	foreach ( $rf_scripts as $priority => $scripts ) {
		foreach ( $scripts as $k => $file ) {
			echo '<script src="' . $file . '"></script>';
		}
	}
}

// function get_field()

function deslugify_title( $input ) {
	return ucwords( str_replace( array( "-", "_" ), array( " ", " " ), $input ) );
}
function desanitize_title( $input ) {
	return ucwords( str_replace( array( "-", "_" ), array( " ", " " ), $input ) );
}
function slugify( $string ) {
	if ( $string == "" ) {
		return "";
	}
	return strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '_', $string ), '_' ) );
}

function repeater_existing( $key ) {
	return isset( $_POST[ $key ] ) ? $_POST[ $key ] : array();
}
function repeater_new( $key, ...$fields ) {
	$new_entries = array();
	if ( $_POST[ $key ] ) {
		foreach ( $_POST[ $key ] as $k => $v ) {
			$row = array();
			foreach ( $fields as $field ) {
				$row[ $field ] = isset( $_POST[ $field ][ $k ] ) ? ( $_POST[ $field ][ $k ] ? $_POST[ $field ][ $k ] : 1 ) : 0;
			}
			$new_entries[] = $row;
		}
	}

	return $new_entries;
}

function debug( ...$params ) {
	echo "<pre>";
	foreach ( $params as $p ) {
		print_r( $p );
		echo "\n";
	}
	echo "</pre>";
}

function redirect( $path = false, $hash = "" ) {
	if ( ! $path ) {
		$path = $_SERVER['HTTP_REFERER'];
	}
	header( "Location: " . $path . $hash );
	exit();
}

// svg includes
function get_file_contents_url( $url ) {
	global $root;
	if ( strpos( $url, $_SERVER['HTTP_HOST'] ) !== false ) {
		echo "strip";
		$url = explode( $_SERVER['HTTP_HOST'], $url );
		$url = $url[1];
		return file_get_contents( $root . $url );
	} else {
		return file_get_contents( $root . $url );
	}
}

function smart_date( $date ) {
	$time = strtotime( $date );
	$time = time() - $time; // to get the time since that moment
	$time = ( $time < 1 ) ? 1 : $time;
	$numberOfUnits = 0;
	$tokens = array(
		31536000 => 'year',
		2592000 => 'month',
		//1209600 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ( $tokens as $unit => $text ) {
		if ( $time < $unit )
			continue;
		$numberOfUnits = floor( $time / $unit );
		return $numberOfUnits . ' ' . $text . ( ( $numberOfUnits > 1 ) ? 's' : '' ) . ' ago';
	}
}


function add_recaptcha( $label = "Submit" ) {
	queue_script( 'https://www.google.com/recaptcha/api.js' );

	?>
	<script>
		function onSubmit(token) {
			document.getElementById("recaptcha-form").submit();
		}
	</script>
	<button class="form_submit btn-secondary g-recaptcha" data-sitekey="6LdLF_8UAAAAALGVy69oBTwtqNRhn3--dgon9_DT" data-theme="dark" data-callback='onSubmit'><?= $label; ?></button>
<?
}