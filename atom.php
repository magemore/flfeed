<?php
header('Content-type: application/atom+xml');
function getFeedData($name) {
    @unlink('/tmp/'.$name.'.json');
    @system('QT_QPA_PLATFORM=offscreen phantomjs '.$name.'.js');
    $ab =  json_decode(file_get_contents('/tmp/'.$name.'.json'), true);
    $i = 0;
    $a=[];
    foreach ($ab as $d) {
        $i++;
        if ($i>3) break;
        $a[]=$d;
    }
    return $a;
}
function validateItem($d) {
    $s = strtolower($d['title'].' '.$d['html']);
    if (strpos($s, 'designer') !== FALSE) return false;
    if (strpos($s, 'redesign') !== FALSE) return false;
    if (strpos($s, 'designing') !== FALSE) return false;
    if (strpos($s, 'angular') !== FALSE) return false;
    if (strpos($s, 'react') !== FALSE) return false;
    if (strpos($s, 'india') !== FALSE) return false;
    if (strpos($s, 'c#') !== FALSE) return false;
    return true;
}
function removeDuplicates($ab) {
    $a = [];
    foreach ($ab as $d) {
        if (!validateItem($d)) continue;
        $a[$d['link']] = $d;
    }
    return $a;
}
function htmlToText($s) {
    return preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($s))) );
}
$a = [];
foreach ([
        'freelancer_tag_yii','freelancer_search_yii',
        'freelancer_tag_opencart','freelancer_search_opencart',
             'freelancer_search_psd_html'] as $name) {
    $a = array_merge($a, getFeedData($name));
}
$a = removeDuplicates($a);
$date = (new DateTime())->format('Y-m-d\TH:i:sP');
?><?xml version = "1.0" encoding = "UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>http://magemore.com/atom.php</id>
    <title>atom</title>
    <updated><?=$date?></updated>
    <link rel="self" href="http://magemore.com/atom.php" hreflang="en-us"/>
    <?php foreach ($a as $d): ?>
    <entry>
        <id><?=$d['link']?></id>
        <title type="html"><?=$d['title']?></title>
        <updated><?=$date?></updated>
        <link href="<?=$d['link']?>"/>
        <summary type="html"><![CDATA[<?=htmlToText($d['html'])?>]]></summary>
        <content type="html"><![CDATA[<?=$d['html']?>]]></content>
    </entry>
    <?php endforeach; ?>
</feed>
