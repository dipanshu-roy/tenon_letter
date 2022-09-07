<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {

    function getHomeData(){
        return "HomePage content";
    }

    function getAboutusData(){
        return "About us content";
    }

}