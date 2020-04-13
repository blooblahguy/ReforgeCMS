<?php

// Allows for cron job style tasking

class Jobs extends Prefab {
	protected $jobs = [];
	protected $cache = [];

	function __construct() {

	}

	function add($job, array $config) {
		if (empty($config['schedule'])) {
            throw new Exception("'schedule' is required for '$job' job");
        }

        if (! isset($config['command'])) {
            throw new Exception("'command' is required for '$job' job");
		}
		
		$this->jobs[] = [$job, $config];
	}

	function run() {
		// loop through jobs
		foreach ($this->jobs as $jobConfig) {
			list($job, $config) = $jobConfig;

			// check schedule
			if ($this->isDue($job, $config['schedule'])) {
				// run the job
				call_user_func($config['command']);
			}
		}
	}

	/**
	 * Compares given values similar to an apache cron, then returns the current running time
	 */
	function timeCompare($entry, $time) {
		if ($entry == "*") {
			return $time;
		}

		$entries = explode(",", $entry);
		foreach ($entries as $e) {
			if ((int) $e == (int) $time) {
				return $time;
			}
		}

		return false;
	}

	/**
	 * Parse out the given schedule and see if the job is due
	 */
	function isDue($job, $schedule) {
		list($minute, $hour, $day, $month, $weekday) = explode(" ", $schedule);
		

		// Check each entry individually
		$minute = $this->timeCompare($minute, Date("i"));
		if ($minute === false) { return false; }

		$hour = $this->timeCompare($hour, Date("G"));
		if ($hour === false) { return false; }

		$day = $this->timeCompare($day, Date("j"));
		if ($day === false) { return false; }

		$month = $this->timeCompare($month, Date("n"));
		if ($month === false) { return false; }

		$weekday = $this->timeCompare($weekday, Date("w"));
		if ($weekday === false) { return false; }

		// job is "due" but now check the last time we ran this job
		$lastrun = $this->cache->get($job . "_last");
		$current = [$minute, $hour, $day, $month, $weekday];
		$current = implode(" ", $current);

		// if we've run this in the same cycle, then kill it
		if ($lastrun == $current) {
			return false;
		}
		$this->cache->set($job . "_last", $current);

		return true;
	}
}

function Jobs() {
	return Jobs::instance();
}