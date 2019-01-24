<?php
header('Content-type: application/atom+xml');
function getFeedData($name) {
    @unlink('/tmp/'.$name.'.json');
    @system('QT_QPA_PLATFORM=offscreen phantomjs '.$name.'.js');
    return json_decode(file_get_contents('/tmp/'.$name.'.json'), true);
}
$a = [];
foreach (['freelancer_tag_yii','freelancer_search_yii'] as $name) {
    $a = array_merge($a, getFeedData($name));
}
$date = (new DateTime())->format('Y-m-d\TH:i:sP');
?><?xml version = "1.0" encoding = "UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>http://magemore.com/rss.php</id>
    <title>rss</title>
    <updated><?=$date?></updated>
    <link rel="self" href="http://magemore.com/test.atom" hreflang="en-us"/>
    <?php foreach ($a as $d): ?>
    <entry>
        <id><?=$d['link']?></id>
        <title type="html"><?=$d['title']?></title>
        <updated><?=$date?></updated>
        <link rel="alternate" href="<?=$d['link']?>"/>
        <summary type="html"><![CDATA[<?=$d['html']?>]]></summary>
        <content type="html"><![CDATA[<?=$d['html']?>]]></content>
    </entry>
    <?php endforeach; ?>
</feed>
