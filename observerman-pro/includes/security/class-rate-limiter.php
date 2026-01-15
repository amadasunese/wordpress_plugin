<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_Rate_Limiter {

    private $limit  = 10;
    private $window = 300; // 5 minutes (seconds)

    public function is_allowed($key) {

        $transient_key = 'observerman_rate_' . md5($key);
        $data = get_transient($transient_key);

        if (!$data) {
            set_transient($transient_key, [
                'count' => 1,
                'start' => time(),
            ], $this->window);

            return true;
        }

        if ($data['count'] >= $this->limit) {
            return false;
        }

        $data['count']++;
        set_transient($transient_key, $data, $this->window);

        return true;
    }

    public function retry_after($key) {

        $transient_key = 'observerman_rate_' . md5($key);
        $data = get_transient($transient_key);

        if (!$data) {
            return 0;
        }

        $elapsed = time() - $data['start'];
        return max(0, $this->window - $elapsed);
    }
}
