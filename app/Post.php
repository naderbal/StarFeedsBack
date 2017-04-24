<?php
namespace App;
use App\Http\Controllers\apiController;
use DateTime;

	class Post{
		public $platform;
		public $feedType;
		public $id;
		public $celebName;
		public $celebId;
		public $text;
		public $imageUrl;
		public $imageProfile;
		public $link;
		public $date;
		public $timestamp;


		public function __construct($feed, $platform, $celeb)
		{
			$dateFormat = 'd/M/Y H:i:s';

			$this->celebName = $celeb->name;
			$this->celebId = $celeb->id;
			$this->platform = $platform;
			$this->id = $feed->feed_id;
			$this->feedType = $feed->feed_type;
			$this->imageProfile = $celeb->fb_profile_url;
			$this->text = $feed->text;
			$this->link = $feed->link;
			$this->imageUrl = $feed->image_url;
			if($platform == 'Instagram'){
				$timestamp = $feed->created_time;
				$dateAfterTimeStampConversion = date($dateFormat, $timestamp);
				$this->date = $dateAfterTimeStampConversion;
				$this->timestamp = $timestamp;
				$this->link = $feed->video_url;
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