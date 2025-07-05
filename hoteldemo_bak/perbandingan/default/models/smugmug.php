<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<feed xmlns="http://purl.org/atom/ns#" version="0.3" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:exif="http://www.exif.org/specifications.html">
  <title>focusinonme's 'sports' tagged photos</title>
  <link rel="alternate" type="text/html" href="http://www.focusinon.me/keyword/sports/"/>
  <link rel="icon" type="image/gif" href="http://www.smugmug.com/img/smuggy64.gif"/>
  <link rel="self" type="application/atom+xml" href="<?php echo $this->baseUrl; ?>/rss/latestsportgalleries<?php if($this->start > 0) { ?>/start/<?php echo $this->start; ?><?php } ?>"/>
  <link rel="next" type="application/atom+xml" href="<?php echo $this->baseUrl; ?>/rss/latestsportgalleries/start/<?php echo ($this->start+$this->limit); ?>"/>
  <info type="text/html" mode="escaped"></info>
  <tagline></tagline>
  <id><?php echo $this->baseUrl; ?>/rss/latestsportgalleries<?php if($this->start > 0) { ?>/start/<?php echo $this->start; ?><?php } ?></id>
  <copyright>Copyright <?php echo date("Y"); ?>, the copyright holder of each photograph.  Some portions copyright SmugMug.  All rights reserved.</copyright>
  <generator url="<?php echo $this->baseUrl; ?>/"><?php echo $this->siteName; ?></generator>
  <?php if(is_array($this->galleries)) foreach ($this->galleries as $gallery) { ?>
  <entry>
    <title><?php if(!empty($gallery['caption'])) { ?><?php echo htmlentities($gallery['caption']); ?> - <?php } ?><?php echo htmlentities($gallery['content_gallery']); ?></title>
    <link rel="alternate" type="text/html" href="<?php echo $this->smugmugURL; ?>/gallery/<?php echo $gallery['gallery_smugmug_id']; ?>_<?php echo $gallery['gallery_smugmug_key']; ?>#<?php echo $gallery['smugmug_id']; ?>_<?php echo $gallery['smugmug_key']; ?>"/>
    <link rel="image.medium" type="image/jpeg" href="<?php echo $this->smugmugURL; ?>/photos/i-<?php echo $gallery['smugmug_key']; ?>/0/M/i-<?php echo $gallery['smugmug_key']; ?>-M.jpg"/>
    <content type="text/html" mode="escaped">&lt;p&gt;&lt;a href="<?php echo $this->smugmugURL; ?>"&gt;focusinonme&lt;/a&gt;&lt;br /&gt;<?php if(!empty($gallery['caption'])) { ?><?php echo htmlentities($gallery['caption']); ?> - <?php } ?><?php echo htmlentities($gallery['content_gallery']); ?>&lt;/p&gt;&lt;p&gt;&lt;a href="<?php echo $this->smugmugURL; ?>/gallery/<?php echo $gallery['gallery_smugmug_id']; ?>_<?php echo $gallery['gallery_smugmug_key']; ?>#<?php echo $gallery['smugmug_id']; ?>_<?php echo $gallery['smugmug_key']; ?>" title="<?php echo htmlentities($gallery['content_gallery'], ENT_QUOTES); ?>"&gt;&lt;img src="<?php echo $this->smugmugURL; ?>/photos/i-<?php echo $gallery['smugmug_key']; ?>/0/Th/i-<?php echo $gallery['smugmug_key']; ?>-Th.jpg" width="150" height="150" alt="<?php echo htmlentities($gallery['caption'], ENT_QUOTES); ?>" title="<?php echo htmlentities($gallery['caption'], ENT_QUOTES); ?>" style="border: 1px solid #000000;" /&gt;&lt;/a&gt;&lt;/p&gt;</content>
    <issued><?php 
    	$date = strtotime($gallery['create_date_time']);
    	echo date("Y-m-d", $date)."T".date("H:i:sP", $date);
    ?></issued>
    <created><?php 
    	$date = strtotime($gallery['create_date_time']);
    	echo date("Y-m-d", $date)."T".date("H:i:sP", $date);
    ?></created>
    <modified><?php 
    	$date = strtotime($gallery['modify_date_time']);
    	echo date("Y-m-d", $date)."T".date("H:i:sP", $date);
    ?></modified>
    <author>
      <name>focusinonme</name>
      <url><?php echo $this->smugmugURL; ?></url>
    </author>
    <id><?php echo $this->smugmugURL; ?>/photos/i-<?php echo $gallery['smugmug_key']; ?>/0/Th/i-<?php echo $gallery['smugmug_key']; ?>-Th.jpg</id>    
  </entry>
  <?php } ?>
</feed>