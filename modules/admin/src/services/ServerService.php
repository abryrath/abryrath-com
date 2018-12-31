<?php

namespace modules\adminmodule\services;

use craft\base\Component;
use modules\adminmodule\models\Server;


class ServerService extends Component
{
    public function getServerOptions()
    {
        $servers = Server::find()
            ->orderBy('dateCreated')
            ->all();

        $servers = array_map(function ($server) {
            return [
                'key' => $server->id,
                'value' => $server->id,
                'label' => $server->displayName,
            ];
        }, $servers);

        return $servers;
    }
}