<?php

/*
 * Routes helper
 */
if (!function_exists('resource_actions')) {
    /**
     * Return the array of string resource action codes.
     * Action Codes:
     * i = index, c = create, e = edit, w = show
     * s = store, u = update, d = destroy'
     * Example: resource_actions('isudw');
     *
     * @param  string $actions
     * @return array
     */
    function resource_actions($actionCodes)
    {
        $actions = [];
        $actionCodes = str_split($actionCodes);

        $actionsMap = [
            'i' => 'index',
            'c' => 'create',
            'e' => 'edit',
            'w' => 'show',
            's' => 'store',
            'u' => 'update',
            'd' => 'destroy'
        ];

        foreach ($actionCodes as $code) {
            if (array_key_exists($code, $actionsMap)) {
                $actions[] = $actionsMap[$code];
            }
        }

        return $actions;
    }
}