<?php

namespace RF {
	interface Plugin {
		function install();
		function activate();
		function deactivate();
		function uninstall();
	}
}
