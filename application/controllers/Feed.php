<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends CI_Controller {

    private function htmlToText($s) {
        return preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($s))) );
    }

    public function index()
    {
      //  @system('wget -qO- http://magemore.com/cron/ &> /dev/null &');

        header('Content-type: application/atom+xml');
        $this->load->database();
        $a = $this->db->order_by('id', 'DESC')->limit(30)->get('entry')->result_array();
        foreach ($a as $i => $d) {
            $d['date'] = (new DateTime($d['created_at']))->format('Y-m-d\TH:i:sP');
            $d['summary'] = $this->htmlToText($d['html']);
            $a[$i] = $d;
        }
        $this->load->view('feed',['a' => $a]);
    }
}
