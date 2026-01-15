<?php
if (!defined('ABSPATH')) exit;

class ObserverMan_Pro_Loader {

    protected $actions = [];

    public function add_action($hook, $component, $callback) {
        $this->actions[] = compact('hook', 'component', 'callback');
    }

    public function run() {
        foreach ($this->actions as $action) {
            add_action(
                $action['hook'],
                [$action['component'], $action['callback']]
            );
        }
    }
}
