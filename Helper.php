<?php

// Auto filtering functions & error handlers easily configurable for the FEMBLOX environment.
// https://github.com/FEMBLOX/Helper

namespace FEMBLOX;

use FEMBLOX\DB;

class Helper {
    public function filter(int $type, string $var, int $filter) { // This is a safer filter because it also strips tags and takes time off typing!
        return (string)strip_tags(filter_input($type, $var, $filter));
    }

    public function exists($var) { // HTML forms allow blank & people could js do this soo filter too.
        $x = isset($_POST[$var]);

        if ($x) {
            if ($_POST[$var] != "") return true;
        }

        return false;
    }

    public function error($url, $error, $flush = false) { // This is useful for the FEMBLOX Codebase.
        header("Location: /$url?error=$error");
        if ($flush) ob_end_flush();
        exit;
    }

    public function regex($var, $regex) { // just faster, simply. (also apparently theres a buffer overflow thing soo)
        if (strlen($var) > 50) return false; // "prevent" buffer overflow?? idk how the exploit works a friend just told me.
        return preg_match($regex, $var);
    }

    public function validateWarning() { // warning checker (this might stay in the open-sourced Helper)
        $secure = $_COOKIE["_ROBLOSECURITY"];

        if (!preg_match("/|WARNING_DO_NOT_SHARE|/", $secure)) {
            // usually i'd call the $session->validate() function butttttt lets just append to be safe so i dont have to open source
            // the very shitty session code but it works jwt for the win ig??
            setcookie(".ROBLOSECURITY", "|WARNING_DO_NOT_SHARE|".$secure, time() + (36400 * 365), "/");
        }
    }

    public function filterGender($v) {
        return $v == "Male" || $v == "Female";
    }

    public function getUserData($ID) {
        $db = DB::getInstance();

        $result = $db->run("SELECT * FROM Users WHERE ID = :id", [":id" => $ID])->fetch(\PDO::FETCH_ASSOC);
        
        $result["Password"] = null;
        $result["IP"] = null;

        return $result;
    }
}

?>
