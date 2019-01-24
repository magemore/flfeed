var page = require('webpage').create();
var fs = require('fs');
var url = 'https://www.freelancer.com/jobs/woocommerce/';

page.open(url, function(status) {
    if (status !== 'success') {
        console.log('Unable to access network');
    } else {
        if (page.injectJs('jquery.min.js')) {
            var a = page.evaluate(function () {
                var r = new Array();
                $('#project-list .JobSearchCard-item').each(function(index) {
                    var a = $( this ).find('.JobSearchCard-primary-heading-link')[0];
                    var link = a.href;
                    var title = a.textContent.replace(new RegExp("\n", 'g'), '').trim();
                    r.push({'link': link, 'title': title, 'html': $(this).html() });
                });
                return r;
            });
            var r = JSON.stringify(a);
            fs.write('/tmp/freelancer_tag_woocommerce.json', r, 'w');
            phantom.exit();
        }
    }
});
