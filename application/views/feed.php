<?php echo '<?xml version = "1.0" encoding = "UTF-8"?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>http://magemore.com/feed/</id>
    <title>atom</title>
    <updated><?=$a[0]['date']?></updated>
    <link rel="self" href="http://magemore.com/atom.php" hreflang="en-us"/>
    <?php foreach ($a as $d): ?>
        <entry>
            <id><?=$d['id']?></id>
            <title type="html"><?=$d['title']?></title>
            <updated><?=$d['date']?></updated>
            <link href="<?=$d['link']?>"/>
            <summary type="html"><![CDATA[<?=$d['summary']?>]]></summary>
            <content type="html"><![CDATA[<?=$d['html']?>]]></content>
        </entry>
    <?php endforeach; ?>
</feed>
