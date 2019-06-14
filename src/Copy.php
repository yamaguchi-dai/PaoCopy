<?php
/**
 * Created by dai.yamaguchi
 * DATE: 2019/05/22
 *
 */

namespace PaoCopy;

use PaoCopy\Core\PSql;

class Copy {

    /** @var string copyのdelimiter用　\tだと2byte扱いでエラーになるためEを不要 */
    const DELIMITER_TAB = "E'\t'";
    /** @var string copyのdelimiter用 */
    const DELIMITER_COMMA = "','";

    use PSql;

    /**
     * copy from
     * @param string $table_name
     * @param string $csv_path
     * @param string $delimiter
     * @param bool $is_header
     * @param bool $is_vacuum
     */
    public static function from(string $table_name, string $csv_path, string $delimiter = self::DELIMITER_COMMA, bool $is_header = FALSE, bool $is_vacuum = TRUE): void {
        $heder_str = $is_header ? "HEADER true ," : "";
        $c = "\copy " . $table_name . " FROM '" . $csv_path . "' (FORMAT csv," . $heder_str . "DELIMITER " . $delimiter . ')';
        $exec_cmd = self::psql() . ' -c "' . $c . '"';
        $copy_res = exec($exec_cmd);
        self::info('Pao From! ' . $copy_res);
        if ($is_vacuum) \DB::statement('VACUUM ' . $table_name);
    }

    /**
     * copy to
     * @param string $out_csv_path
     * @param string $sql
     * @param array $prm
     * @param string $delimiter
     * @param bool $is_header
     * @param string $quote
     */
    static function to(string $out_csv_path, string $sql, array $prm = [], string $delimiter = self::DELIMITER_COMMA, bool $is_header = FALSE, string $quote = ' '): void {
        $psql = self::psql();
        $header_bool = $is_header ? "true" : "false";
        $cmd = $psql . '-c "' . "\copy ( " . $sql . " ) TO " . $out_csv_path . ' '
            . "(FORMAT CSV"
            . ",HEADER " . $header_bool
            . ",QUOTE '" . $quote . "'"
            . ",DELIMITER " . $delimiter . ')'
            . '"';
        $res = exec($cmd);
        self::info('Pao To! ' . $res);
    }

}