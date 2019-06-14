<?php
/**
 * Created by dai.yamaguchi
 * DATE: 2019/06/14
 *
 */
namespace PaoCopy\Core;
trait PSql {

    /**
     * PSql constructor.
     */
    private static function psql():string {
        $psql = 'PGPASSWORD=' . config('database.connections.pgsql.password') . ' psql -h ' . config('database.connections.pgsql.host') . ' -d ' . config('database.connections.pgsql.database') . ' -U ' . config('database.connections.pgsql.username') . ' ';
        return $psql;
    }

    private static function info($str) {
        echo "\033[0;32m$str\033[0m" . PHP_EOL;
    }
}