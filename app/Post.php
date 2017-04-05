<?php
namespace App;
use App\Http\Controllers\apiController;
use DateTime;

	class Post{
		public $platform;
		public $feedType;
		public $id;
		public $celebName;
		public $text;
		public $imageUrl;
		public $link;
		public $date;
		public $timestamp;


		public function __construct($feed, $platform, $celebName)
		{
			$dateFormat = 'd M Y H:i:s';

			$this->celebName = $celebName;
			$this->platform = $platform;
			$this->id = $feed->feed_id;
			$this->feedType = $feed->feed_type;
			$this->text = $feed->text;
			$this->link = $feed->link;
			$this->imageUrl = $feed->image_url;
			if($platform == 'Instagram'){
				$timestamp = $feed->created_time;
				$dateAfterTimeStampConversion = date($dateFormat, $timestamp);
				$this->date = $dateAfterTimeStampConversion;
				$this->timestamp = $timestamp;
			}
			else {
				$this->date = $feed->created_time;
				$timestamp = strtotime($feed->created_time);
				$dateAfterTimeStampConversion = date($dateFormat, $timestamp);
				$this->date = $dateAfterTimeStampConversion;
				$this->timestamp = $timestamp;
			}
		}

	}

?>