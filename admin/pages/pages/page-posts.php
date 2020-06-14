<?

class admin_page_POSTS extends RF_Admin_Page {

	function __construct($info) {
		$slug = $info['name'];
		$this->name = $slug;
		$this->label = $info["label"];
		$this->label_plural = $info["label_plural"];
		$this->admin_menu = $info["admin_menu"];
		$this->icon = $info["icon"];
		$this->link = $info["link"];
		$this->permissions = array(
			"view" => array("create_".$slug, "update_any_".$slug, "update_own_".$slug),
			"create" => "create_".$slug,
			"update" => array("update_any_".$slug),
			"update_own" => array("update_any_".$slug, "update_own_".$slug),
			"delete" => "delete_any_".$slug,
			"delete_own" => array("delete_any_".$slug, "delete_own_".$slug),
		);

		parent::__construct();
	}

	function query_object($args) {
		if ($args['id'] > 0) {
			$post = new Post();
			$post->load("*", array("id = :id", ":id" => $args['id']));

			$this->object = $post;
		}
	}

	function index($args) {
		$this->post_type = $this->name;
		$posts = new Post();
		$posts = $posts->find("*", "post_type = '{$this->post_type}'", array(
			"order by" => "created DESC"
		));

		$this->render_title();

		$columns = array(
			'title' => array(
				"label" => "Title",
				"class" => "tablelabel",
				"html" => '<a href="'.$this->link.'/edit/%2$d">%1$s</a>',
			),
			'post_parent' => array(
				"label" => "Parent",
				"calculate" => function($value, $id) {
					if ($value == 0) {
						echo "-";
					} else {
						$parent = new Post();
						$parent->load("*", array("id = :id", ":id" => $value));
						echo $parent->title;
					}
				}
			),
			'author' => array(
				"label" => "Author",
				"calculate" => function($value, $id) {
					$user = get_user($value);
					return $user->username;
				}
			),
			'modified' => array(
				"label" => "Updated",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
			'created' => array(
				"label" => "Created",
				"calculate" => function($value, $id) {
					$year = Date("Y", strtotime($value));
					if ($year != Date("Y")) {
						return Date("M jS Y, g:ia", strtotime($value));
					} else {
						return Date("M jS, g:ia", strtotime($value));
					}
				}
			),
			'remove' => array (
				"label" => "Remove",
				"class" => "min",
				"calculate" => function($s, $r) {
					return "<a href='{$this->link}/delete/{$r['id']}' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this item?');\"><i>delete_forever</i></a>";
				}
			)
		);
		$columns = apply_filters("admin/posts/columns", $columns);

		// display table
		?>
		<div class="section">
			<? display_results_table($posts, $columns);	?>
		</div>
		<?
	}

	function edit($args) {
		$this->render_title();
		
		$post_type = $this->name;
		$id = $this->id;
		
		$post = new Post();
		if ($id > 0) {
			$post->load("*", "id = $id");
		}

		?>

		<div class="row g1">
			<div class="os">
				<div class="content">
					<div class="padb2 section">
						<?
						render_html_field($post, array(
							"type" => "text",
							"label" => "Title",
							"name" => "title",
							"class" => "post_title",
							"data-bind" => true,
							"required" => true,
						));
						render_html_field($post, array(
							"type" => "text",
							"label" => "Sub Title",
							"name" => "subtitle",
							"class" => "sub_title padt1",
							"required" => false,
						));
						?>
					</div>
					<?
					// Custom Fields
					do_action("admin/custom_fields", "post");

					render_html_field($post, array(
						"type" => "wysiwyg",
						"label" => "Content",
						"name" => "content",
						"class" => "content",
						"style" => "height: 400px",
					));
					?>
					<div class="accordion_handle pad1 padx2 seo_settings toggled" data-accordion=".seo">
						<div class="row content-middle">
							<div class="os-min padr4">
								<h4 class="marg0">SEO Settings</h4>
							</div>
							<div class="os-min padr1">
								Enable SEO
							</div>
							<div class="os-min">
								<?
								render_html_field($post, array(
									"type" => "checkbox",
									"name" => "seo_enable",
									"class" => "seo_enable",
									"layout" => "os-min text-center marg0",
								));
								?>
							</div>
						</div>
					</div>
					<div class="accordion seo pad2">
						
						<div class="row g1">
							<?
							
							render_html_field($post, array(
								"type" => "text",
								"label" => "Main Title",
								"instructions" => "Less that 70 characters between these two fields, used to quickly describe the page with a keyword-centric approach.",
								"name" => "seo_title",
								"class" => "seo_title",
								"data-bind" => true,
								"maxlength" => "70",
								// "required" => true,
							));
							render_html_field($post, array(
								"type" => "text",
								"label" => "Secondary Title / Category",
								"instructions" => "Less that 70 characters between these two fields, used to quickly describe the page with a keyword-centric approach.",
								"name" => "seo_category",
								"class" => "seo_category",
								"data-bind" => true,
								"maxlength" => "70",
							));
							
							echo '<div class="clear"></div>';
							render_html_field($post, array(
								"type" => "textarea",
								"label" => "SEO Description",
								"instructions" => "Something descriptive about the pages content from a user's perspective. Something click-worthy, not click-bait.",
								"name" => "seo_description",
								"class" => "seo_description",
								"data-bind" => true,
								"required" => false,
							));
							?>
							<div class="os">
								<h3>SEO Preview</h3>
								<div class="seo_preview pad1 border">
									<? global $options; ?>
									<div class="sitetitle"><?= $_SERVER['SERVER_NAME']; ?> > <span data-value="title"></span></div>
									<div class="link">
										<span data-value="seo_title"></span>
										<span data-hide-on-empty="seo_category" class="sep"><?= $options['seo_seperator']; ?></span>
										<span data-value="seo_category"></span>
										<span class="sep"><?= $options['seo_seperator']; ?></span>
										<span data-value="seo_brand"><?= $options['sitename']; ?></span>
										<span class="sep"><?= $options['seo_seperator']; ?></span>
										<span data-value="seo_keywords"><?= $options['seo_keywords']; ?></span>
									</div>
									<div id="default_seo" class="description" data-value="seo_description"></div>
									<input type="hidden" id="default_seo_description" name="default_seo_description">
								</div>
							</div>
						</div>
						

					</div>
				</div>
			</div>

			<div class="os-2">
				<div class="sidebar autosticky">
					<div class="section">
						<input type="submit" class="marg0" value="Save">
						<? if ($post->id > 0) { ?>
							<a href="<?= $post->get_permalink(); ?>" target="_blank" class="btn display-block margt1 w100">View Page</a>
						<? } ?>
						<hr>
						<?
						render_html_field($post, array(
							"type" => "text",
							"name" => "permalink",
							"label" => "Permalink",
							"class" => "post_permalink",
							"required" => true,
						));

						$created = Date("Y-m-d");
						if (isset($post['created'])) {
							$created = Date("Y-m-d", strtotime($post['created']));
						}

						render_html_field($created, array(
							"type" => "date",
							"name" => "created",
							"label" => "Post Date",
							"class" => "created",
							"required" => false,
						));

						$pages = new Post();
						$pages = $pages->find("*", "post_type = 'pages' ");
						$pages = array_extract($pages, "id", "title");

						render_html_field($post, array(
							"type" => "select",
							"name" => "post_parent",
							"label" => "Post Parent",
							"choices" => $pages,
						));
						?>
						<?
						// $default = "";
						// $statuses = array();
						// // debug($pt['statuses']);
						// foreach (unserialize($pt['statuses']) as $s) {
						// 	if ($s['default_status'] == 1) {
						// 		$default = $s['name'];
						// 	}
						// 	$statuses[$s['name']] = $s['name'];
						// }
						// render_html_field($post, array(
						// 	"choices" => $statuses,
						// 	"type" => "select",
						// 	"name" => "post_status",
						// 	"label" => "Post Status",
						// 	"class" => "post_status",
						// 	"default" => $default,
						// 	"required" => true,
						// ));
						?>
						<!-- <input type="hidden" name="post_type_statuses" value=""> -->
					</div>
					<div class="section">
						<strong>Permissions</strong>
						<p>Page is visible to users who:</p>

						<? 
						$defaults = get_permissions();
						$defaults = array_extract($defaults, "slug", 'label');
						$defaults = array("" => "Disabled") + $defaults;

						$exp = array(
							"" => "Disabled",
							"==" => "Can",
							"!=" => "Cannot",
							"logged_in" => "Logged In",
							"logged_out" => "Logged Out",
						);
						?>

						<div class="row g1">
							<div class="os">
								<?
								render_html_field($post, array(
									"type" => "select",
									"name" => "permission_exp",
									"choices" => $exp,
								));
								?>
							</div>
							<div class="os">
								<? 
								render_html_field($post, array(
									"type" => "select",
									"name" => "permission",
									"choices" => $defaults,
								));
								?>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<?
	}

	function hierchal_permalink($parent_id) {
		$post = new Post();
		$post->load("*", array("id = :id", ":id" => $parent_id));
	}

	function save($args) {
		$id = $this->id;
		$user = current_user();

		$post = new Post();
		$changed = "created";
		if ($id > 0) {
			$changed = "updated";
			$post->load("*", "id = $id");
		}

		// calculate permalink
		$permalink = array();
		if ($_POST["post_parent"] > 0) {

		}
		$permalink[] = $_POST['permalink'];

		// update normal fields
		$post->title = $_POST["title"];
		$post->subtitle = $_POST["subtitle"];
		$post->slug = $_POST["permalink"];
		$post->created = $_POST["created"];
		if (isset($_POST['content'])) {
			$post->content = $_POST["content"];
		}
		$post->post_parent = $_POST["post_parent"];
		$post->post_type = $this->name;
		$post->author = $user->id;
		$post->permission = $_POST['permission'];
		$post->permission_exp = $_POST['permission_exp'];
		$post->seo_enable = $_POST['seo_enable'];
		$post->seo_title = $_POST['seo_title'];
		$post->seo_category = $_POST['seo_category'];
		if ($_POST['seo_description'] == "") {
			$post->seo_description = $_POST['default_seo_description'];
		} else {
			$post->seo_description = $_POST['seo_description'];
		}

		// echo "title";
		// debug($post->seo_title);
		// echo "category";
		// debug($post->seo_category);
		// echo "description";
		// debug($post->seo_description);
		// exit();
		$post->save();

		// exit();

		// debug($post);

		RCF()->save_fields("post", $post->id);
		// exit();

		$this->save_success($post->title, $changed, $post->id);
	}

	function delete($args) {
		if ($this->can_delete()) {
			$post = new Post();
			$post->load("*", array("id = :id", ":id" => $args['id']));
			$post->erase();

			\Alerts::instance()->success("Deleted post");
			redirect($this->link);
		}
	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	function can_view($args) {

	}

	function can_edit($args) {

	}

	function can_save($args) {

	}

	function can_delete($args) {

	}
	
	*/
}

function build_post_pages() {
	$cpts = get_post_types();

	// debug($cpts);

	foreach ($cpts as $post) {
		$admin_menu = false;
		if (isset($post['order']) && $post['order'] !== false) {
			$admin_menu = 5 + $post['order'];
		}
		$info = array(
			"admin_menu" => 5 + $admin_menu,
			"icon" => $post["icon"],
			"name" => $post["slug"],
			"label" => $post["label"],
			"label_plural" => $post["label_plural"],
			"route" => "/admin/posts/@slug",
			"link" => "/admin/posts/{$post['slug']}",
		);

		$class = "admin_page_POSTS";
		if ($post['class']) {
			$class = $post['class'];
		}

		new admin_page_POSTS($info);
	}
}

build_post_pages();