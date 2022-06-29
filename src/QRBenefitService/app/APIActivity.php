<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APIActivity extends Model
{
    protected $table      = 'api_activity';
    protected $primaryKey = 'id';
    public    $timestamps = false;

    /**
     * Create a new API activity record in the database.
     *
     * @param array $params.
     */
    static public function create($params = [
        'route'       => null,
        'method'      => null,
        'user_id'     => 0,
        'payload'     => null,
        'status_code' => 0,
        'response'    => null,
    ])
    {
        // Validation
        $params = array_merge([
            'route'       => null,
            'method'      => null,
            'user_id'     => 0,
            'payload'     => null,
            'status_code' => 0,
            'response'    => null,
        ], $params);

        foreach ($params as $key => $value) {
            if (in_array($key, ['route', 'method']) && !$value) {
                return false;
            }
        }

        $params['payload']  = !empty($params['payload'])  ? str_replace('\\', '', $params['payload'])  : null;
        $params['response'] = !empty($params['response']) ? str_replace('\\', '', $params['response']) : null;

        $activity = new self;

        $activity->route       = $params['route'];
        $activity->method      = $params['method'];
        $activity->user_id     = $params['user_id'];
        $activity->payload     = $params['payload'];
        $activity->status_code = $params['status_code'];
        $activity->response    = $params['response'];

        $activity->save();

        return $activity ? true : false;
    }
}
