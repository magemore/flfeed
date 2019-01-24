<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {


    private function validateItem($d) {
        $s = strtolower($d['title'].' '.$d['html']);
        if (strpos($s, 'designer') !== FALSE) return false;
        if (strpos($s, 'redesign') !== FALSE) return false;
        if (strpos($s, 'designing') !== FALSE) return false;
        if (strpos($s, 'angular') !== FALSE) return false;
        if (strpos($s, 'react') !== FALSE) return false;
        if (strpos($s, 'india') !== FALSE) return false;
        if (strpos($s, 'mumbai') !== FALSE) return false;
        if (strpos($s, 'c#') !== FALSE) return false;
        if (strpos($s, '>ended<') !== FALSE) return false;
        if (strpos($s, '>project in progress<') !== FALSE) return false;
        return true;
    }
    private function getFeedData($name, $filter) {
        @unlink('/tmp/'.$name.'.json');
        @system('QT_QPA_PLATFORM=offscreen phantomjs '.PHANTOMJSDIR.$name.'.js');
        $ab =  json_decode(file_get_contents('/tmp/'.$name.'.json'), true);
        $i = 0;
        $a=[];
        foreach ($ab as $d) {
            $add = true;
            if ($filter) {
                $add = strpos(strtolower($d['title'].' '.$d['html']),$filter)!==FALSE;
            }
            if (!$this->validateItem($d)) $add = false;
            if ($add) {
                $i++;
//                if ($i>3) break;
                $d['title'] = $name.': ' .$d['title'];
                $a[]=$d;
            }
        }
        return $a;
    }

    public function index()
    {
        $this->load->database();
        $a = [];
        foreach ([
                     'freelancer_tag_yii' => 'yii','freelancer_search_yii' => 'yii',
                     'freelancer_tag_opencart' => 'opencart','freelancer_search_opencart' => 'opencart',
                     'freelancer_tag_woocommerce' => 'woocommerce','freelancer_search_woocommerce' => 'woocommerce',
                     'freelancer_search_psd_html' => 'html'] as $name => $filter) {
            $a = array_merge($a, $this->getFeedData($name, $filter));
        }
        foreach ($a as $d) {
            $this->db->insert('entry',$d);
        }
    }
}
