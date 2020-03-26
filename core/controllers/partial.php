<?
	class Partial extends \RF\Mapper {
		function __construct() {
			parent::__construct("posts");
		}

		function html() {
			if (! $this->id) { return ''; }

			if (file_exists(theme_dir()."/partials/{$this->slug}.php")) {
				require theme_dir()."/partials/{$this->slug}.php";
			} else {
				$this->content;
			}
		}

		function widget_html() {
			if (! $this->id) { return ''; }
			?>
			<div class="widget widget_<?= $this->slug; ?>">
				<?= $this->html(); ?>
			</div>
			<?
		}
	}

	function render_widget($slug) {

	}
?>