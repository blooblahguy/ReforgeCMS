<?

class admin_page_DASHBOARD extends RF_Admin_Page {
	function __construct() {
		global $core; 

		$this->name = "dashboard";
		$this->label = "Dashboard";
		$this->label_plural = "Dashboard";
		$this->admin_menu = 0;
		$this->icon = "speed";
		$this->base_permission = "access_admin";
		$this->link = "/admin/dashboard";
		$this->disable_header = true;

		// CUSTOM Routes (index, edit, and save are automatically created)
		$core->route("GET /admin", "admin_page_DASHBOARD->goto_dashboard");

		// Be sure to set up the parent
		parent::__construct();
	}

	function goto_dashboard($core, $args) {
		$core->reroute("/admin/dashboard");
	}

	function render_index() {
		$user = current_user();

		?>
			<div class="row h100 g2">
				<div class="os">

					<div class="os widget activity section">
						<h2>Last Two Weeks</h2>
						<div class="row dashboard_calendar g1">
						<? // loop through last 2 weeks
						for ($i = -13; $i <= 0; $i++) {
							$time = strtotime("{$i} days");
							$weekday = strtoupper(Date("D", $time));
							$day = Date("j", $time);
							?>
							<div class="os-sv1 day pad1 margb2">
								<div class="date">
									<div class="weekday text-center h5 marg0 text-dark-grey">
										<?= $weekday; ?>
									</div>
									<div class="day text-center h2 marg0 text-dark-grey">
										<?= $day; ?>
									</div>
									<div class="events padt1">
										<div class="bg-info event">test</div>
									</div>
								</div>
							</div>
						<? } ?>
						</div>
					</div>
				</div>

				<div class="os-3 h100">
					<div class="section dashboard_profile">
						<div class="row content-middle">
							<div class="profile os">
								<h2>Profile</h2>
							</div>
							<div class="os-min">
								<a href="/admin/users/edit/<?= $user->id; ?>" class="pad1 border display-block"><i class="edit display-block">edit</i></a>
							</div>
						</div>
						<div class="pady2 text-center">
							<div class="pad2">
								<img src="<?= $user->avatar; ?>" class="avatar" alt="<?= $user->username; ?>">
							</div>
							<a href="" class="username strong">
								<?= $user->username; ?>
							</a>
							<div class="role"><?= $user->role; ?></div>
						</div>
					</div>
				</div>
			</div>
		<?
	}

	function render_edit() {
		echo "edit dashboard";
	}

	function save_page() {

	}

	function delete_page() {

	}

	/**
	 * Permission Overrides
	 * Uncomment and use these permissions functions to set exact permission behavior
	 */

	/*

	protected function can_view($args) {

	}

	protected function can_edit($args) {

	}

	protected function can_save($args) {

	}

	protected function can_delete($args) {

	}
	
	*/
}

new admin_page_DASHBOARD();

?>