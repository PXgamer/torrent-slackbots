<?php

namespace pxgamer\TorrentSlackBots;

use pxgamer\TorrentParser\ExtraTorrent as ET;
use pxgamer\TorrentSlackBots\Functions\Quick as Q;

class BotSender
{
	
	public static $user;
	
	private static $torrents;
	private static $db;
	private static $endpoint;
	
	public function setDb($host, $username, $password, $db)
	{
		self::$db = new \MysqliDb($host, $username, $password, $db);
	}
	
	public function setEndpoint($endpoint)
	{
		if (!is_string($endpoint)) return false;
		self::$endpoint = new \Maknz\Slack\Client($endpoint);
	}
	
	public function setUser($username)
	{
		self::$user = $username;
		self::$torrents = ET::user($username);
	}
	
	public function loop($limit = null)
	{
		$i = 1;
		foreach (self::$torrents as $result) {
			self::$db->where(
				"hash",
				$result['info_hash']
			);
			$is_found = self::$db->getOne(self::$user);
			
			if (!$is_found) {				
				$send = $this->send($result);
				
				if ($send) {
					self::$db->insert(self::$user, ['hash' => $result['info_hash']]);
				}
			}
			if (isset($limit) && $i == $limit) break;
			$i++;
		}
	}
	
	private function send($result = [])
	{
		if (empty($result)) return false;
		
		$u = Q::parseTurl($result['link']);
		
		self::$endpoint->attach(
			[
				'fallback' => 'Additional Data',
				'text' => 'Additional Data',
				'color' => 'warning',
				'fields' => [
					[
						'title' => 'Released',
						'value' => date('jS M Y', strtotime($result['pubDate'])),
						'short' => true
					],
					[
						'title' => 'Size',
						'value' => Q::mksize($result['size']),
						'short' => true
					]
				]
			]
		)->send(
			"_[".
			$u->id.
			"]_ *".
			$result['title'].
			"*\n".
			$u->url.
			"\n*Torrent Hash:* ".
			$result['info_hash'].
			(!empty($result['category']) ? "\n*Category:* ".
			$result['category'] : '')
		);
		
		return true;
	}
	
}