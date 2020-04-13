<?
	
	class Post extends \RF\Mapper {
		function __construct() {
			$schema = array(
				"post_type" => array(
					"type" => "VARCHAR(100)"
				),
				"post_parent" => array(
					"type" => "INT(7)",
				),
				"title" => array(
					"type" => "VARCHAR(280)",
				),
				"subtitle" => array(
					"type" => "VARCHAR(512)",
				),
				"content" => array(
					"type" => "LONGTEXT"
				),
				"seo_title" => array(
					"type" => "VARCHAR(70)"
				),
				"seo_category" => array(
					"type" => "VARCHAR(70)"
				),
				"seo_description" => array(
					"type" => "VARCHAR(256)"
				),
				"slug" => array(
					"type" => "VARCHAR(190)",
				),
				"author" => array(
					"type" => "INT(7)",
				),
				"post_parent" => array(
					"type" => "INT(7)",
					"attrs" => "NOT NULL DEFAULT 0"
				),
				"post_status" => array(
					"type" => "VARCHAR(250)",
				),
				"permissions" => array(
					"type" => "LONGTEXT",
				),
			);

			parent::__construct("rf_posts", $schema);
		}

		function hierchal_permalink($parent_id) {
			$permalinks = array();
			$post = new Post();
			$post->load(array("id = :id", ":id" => $parent_id));
			if ($post->post_parent) {
				$permalinks = array_merge($permalinks, $this->hierchal_permalink($post->post_parent));
			}
			$permalinks[] = $post->slug;

			return $permalinks;
		}

		function get_permalink($id = 0) {
			if ($this->permalink) {
				return $this->permalink;
			} 
			if ($id == 0 && ! $this->id) { 
				return false;
			}

			$permalinks = array();

			// load a post if ID is > 0
			if ($id > 0) {
				$this->load(array("id = :id", ":id" => $id));
			}

			$home = get_option("site_homepage");
			if ($this->id == $home) {
				return "/";
			}

			// get post type, and check for url prefix
			$pt = new PostType();
			$pt->load(array("slug = :post_type", ":post_type" => $this->post_type));
			if ($pt->allow_parents == 0 && $pt->url_prefix != "") {
				$permalinks[] = $pt->url_prefix;
			}

			// if we don't have a post parent, then just return our slug link
			if (! $this->post_parent) {
				$permalinks[] = $this->slug;
				return "/".implode("/", $permalinks);
			}

			$permalinks = array_merge($permalinks, $this->hierchal_permalink($this->post_parent));
			$permalinks[] = $this->slug;

			return "/".implode("/", $permalinks); 
		}
	}
