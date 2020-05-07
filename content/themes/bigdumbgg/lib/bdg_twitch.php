<?php
	namespace BDG;
	class Twitch {
		private $client_id;

		function __construct($client_id) {
			$this->client_id = $client_id;
		}

		function getUsers($ids) {
			$ids = explode(",",$ids);
			$query = array();
			foreach ($ids as $id) {
				if (is_numeric($id)) {
					$query[] = "id={$id}";
				} else {
					$query[] = "login={$id}";
				}
			}
			$query = implode("&", $query);
			$users = $this->request("users?{$query}");

			return $users;
		}
		function getGames($ids) {
			$ids = explode(",",$ids);

			$query = array();
			foreach ($ids as $id) {
				if (is_numeric($id)) {
					$query[] = "id={$id}";
				} else {
					$query[] = "name={$id}";
				}
			}
			$query = implode("&", $query);

			$result = $this->request("games?{$query}");

			return $result;
		}
		function getStreams($ids) {
			$ids = explode(",",$ids);

			$query = array();
			foreach ($ids as $id) {
				if (is_numeric($id)) {
					$query[] = "user_id={$id}";
				} else {
					$query[] = "user_login={$id}";
				}
			}
			$query = implode("&", $query);

			$streams = $this->request("streams?{$query}");

			return $streams;
		}

		function request( $url ) {
			$url = "https://api.twitch.tv/helix/{$url}";

			$headers = ["Client-ID:".$this->client_id];

			// debug($url);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_REFERER, $url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$response = curl_exec($ch);
			curl_close($ch);
			// var_dump($response);
			$response = json_decode($response, true);
			
			return $response;
		}
	}
?>