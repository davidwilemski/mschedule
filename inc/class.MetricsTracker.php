<?php

class MetricsTracker {
    public $token;
    public $host = 'http://api.mixpanel.com/';
    public function __construct($token_string) {
        $this->token = $token_string;
    }
    function track($event, $properties=array()) {
        global $db;

        $browser = get_browser(null, true);
        if($browser['platform'] == "unknown"){
            return;
        }
        $properties['browser'] = $browser['browser']." ".$browser['version'];

        $properties['time'] = time();
        $properties['distinct_id'] = session_id();
        $properties['token'] = $this->token;
        $properties['ip'] = $_SERVER['REMOTE_ADDR'];

        $params = array(
            'event' => $event,
            'properties' => $properties
            );
            
        $url = $this->host . 'track/?data=' . base64_encode(json_encode($params));

        $start = microtime(true);
        file_get_contents($url);
    }
    
    function track_funnel($funnel, $step, $goal, $properties=array()) {
        $properties['funnel'] = $funnel;
        $properties['step'] = $step;
        $properties['goal'] = $goal;
        $this->track('mp_funnel', $properties);
    }
}
 
