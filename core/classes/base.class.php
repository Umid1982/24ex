<?php

class BASE
{

    function __construct($db, $totg = false)
    {
        $this->connect($db, $totg);
    }

    function connect($db, $totg = false)
    {
        @$this->dbl = mysqli_connect($db['host'], $db['login'], $db['pass']);

        if (!$this->dbl) {
            if (!$totg) die("Mysql error, try later");
            else return false;
        }

        mysqli_select_db($this->dbl, $db['base']);
        mysqli_query($this->dbl, "SET NAMES 'utf8'");
        mysqli_query($this->dbl, "SET collation_connection = 'utf8_general_ci'");
        mysqli_query($this->dbl, "SET collation_server = 'utf8_general_ci'");
        mysqli_query($this->dbl, "SET character_set_client = 'utf8'");
        mysqli_query($this->dbl, "SET character_set_connection = 'utf8'");
        mysqli_query($this->dbl, "SET character_set_results = 'utf8'");
        mysqli_query($this->dbl, "SET character_set_server = 'utf8'");
        mysqli_query($this->dbl, "SET sql_mode = ''");
    }

    function close()
    {
        mysqli_close($this->dbl);
    }

    function mres($line)
    {
        return mysqli_real_escape_string($this->dbl, $line);
    }

    function last_count()
    {
        $q = 'SELECT FOUND_ROWS() count';
        $res = mysqli_query($this->dbl, $q);
        $row = mysqli_fetch_assoc($res);
        return $row['count'];
    }

    function q($q)
    {
        return mysqli_query($this->dbl, $q);
    }

    function insert_id()
    {
        return mysqli_insert_id($this->dbl);
    }

    function truncate($table)
    {
        $q = 'TRUNCATE TABLE `' . $table . '`';
        $this->q($q);
    }

    // ТРАНЗАКЦИИ
    function trans_begin()
    {
        mysqli_query($this->dbl, 'BEGIN');
    }

    function trans_commit()
    {
        mysqli_query($this->dbl, 'COMMIT');
    }

    function trans_rollback()
    {
        mysqli_query($this->dbl, 'ROLLBACK');
    }

    // UNI
    function uni_insert($table, $data, $delayed = false)
    {
        $ins = array();
        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                if (is_null($v)) $v = 'NULL';
                else $v = '"' . $this->mres($v) . '"';

                $ins[] = '`' . $k . '` = ' . $v;
            }
        }

        $q = 'INSERT  INTO `' . $table . '` SET ' . implode(' , ', $ins); //echo $q;
        if (!$this->q($q)) {
            file_put_contents(_ROOT_ . '/tmp/sql_insert.txt', $q . "\r\n", FILE_APPEND);
            return false;
        }

        return $this->insert_id();
    }

    function uni_insert_multi($table, $fields, $vals)
    {
        $todo = array();
        $step = 0;

        $fields_arr = array();
        foreach ($fields as $of) $fields_arr[] = '`' . $of . '`';

        foreach ($vals as $one) {
            if (!isset($todo[$step])) $todo[$step] = array();

            $todo[$step][] = $one;
            if (count($todo[$step]) >= 100) $step++;
        }

        foreach ($todo as $vals_arr) {
            $ins = array();
            $ins_vals = array();
            foreach ($vals_arr as $one_vals) {
                $one_ins_vals = array();
                foreach ($one_vals as $item) {
                    $one_ins_vals[] = '"' . $this->mres($item) . '"';
                }
                $ins_vals[] = '(' . implode(',', $one_ins_vals) . ')';
            }
            $q = 'INSERT INTO `' . $table . '` (' . implode(',', $fields_arr) . ') VALUES ' . implode(',', $ins_vals); //die($q);
            $this->q($q);
        }
    }

    function uni_update_in($table, $field, $vals, $data)
    {
        $todo = array();
        $step = 0;

        foreach ($vals as $one) {
            if (!isset($todo[$step])) $todo[$step] = array();

            $todo[$step][] = $one;
            if (count($todo[$step]) >= 100) $step++;
        }

        $upd = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $val = (($v['eq'] == 'IN' || $v['eq'] == 'NOT IN') || preg_match('#^`.+?`$#si', $v['val'])) ? $v['val'] : '"' . $this->mres($v['val']) . '"';

                $upd[] = '`' . $k . '` ' . $this->mres($v['eq']) . ' ' . $val;
            } else {
                $upd[] = '`' . $k . '` = "' . $this->mres($v) . '"';
            }
        }

        foreach ($todo as $vals_arr) {
            $q = 'UPDATE `' . $table . '` SET ' . implode(' , ', $upd) . ' WHERE `' . $field . '` IN (' . implode(',', $vals_arr) . ')'; //die($q);
            $this->q($q);
        }
    }

    function uni_update($table, $by, $data)
    {
        $upd = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $val = (($v['eq'] == 'IN' || $v['eq'] == 'NOT IN') || preg_match('#^`.+?`$#si', $v['val'])) ? $v['val'] : '"' . $this->mres($v['val']) . '"';

                $upd[] = '`' . $k . '` ' . $this->mres($v['eq']) . ' ' . $val;
            } else {
                if (is_null($v)) $v = 'NULL';
                else $v = '"' . $this->mres($v) . '"';

                $upd[] = '`' . $k . '` = ' . $v;
            }
        }

        $whs = array('1=1');
        if (count($by)) foreach ($by as $k => $v) {
            if (is_array($v)) {
                $val = (in_array($v['eq'], array('IN', 'NOT IN', 'BETWEEN')) || preg_match('#^`.+?`$#si', $v['val'])) ? $v['val'] : '"' . $this->mres($v['val']) . '"';

                $whs[] = '`' . $k . '` ' . $this->mres($v['eq']) . ' ' . $val;
            } else {
                $whs[] = '`' . $k . '` = "' . $this->mres($v) . '"';
            }
        }

        $q = 'UPDATE `' . $table . '` SET ' . implode(' , ', $upd) . ' WHERE ' . implode(' AND ', $whs); //die($q);
        $this->q($q);
    }

    function uni_count($table, $by = array())
    {
        return $this->uni_select($table, $by, $order = array(), $single = false, $offset = 0, $pp = 0, $count = true);
    }

    function uni_select($table, $by = array(), $order = array(), $single = false, $offset = 0, $pp = 0, $count = false)
    {
        $whs = array('1=1');
        if (count($by)) foreach ($by as $k => $v) {
            $k = preg_replace('#\[[\d]+\]#si', '', $k);

            if (is_array($v)) {
                $val = ($v['eq'] == 'IN' || $v['eq'] == 'NOT IN') ? $v['val'] : '"' . $this->mres($v['val']) . '"';
                $whs[] = '`' . $k . '` ' . $this->mres($v['eq']) . ' ' . $val;
            } else {
                if (is_null($v)) $whs[] = '`' . $k . '` IS NULL';
                else $whs[] = '`' . $k . '` = "' . $this->mres($v) . '"';
            }
        }

        if (!$count) {
            $ord = array();
            if (count($order)) foreach ($order as $k => $v) {
                $ord[] = '`' . $k . '` ' . $v;
            }
            $ord_line = (count($ord)) ? 'ORDER BY ' . implode(' , ', $ord) : '';

            $lim = (int)$pp > 0 ? ' LIMIT ' . (int)$offset . ',' . (int)$pp : '';

            $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `' . $table . '` WHERE ' . implode(' AND ', $whs) . ' ' . $ord_line . $lim; //echo $q;
            $res = $this->q($q);

            if (!$res) file_put_contents(_ROOT_ . '/tmp/sql_select.txt', $q . "\r\n", FILE_APPEND);
            //die($q);

            if (!$single) {
                $ret = array();
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) $ret[] = $row;
                }
            } else {
                if (mysqli_num_rows($res) > 0) {
                    $ret = mysqli_fetch_assoc($res);
                } else {
                    $ret = false;
                }
            }
        } else {
            $q = 'SELECT COUNT(*) cc FROM `' . $table . '` WHERE ' . implode(' AND ', $whs); //echo $q.'<br>';
            $res = $this->q($q);
            $ret = mysqli_fetch_assoc($res);
            $ret = $ret['cc'];
        }

        return $ret;
    }

    function uni_select_one($table, $by = array(), $order = array())
    {
        return $this->uni_select($table, $by, $order, true);
    }

    function uni_delete($table, $whs = array())
    {
        $whs_arr = array();
        $wh_line = '';
        if (count($whs) > 0) {
            foreach ($whs as $k => $v) {
                if (is_array($v)) {
                    $val = ($v['eq'] == 'IN' || $v['eq'] == 'NOT IN') ? $v['val'] : '"' . $this->mres($v['val']) . '"';
                    $whs_arr[] = '`' . $k . '` ' . $this->mres($v['eq']) . ' ' . $val;
                } else {
                    $whs_arr[] = '`' . $k . '` = "' . $this->mres($v) . '"';
                }
            }

            $wh_line = ' WHERE ' . implode(' AND ', $whs_arr);
        }

        $q = 'DELETE FROM `' . $table . '`' . $wh_line; //echo $q;
        $this->q($q);

        if (mysqli_affected_rows($this->dbl) > 0) return true;
        else return false;
    }

    function get_columns_defaults($table)
    {
        $q = "SHOW COLUMNS FROM `" . $this->mres($table) . "`";
        $result = $this->q($q);

        $ret = array();
        while ($row = mysqli_fetch_array($result)) {
            $ret[$row['Field']] = $row['Default'];
        }

        return $ret;
    }

    // REG / AUTH
    function goReg($email, $pass, $secret, $fname = '')
    {
        $email = strtolower($email);

        $check = $this->uni_select('users', ['user_email' => $email]);
        if (count($check) > 0) return ['result' => false];

        $salt = genPass(5, 5);
        $hash = sha1($pass . $salt);

        $data = [
            'user_email' => $email,
            'user_hash' => $hash,
            'user_salt' => $salt,
            'user_tg_code' => genPass(8, 16),
            'user_2fa_secret' => $secret,
            'user_dt_reg' => nowDT(),
            'user_fname' => $fname,
            'user_bal_num' => $this->genMainUserBalNum(),
        ];

        $user_id = $this->uni_insert('users', $data);
        return ['result' => true, 'user_id' => $user_id];
    }

    function goLogin($email, $pass)
    {
        $email = strtolower($email);

        $user_data = $this->uni_select_one('users', ['user_email' => $email]);
        if ($user_data === false) return ['result' => false];

        $hash = sha1($pass . $user_data['user_salt']);
        if ($hash != $user_data['user_hash']) return ['result' => false];

        return ['result' => true, 'user_data' => $user_data];
    }

    // USERS
    function getUsers($pg, $pp, $sort = [], $search = '')
    {
        if ($search != '') {
            $wh = 'WHERE `user_email` LIKE "%' . $this->mres($search) . '%" OR 
						`user_fname` LIKE "%' . $this->mres($search) . '%" OR 
						`user_lname` LIKE "%' . $this->mres($search) . '%" OR 
						`user_birth_city` LIKE "%' . $this->mres($search) . '%" OR 
						`user_dt_reg` LIKE "%' . $this->mres($search) . '%"';
        }

        if (count($sort) > 0) {
            $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        }

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `users` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $row['user_id'] = (int)$row['user_id'];
            $ret[] = $row;
        }

        return $ret;
    }

    function getUserBalsSumsUsd($user_id)
    {
        $q = 'SELECT SUM(ub.`ub_value` *  b.`bal_rate`) sm FROM `bals` b, `users_bals` ub WHERE ub.`bal_id` = b.`bal_id` AND ub.`user_id` = ' . (int)$user_id;
        $res = $this->q($q);
        $row = mysqli_fetch_assoc($res);
        return $row['sm'];
    }

    function getUserInvestSumsUsd($user_id)
    {
        $q = 'SELECT SUM(ui.`ui_value_now` *  b.`bal_rate`) sm FROM `bals` b, `users_invest` ui 
				WHERE ui.`bal_id` = b.`bal_id` AND ui.`ui_status` IN (0,2) AND ui.`user_id` = ' . (int)$user_id;
        $res = $this->q($q);
        $row = mysqli_fetch_assoc($res);
        return $row['sm'];
    }

    function getUserPayoutSumsUsd($user_id)
    {
        $q = 'SELECT SUM(p.`pay_value` *  b.`bal_rate`) sm FROM `payments` p, `bals` b 
				WHERE p.`bal_id` = b.`bal_id` AND p.`pay_status` IN (0,1,5,6,7,9) AND p.`pay_type` = 1 AND p.`user_id` = ' . (int)$user_id;
        $res = $this->q($q);
        $row = mysqli_fetch_assoc($res);
        return $row['sm'];
    }

    function genUAPIkey()
    {
        $len = $this->setGet('api_len');

        $end = false;
        while (!$end) {
            $temp = genPass(128, 128, true);
            $key = strtoupper(substr($temp, 0, $len));

            $test = $this->uni_select_one('users', ['user_api_key' => $key]);
            if ($test === false) break;
        }

        return $key;
    }

    // ADMINS
    function getAdmins($pg, $pp, $sort = [], $search = '')
    {
        if ($search != '') {
            $wh = 'WHERE `admin_login` LIKE "%' . $this->mres($search) . '%"';
        }

        if (count($sort) > 0) {
            $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        }

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `admins` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getAcc($acc_type, $acc_id)
    {
        if ($acc_type == ACC_ADMIN) $data = $this->uni_select_one('admins', ['admin_id' => (int)$acc_id]);
        else if ($acc_type == ACC_USER) $data = $this->uni_select_one('users', ['user_id' => (int)$acc_id]);
        else $data = false;

        if ($data === false) return false;

        $ret = $data;
        if ($acc_type == ACC_ADMIN) {
            $ret['acc_id'] = $data['admin_id'];
            $ret['acc_name'] = $data['admin_login'];
            $ret['acc_param'] = 'admin_' . $data['admin_id'];
            $ret['acc_mark'] = '[ADMIN]';

            $ret['tg'] = $data['admin_tg'];
            $ret['tg_chat_id'] = $data['admin_tg_chat_id'];
        } else if ($acc_type == ACC_USER) {
            $ret['acc_id'] = $data['user_id'];
            $ret['acc_name'] = $data['user_email'];
            $ret['acc_param'] = 'user_' . $data['user_id'];
            $ret['acc_mark'] = '[USER]';

            $ret['tg'] = $data['user_tg'];
            $ret['tg_chat_id'] = $data['user_tg_chat_id'];
        }

        return $ret;
    }

    function adminsSearch($s, $lim)
    {
        $q = 'SELECT * FROM `admins` WHERE `admin_id` = ' . (int)$s . ' OR `admin_login` LIKE "%' . $this->mres($s) . '%" ORDER BY `admin_login` LIMIT 0,' . $lim;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $one = [];
            $one['id'] = 'admin_' . $row['admin_id'];
            $one['label'] = $row['admin_login'];
            $one['value'] = $row['admin_login'];

            $ret[] = $one;
        }

        return $ret;
    }

    // FEEDBACKS
    function getFeedbacks($pg, $pp, $sort = [], $search = '')
    {
        if ($search != '') {
            $wh = 'WHERE `sup_msg_email` LIKE "%' . $this->mres($search) . '%" OR 
						`sup_msg_title` LIKE "%' . $this->mres($search) . '%"';
        }

        if (count($sort) > 0) {
            $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        }

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `sup_msgs` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    // USERS
    function logWrite($log_type, $old_data = '', $new_data = '', $acc_type = 0, $acc_id = null)
    {
        $ip = getClientIp();
        $device = getClientDevice();

        $data = [
            'acc_type' => $acc_type,
            'acc_id' => $acc_id,
            'log_type' => $log_type,
            'log_dt' => time(),
            'log_ip' => $ip,
            'log_device' => $device,
            'log_ua' => $_SERVER['HTTP_USER_AGENT'],
            'log_data_old' => (string)$old_data,
            'log_data_new' => (string)$new_data,
        ];

        $this->uni_insert('logs', $data);

        // LAST ACTION
        if ($acc_type == ACC_USER) $this->uni_update('users', ['user_id' => $acc_id], ['user_last_action' => time(), 'user_last_ip' => $ip]);
        else if ($acc_type == ACC_ADMIN) $this->uni_update('admins', ['admin_id' => $acc_id], ['admin_last_action' => time(), 'admin_last_ip' => $ip]);
    }

    function usersSearch($s, $lim)
    {
        $q = 'SELECT * FROM `users` WHERE `user_id` = ' . (int)$s . ' OR `user_email` LIKE "%' . $this->mres($s) . '%" ORDER BY `user_email` LIMIT 0,' . $lim;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $one = [];
            $one['raw_id'] = $row['user_id'];
            $one['id'] = 'user_' . $row['user_id'];
            $one['label'] = $row['user_email'];
            $one['value'] = $row['user_email'];

            $ret[] = $one;
        }

        return $ret;
    }

    // BALTYPES
    function getBalTypes()
    {
        $bal_types = [];
        $temp = $this->uni_select('bal_types');
        if (count($temp) > 0) foreach ($temp as $one) {
            $bal_types[$one['bal_type_id']] = $one['bal_type_title'];
        }
        return $bal_types;
    }

    // BALS FOR ADD BY USER ID
    function getBalsForAdd($user_id)
    {
        $ubs = $this->uni_select('users_bals', ['user_id' => $user_id]);
        $not = [];
        if (count($ubs) > 0) foreach ($ubs as $one_ub) $not[] = $one_ub['bal_id'];

        $not_line = (count($not) > 0) ? ' AND `bal_id` NOT IN (' . implode(',', $not) . ')' : '';
        $q = 'SELECT * FROM `bals` WHERE `bal_status_active` = 1 ' . $not_line;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function addBalToUser($user_id, $bal_id)
    {
        $test = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'bal_id' => $bal_id]);
        if ($test !== false) {
            return false;
        } else {
            $one_bal = $this->uni_select_one('bals', ['bal_id' => $bal_id]);
            if ($one_bal['bal_status_active'] == 0) {
                return false;
            } else {
                $this->uni_insert('users_bals', ['user_id' => $user_id, 'bal_id' => $bal_id, 'ub_num' => $this->genUBNum()]);
                return $one_bal;
            }
        }
    }

    // DEFAULT BALS TO USER ON REG
    function setDefaultBals($user_id)
    {
        $ubs = $this->getUBs($user_id);
        $bals = $this->uni_select('bals', ['bal_status_active' => 1, 'bal_default' => 1]);
        if (count($bals) > 0) foreach ($bals as $one) {
            if (isset($ubs[$one['bal_id']])) continue;

            $this->uni_insert('users_bals', ['bal_id' => $one['bal_id'], 'user_id' => $user_id, 'ub_num' => $this->genUBNum()]);
        }
    }

    function genMainUserBalNum()
    {
        $pref = $this->setGet('ub_pref');
        $len = $this->setGet('ub_len');

        $end = false;
        while (!$end) {
            $temp = genPass(128, 128, true);
            $num = strtoupper($pref . substr($temp, 0, $len));

            $test = $this->uni_select_one('users', ['user_bal_num' => $num]);
            if ($test === false) break;
        }

        return $num;
    }

    function genUBNum()
    {
        $pref = $this->setGet('ub_pref');
        $len = $this->setGet('ub_len');

        $end = false;
        while (!$end) {
            $temp = genPass(128, 128, true);
            $num = strtoupper($pref . substr($temp, 0, $len));

            $test = $this->uni_select_one('users_bals', ['ub_num' => $num]);
            if ($test === false) break;
        }

        return $num;
    }

    function searchBals($sort, $from, $pp, $s)
    {
        $ret = [];

        $search_line = ($s == '') ? '' : ' WHERE (`bal_name` LIKE "%' . $this->mres($s) . '%" OR `bal_title` LIKE "%' . $this->mres($s) . '%")';
        $sort_line = (count($sort) > 0) ? ' ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']) : '';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `bals` ' . $search_line . $sort_line . ' LIMIT ' . $from . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        if (mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) $ret[] = $row;
        return $ret;
    }

    function getAllBals()
    {
        $ret = [];
        $bals = $this->uni_select('bals');
        if (count($bals) > 0) foreach ($bals as $one) $ret[$one['bal_id']] = $one;
        return $ret;
    }

    function balCanPay($bal_id, $type)
    {
        $bal_data = $this->uni_select_one('bals', ['bal_id' => $bal_id, 'bal_status_active' => 1]);
        if ($bal_data === false) return false;

        if ($type == 'in') {
            if ($bal_data['bal_status_payin'] == 0 || $bal_data['bal_payin_list'] == '') return false;
            else return true;
        } else if ($type == 'out') {
            if ($bal_data['bal_status_payout'] == 0 || $bal_data['bal_payout_list'] == '') return false;
            else return true;
        } else if ($type == 'voucher') {
            if ($bal_data['bal_status_voucher'] == 0) return false;
            else return true;
        } else if ($type == 'transfer') {
            if ($bal_data['bal_status_transfer'] == 0) return false;
            else return true;
        } else {
            return false;
        }
    }

    // UBS
    function getUBs($user_id, $bal_types = [], $pg = 1, $pp = 0, $sort = [], $s = '')
    {
        $lim = ($pp == 0) ? '' : ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;

        $search = '';
        if ($s != '') $search = ' AND (b.`bal_name` LIKE "%' . $this->mres($s) . '%" OR b.`bal_title` LIKE "%' . $this->mres($s) . '%")';

        if (count($sort) > 0) {
            $sr = ' ORDER BY ub.`' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        } else $sr = ' ORDER BY ub.`bal_id` DESC';

        $q = 'SELECT ub.*, b.* FROM `users_bals` ub, `bals` b WHERE ub.`bal_id` = b.`bal_id` AND ub.`user_id` = ' . (int)$user_id . $search . $sr . $lim;
        $res = $this->q($q);

        //echo $q;

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $row['bal_icon'] = getBalIcon($row['bal_icon']);
            if (isset($bal_types[$row['bal_type_id']])) $row['bal_type_line'] = $bal_types[$row['bal_type_id']];

            $ret[$row['bal_id']] = $row;
        }

        return $ret;
    }

    function getUbByBal($user_id, $bal_id) // get or create
    {
        $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'bal_id' => (int)$bal_id]);
        if ($ub_data === false) {
            if ($this->addBalToUser($user_id, $bal_id) === false) {
                $ub_data = false;
            } else {
                $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'bal_id' => (int)$bal_id]);
            }
        }

        return $ub_data;
    }

    function getVouchers($pg, $pp, $sort = [], $user_id = '', $status = '')
    {
        $wh_arr = [];

        if ($user_id != '') $wh_arr[] = '`user_id` = ' . (int)$user_id;
        if ($status != '') $wh_arr[] = '`voucher_status` = ' . (int)$status;

        $wh_line = (count($wh_arr) > 0) ? ' WHERE ' . implode(' AND ', $wh_arr) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `voucher_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `vouchers` ' . @$wh_line . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    // PAYMENTS
    function getPayments($pg, $pp, $sort = [], $user_id = '', $type = '', $status = '')
    {
        $wh_arr = [];

        if ($user_id != '') $wh_arr[] = '`user_id` = ' . (int)$user_id;
        if ($type != '') $wh_arr[] = '`pay_type` = ' . (int)$type;
        if ($status != '') $wh_arr[] = '`pay_status` = ' . (int)$status;

        $wh_line = (count($wh_arr) > 0) ? ' WHERE ' . implode(' AND ', $wh_arr) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `pay_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `payments` ' . @$wh_line . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getPaystat($pg, $pp, $sort = [], $user_id = '')
    {
        $wh_arr = [];

        if ($user_id != '') $wh_arr[] = 'ps.`user_id` = ' . (int)$user_id;

        $wh_line = (count($wh_arr) > 0) ? ' AND ' . implode(' AND ', $wh_arr) : '';

        if (count($sort) > 0) $sr = 'ORDER BY ps.`' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY ps.`ps_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS ps.*, u.* FROM `payments_stat` ps, `users` u WHERE 
					ps.`user_id` = u.`user_id` ' . @$wh_line . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }


    // создание ваучеров
    function newVoucher($bal_id, $value, $user_id = NULL, $ub_id = NULL)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            if ($ub_id === NULL) {
                $ub_data = ['ub_value' => '99999999999'];
            } else {
                $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'ub_id' => $ub_id]);
            }

            $bal_data = $this->uni_select_one('bals', ['bal_id' => $bal_id]);
            if ($bal_data === false || $ub_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $com = $value / 100 * $bal_data['bal_com_voucher'];

                if ($value < $bal_data['bal_min_voucher'] || $value > $bal_data['bal_max_voucher']) {
                    //$this->ajax_return['info'] = 'Сумма должна в диапозоне '.cutZeros($bal_data['bal_min_voucher']).'-'.cutZeros($bal_data['bal_max_voucher']);
                    $return = ['result' => PAY_ERR_LIMITS, 'min' => cutZeros($bal_data['bal_min_voucher']), 'max' => cutZeros($bal_data['bal_max_voucher'])];
                } else if ($bal_data['bal_status_voucher'] == 0) {
                    //$this->ajax_return['info'] = 'Создание ваучера невозможно невозможно';
                    $return = ['result' => PAY_ERR_LOCK];
                } else if ($value + $com > $ub_data['ub_value']) {
                    //$this->ajax_return['info'] = 'Сумма больше доступного баланса';
                    $return = ['result' => PAY_ERR_UB_VALUE];
                } else {
                    $len = $this->setGet('voucher_len');

                    $end = false;
                    while (!$end) {
                        $temp = genPass(128, 128, true);
                        $voucher_code = strtoupper(substr($temp, 0, $len));
                        if (!$this->hasVoucherCode($voucher_code)) break;
                    }

                    // всё ок, можно создавать ваучер
                    $ins = [
                        'bal_id' => $bal_id,
                        'voucher_value' => $value,
                        'voucher_code' => $voucher_code,
                        'voucher_dt_create' => time(),
                        'voucher_status' => VOUCHER_ACTIVE,
                        'user_id' => $user_id,
                        'ub_id' => $ub_id,
                    ];

                    $voucher_id = $this->uni_insert('vouchers', $ins);
                    if ($voucher_id === false) {
                        $return = ['result' => PAY_ERR_NA];
                    } else {
                        // ок, списываем бабки
                        if ($user_id !== NULL) {
                            $this->changeUserBal($user_id, $ub_data['ub_id'], PS_TYPE_OUT, $value, REASON_NEW_VOUCHER, $voucher_id);
                            $this->changeUserBal($user_id, $ub_data['ub_id'], PS_TYPE_OUT, $com, REASON_NEW_VOUCHER_COM, $voucher_id);

                            // остатки
                            $this->changeChValue($ub_data['bal_id'], 'minus', $value, $user_id, false, true);

                            $this->logWrite(LOG_VOUCHER_NEW, '', $voucher_id, ACC_USER, $user_id);
                        }

                        $return = ['result' => PAY_ERR_OK, 'voucher_id' => $voucher_id, 'voucher_code' => $voucher_code];
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    // переводы
    function transferNew($user_id, $ub_id, $value)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $user_data = $this->uni_select_one('users', ['user_id' => $user_id]);
            $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'ub_id' => $ub_id]);

            if ($user_data == false || $ub_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $bal_data = $this->uni_select_one('bals', ['bal_id' => $ub_data['bal_id']]);

                $com = $value / 100 * $bal_data['bal_com_transfer'];

                if ($bal_data['bal_status_transfer'] == 0 || $ub_data['ub_lock'] == 1) {
                    //$this->ajax_return['info'] = 'Перевод недоступен';
                    $return = ['result' => PAY_ERR_LOCK];
                } else if ($value < $bal_data['bal_min_transfer'] || $value > $bal_data['bal_max_transfer']) {
                    //$this->ajax_return['info'] = 'Сумма должна быть в диапазоне '.cutZeros($bal_data['bal_min_transfer']).' - '.cutZeros($bal_data['bal_max_transfer']);
                    $return = ['result' => PAY_ERR_LIMITS, 'min' => cutZeros($bal_data['bal_min_transfer']), 'max' => cutZeros($bal_data['bal_max_transfer'])];
                } else if (($value + $com) > $ub_data['ub_value']) {
                    $return = ['result' => PAY_ERR_UB_VALUE];
                } else {
                    // всё ок, можно создавать
                    $ins = [
                        'user_id' => $user_id,
                        'bal_id' => $ub_data['bal_id'],
                        'ub_id' => $ub_data['ub_id'],
                        'pay_value' => $value,
                        'pay_com' => $com,
                        'pay_dt' => time(),
                        'pay_end' => time() + ($this->setGet('pay_life') * 60),
                        'pay_status' => PAY_STATUS_NEW,
                        'pay_type' => PAY_TYPE_TRANSFER,
                    ];

                    $pay_id = $this->uni_insert('payments', $ins);
                    if ($pay_id === false) {
                        //$this->ajax_return['info'] = 'Неизвестная ошибка';
                        $return = ['result' => PAY_ERR_NA];
                    } else {
                        // ссылка на оплату
                        //$this->ajax_return['result'] = true;
                        //$this->ajax_return['pay_link'] = '/'._LANG_.'/payment/transfer?pay='.$pay_id;

                        $this->logWrite(LOG_PAY_TRANFER_NEW, '', $pay_id, ACC_USER, $user_id);
                        $return = ['result' => PAY_ERR_OK, 'pay_id' => $pay_id, 'link' => '/' . _LANG_ . '/payment/transfer?pay=' . $pay_id];
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    function transferSetPayData($user_id, $pay_id, $user_bal_num, $pay_pass)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $pay_data = $this->uni_select_one('payments', ['user_id' => $user_id, 'pay_id' => $pay_id]);
            $user_data_to = $this->uni_select_one('users', ['user_bal_num' => $user_bal_num]);

            if ($pay_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else if ($user_bal_num == '' || $user_data_to === false) {
                $return = ['result' => PAY_ERR_UB_NUM];
            } else {
                $bal_id = $pay_data['bal_id'];
                $user_id_to = $user_data_to['user_id'];

                $ub_id_to = $this->getOrCreateUB($user_id_to, $bal_id);
                $ub_data_to = $this->uni_select_one('users_bals', ['ub_id' => $ub_id_to]);

                if (!$this->checkPayPass($user_id, $pay_pass)) {
                    $return = ['result' => PAY_ERR_PAYCODE];
                } else {
                    $ub_data_from = $this->uni_select_one('users_bals', ['ub_id' => $pay_data['ub_id']]);
                    if ($ub_data_from === false) {
                        $return = ['result' => PAY_ERR_INPUT_DATA];
                    } else {
                        $bal_data_from = $this->getBalFull($ub_data_from['bal_id']);
                        $need_change = false;
                        $check_change_ok = true;

                        /* ОБМЕНОВ ПОКА НЕТ
					if ($ub_data_from['bal_id']!=$ub_data_to['bal_id'])
						{
						$bal_data_to = $this->getBalFull($ub_data_to['bal_id']);

						// баланс не соответствует, делаем обмен
						if ($bal_data_from['bal_transfer_change']==1 && @$bal_data_to['bal_transfer_change']==1 && @$bal_data_to['bal_status_transfer']==1)
							{
							$need_change = true;
							$to_val = slkDouble($bal_data_from['bal_rate'] * $pay_data['pay_value'] / $bal_data_to['bal_rate']);
							$from_com = slkDouble($pay_data['pay_value'] * $bal_data_from['ch_in_com'] / 100);
							}
						else
							{
							$return = ['result'=>PAY_ERR_UB_NUM_BAL];
							$check_change_ok = false;
							//Баланс данного кошелька не соответствует валюте
							}
						}
					*/

                        if ($check_change_ok) {
                            $pay_ps_data = ['props' => $user_bal_num];

                            if ($need_change) {
                                //$pay_ps_data['change_bal_id'] = $ub_data_to['bal_id'];
                                $pay_ps_data['change_val'] = $to_val;
                                $pay_ps_data['change_com'] = $from_com;
                            }

                            // меняем статус
                            $upd = [
                                'pay_status' => PAY_STATUS_GO_PAY,
                                'pay_ps_data' => json_encode($pay_ps_data)
                            ];

                            $this->logWrite(LOG_PAY_TRANFER_PROPS, $pay_id, $user_bal_num, ACC_USER, $user_id);
                            $this->uni_update('payments', ['pay_id' => $pay_id], $upd);

                            $return = ['result' => PAY_ERR_OK];
                        }
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    function transferConfirm($user_id, $pay_id)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $pay_data = $this->uni_select_one('payments', ['user_id' => $user_id, 'pay_id' => $pay_id]);
            if ($pay_data === false) {
                //$this->ajax_return['info'] = 'Платеж не найден';
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $temp = json_decode($pay_data['pay_ps_data'], true);
                if (!isset($temp['props'])) {
                    $return = ['result' => PAY_ERR_INPUT_DATA];
                    //$this->ajax_return['info'] = 'Реквизиты не введены';
                } else {
                    $ub_data_from = $this->uni_select_one('users_bals', ['ub_id' => $pay_data['ub_id']]);
                    $bal_data = $this->uni_select_one('bals', ['bal_id' => $pay_data['bal_id']]);
                    $user_data_from = $this->uni_select_one('users', ['user_id' => $pay_data['user_id']]);

                    $user_bal_num = $temp['props'];
                    $user_data_to = $this->uni_select_one('users', ['user_bal_num' => $user_bal_num]);
                    $ub_data_to = $this->getOrCreateUB($user_data_to['user_id'], $pay_data['bal_id'], true);

                    if ($bal_data === false || $ub_data_to === false || $ub_data_from === false) {
                        $return = ['result' => PAY_ERR_INPUT_DATA];
                    } else {
                        // проверяем деньги на счету с учетом комиссии за конверсию
                        $check_change_ok = true;
                        if (isset($temp['change_bal_id'])) {
                            /*
							обмены отключены
							$bal_data_to = $this->getBalData($temp['change_bal_id']);

							$total_val = $temp['change_com']  + $pay_data['pay_value'] + $pay_data['pay_com'];
							if ($ub_data_from['ub_value']<$total_val)
								{
								$check_change_ok = false;
								$return = ['result'=>PAY_ERR_UB_VALUE];
								}
							else
								{
								// списывем комиссию за перевод
								$this->changeUserBal($ub_data_from['user_id'],$ub_data_from['ub_id'],PS_TYPE_OUT,$temp['change_com'],REASON_CHANGEIN_COM,$pay_id);
								$to_value = $temp['change_val'];
								$to_bal_name = $bal_data_to['bal_name'];
								}
							*/
                        } else {
                            $to_value = $pay_data['pay_value'];
                            $to_bal_name = $bal_data['bal_name'];
                        }

                        if ($check_change_ok) {
                            // переводим
                            // списываем баланс полюбому
                            $this->changeUserBal($ub_data_from['user_id'], $ub_data_from['ub_id'], PS_TYPE_OUT, $pay_data['pay_value'], REASON_TRANSFER_PAY, $pay_id);
                            $this->changeUserBal($ub_data_from['user_id'], $ub_data_from['ub_id'], PS_TYPE_OUT, $pay_data['pay_com'], REASON_TRANSFER_COM, $pay_id);
                            // изменения в платежке
                            if ($bal_data['bal_transfer_auto'] == 1) {
                                // авторежим
                                $upd = [
                                    'pay_status' => PAY_STATUS_DONE,
                                ];

                                $this->writeAdminAlerts('transfer', 'Перевод ID:' . $pay_id . ', пользователи ID:' . $user_data_from['user_id'] .
                                    ' -> ID:' . $user_data_to['user_id'] . ', отправляет ' . cutZeros($pay_data['pay_value']) . ' ' . $bal_data['bal_name'] .
                                    ', получает ' . cutZeros($to_value) . ' ' . $to_bal_name . ' проведен автоматически');

                                // зачисляем деньги
                                $this->changeUserBal($ub_data_to['user_id'], $ub_data_to['ub_id'], PS_TYPE_IN, $to_value, REASON_TRANSFER_GET_PAY, $pay_id);
                                $this->logWrite(LOG_PAY_TRANFER_DONE, $pay_id, '', ACC_USER, $ub_data_from['user_id']);
                            } else {
                                // ручной режим
                                $upd = [
                                    'pay_status' => PAY_STATUS_PAYS,
                                ];

                                $this->writeAdminAlerts('transfer', 'Перевод ID:' . $pay_id . ', пользователи ID:' . $user_data_from['user_id'] .
                                    ' -> ID:' . $user_data_to['user_id'] . ', отправляет ' . cutZeros($pay_data['pay_value']) . ' ' . $bal_data['bal_name'] .
                                    ', получает ' . cutZeros($to_value) . ' ' . $to_bal_name . ' ожидает подтверждения в админке');

                                $this->logWrite(LOG_PAY_TRANFER_PAYS, $pay_id, '', ACC_USER, $ub_data_from['user_id']);
                            }

                            $this->uni_update('payments', ['pay_id' => $pay_id], $upd);

                            $return = ['result' => PAY_ERR_OK];
                        }
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    // пополнение
    function payInNew($user_id, $ub_id, $value)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $user_data = $this->uni_select_one('users', ['user_id' => $user_id]);
            $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'ub_id' => $ub_id]);

            if ($user_data == false || $ub_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $bal_data = $this->uni_select_one('bals', ['bal_id' => $ub_data['bal_id']]);

                $com = $value / 100 * $bal_data['bal_com_payin'];

                if ($value < $bal_data['bal_min_payin'] || $value > $bal_data['bal_max_payin']) {
                    //$this->ajax_return['info'] = 'Сумма должна быть в диапазоне '.cutZeros($bal_data['bal_min_payin']).' - '.cutZeros($bal_data['bal_max_payin']);
                    $return = ['result' => PAY_ERR_LIMITS, 'min' => cutZeros($bal_data['bal_min_payin']), 'max' => cutZeros($bal_data['bal_max_payin'])];
                } else if ($bal_data['bal_status_payin'] == 0 || $bal_data['bal_payin_list'] == '' || $ub_data['ub_lock'] == 1) {
                    //$this->ajax_return['info'] = 'Пополнение невозможно';
                    $return = ['result' => PAY_ERR_LOCK];
                } else {
                    // всё ок, можно создавать
                    $ins = [
                        'user_id' => $user_id,
                        'bal_id' => $ub_data['bal_id'],
                        'ub_id' => $ub_data['ub_id'],
                        'pay_value' => $value,
                        'pay_com' => $com,
                        'pay_dt' => time(),
                        'pay_end' => time() + ($this->setGet('pay_life') * 60),
                        'pay_status' => PAY_STATUS_NEW,
                        'pay_type' => PAY_TYPE_IN,
                    ];

                    $pay_id = $this->uni_insert('payments', $ins);
                    if ($pay_id === false) {
                        //$this->ajax_return['info'] = 'Неизвестная ошибка';
                        $return = ['result' => PAY_ERR_NA];
                    } else {
                        // ссылка на оплату
                        //$this->ajax_return['result'] = true;
                        //$this->ajax_return['pay_link'] = '/'._LANG_.'/payment/payin?pay='.$pay_id;

                        $this->logWrite(LOG_PAYIN_NEW, '', $pay_id, ACC_USER, $user_id);
                        $return = ['result' => PAY_ERR_OK, 'pay_id' => $pay_id, 'link' => '/' . _LANG_ . '/payment/payin?pay=' . $pay_id];
                    }
                }

            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    // вывод
    function payOutNew($user_id, $ub_id, $value)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $user_data = $this->uni_select_one('users', ['user_id' => $user_id]);
            $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'ub_id' => $ub_id]);

            if ($user_data == false || $ub_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $bal_data = $this->uni_select_one('bals', ['bal_id' => $ub_data['bal_id']]);
                $com = $value / 100 * $bal_data['bal_com_payout'];

                if ($value + $com > $ub_data['ub_value']) {
                    //$this->ajax_return['info'] = 'Сумма вывода больше доступного баланса';
                    $return = ['result' => PAY_ERR_UB_VALUE];
                } else if ($value < $bal_data['bal_min_payout'] || $value > $bal_data['bal_max_payout']) {
                    //$this->ajax_return['info'] = 'Сумма должна быть в диапазоне '.cutZeros($bal_data['bal_min_payout']).' - '.cutZeros($bal_data['bal_max_payout']);
                    $return = ['result' => PAY_ERR_LIMITS, 'min' => cutZeros($bal_data['bal_min_payout']), 'max' => cutZeros($bal_data['bal_max_payout'])];
                } else if ($bal_data['bal_status_payout'] == 0 || $bal_data['bal_payout_list'] == '' || $ub_data['ub_lock'] == 1) {
                    //$this->ajax_return['info'] = 'Вывод невозможно';
                    $return = ['result' => PAY_ERR_LOCK];
                } else {
                    // всё ок, можно создавать
                    $ins = [
                        'user_id' => $user_id,
                        'bal_id' => $ub_data['bal_id'],
                        'ub_id' => $ub_data['ub_id'],
                        'pay_value' => $value,
                        'pay_com' => $com,
                        'pay_dt' => time(),
                        'pay_end' => time() + (365 * 60), // 1 год на вывод
                        'pay_status' => PAY_STATUS_NEW,
                        'pay_type' => PAY_TYPE_OUT,
                    ];

                    $pay_id = $this->uni_insert('payments', $ins);
                    if ($pay_id === false) {
                        //$this->ajax_return['info'] = 'Неизвестная ошибка';
                        $return = ['result' => PAY_ERR_NA];
                    } else {
                        // ссылка на оплату
                        //$this->ajax_return['result'] = true;
                        //$this->ajax_return['pay_link'] = '/'._LANG_.'/payment/payout?pay='.$pay_id;

                        $this->logWrite(LOG_PAYIN_NEW, '', $pay_id, ACC_USER, $user_id);

                        // меняем баланс юзера
                        $this->changeUserBal($user_id, $ub_data['ub_id'], PS_TYPE_OUT, $value, REASON_PAYOUT_PAY, $pay_id);
                        $this->changeUserBal($user_id, $ub_data['ub_id'], PS_TYPE_OUT, $com, REASON_PAYOUT_COM, $pay_id);

                        // остатки
                        $this->changeChValue($ub_data['bal_id'], 'minus', $value, $user_id, false, true);

                        $return = ['result' => PAY_ERR_OK, 'pay_id' => $pay_id, 'link' => '/' . _LANG_ . '/payment/payout?pay=' . $pay_id];
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    // обмен, внутренний
    function changeInNew($user_id, $from, $to, $from_val, $pp = '')
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $from_data = $this->getBalFull($from);
            $to_data = $this->getBalFull($to);

            // берем балансы юзеров
            $ub_from_data = $this->getUbByBal($user_id, $from);
            $ub_to_data = $this->getUbByBal($user_id, $to);

            if ($from_val <= 0 || $from_data === false || $to_data === false || $ub_from_data === false || $ub_to_data === false) {
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else if (!$this->checkPayPass($user_id, $pp)) {
                $return = ['result' => PAY_ERR_PAYCODE];
            } else {
                $from_list = $from_data['ch_in_list'] == '' ? [] : explode(',', $from_data['ch_in_list']);

                if (count($from_list) > 0 && !in_array($to, $from_list)) {
                    //$this->ajax_return['info'] = 'Неверные направления обмена';
                    $return = ['result' => PAY_ERR_LOCK];
                } else {
                    // проверяем цифры
                    $to_val = slkDouble($from_data['bal_rate'] * $from_val / $to_data['bal_rate']);
                    $from_com = slkDouble($from_val * $from_data['ch_in_com'] / 100);

                    if ($from_val < $from_data['ch_in_min'] || $from_val > $from_data['ch_in_max']) {
                        //$this->ajax_return['info'] = 'За пределами минимум-максимум';
                        $return = ['result' => PAY_ERR_LIMITS, 'min' => cutZeros($from_data['ch_in_min']), 'max' => cutZeros($from_data['ch_in_max'])];
                    } else if ($to_val < $to_data['ch_in_min'] || $to_val > $to_data['ch_in_max']) {
                        $return = ['result' => PAY_ERR_LIMITS_2, 'min' => cutZeros($to_data['ch_in_min']), 'max' => cutZeros($to_data['ch_in_max'])];
                    } else if ($from_val + $from_com > $ub_from_data['ub_value']) {
                        //$this->ajax_return['info'] = 'Сумма больше вашего баланса';
                        $return = ['result' => PAY_ERR_UB_VALUE];
                    } else if ($to_val > $to_data['ch_value']) {
                        //$this->ajax_return['info'] = 'Сумма больше резерва';
                        $return = ['result' => PAY_ERR_CH_VALUE];
                    } else {
                        // всё ок, создаем платеж
                        $pay_ps_data = json_encode([
                            'pay_ch_bal_id' => $from,
                            'pay_ch_ub_id' => $ub_from_data['ub_id'],
                            'pay_ch_value' => $from_val,
                        ]);

                        $ins = [
                            'user_id' => $user_id,

                            'bal_id' => $to,
                            'ub_id' => $ub_to_data['ub_id'],
                            'pay_value' => $to_val,

                            'pay_com' => $from_com,
                            'pay_ps_data' => $pay_ps_data,

                            'pay_dt' => time(),
                            'pay_end' => time() + ($this->setGet('pay_life') * 60),
                            'pay_status' => PAY_STATUS_NEW,
                            'pay_type' => PAY_TYPE_CHANGE_IN,
                        ];

                        $pay_id = $this->uni_insert('payments', $ins);
                        if ($pay_id === false) {
                            //$this->ajax_return['info'] = 'Внутренняя ошибка, обратитесь к техническую поддержку';
                            $return = ['result' => PAY_ERR_NA];
                        } else {
                            $this->logWrite(LOG_PAYCHANGE_IN_NEW, '', $pay_id, ACC_USER, $user_id);
                            $return = ['result' => PAY_ERR_OK, 'pay_id' => $pay_id, 'link' => '/' . _LANG_ . '/payment/changein?pay=' . $pay_id];
                        }
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }

    function changeInConfirm($user_id, $pay_id)
    {
        $return = ['result' => PAY_ERR_NA];

        try {
            $this->trans_begin();

            $user_data = $this->uni_select_one('users', [
                'user_id' => $user_id
            ]);

            $pay_data = $this->uni_select_one('payments', [
                'user_id' => $user_id,
                'pay_id' => $pay_id,
                'pay_type' => PAY_TYPE_CHANGE_IN,
                'pay_status' => PAY_STATUS_NEW
            ]);
            if ($pay_data === false) {
                //$this->ajax_return['info'] = 'Платеж не найден';
                $return = ['result' => PAY_ERR_INPUT_DATA];
            } else {
                $pay_ps_data = json_decode($pay_data['pay_ps_data'], true);
                $bal_data = $this->getBalFull($pay_ps_data['pay_ch_bal_id']);
                if ($bal_data === false) {
                    //$this->ajax_return['info'] = 'Баланс не найден';
                    $return = ['result' => PAY_ERR_INPUT_DATA];
                } else {
                    // проверяем баланс юзера
                    $pay_ch_ub_data = $this->getUbByBal($user_id, $pay_ps_data['pay_ch_bal_id']);

                    if ($pay_ch_ub_data === false) {
                        //$this->ajax_return['info'] = 'Баланс пользователя не найден';
                        $return = ['result' => PAY_ERR_INPUT_DATA];
                    } else if ($pay_ps_data['pay_ch_value'] + $pay_data['pay_com'] > $pay_ch_ub_data['ub_value']) {
                        //$this->ajax_return['info'] = 'Не хватает средств на счете';
                        $return = ['result' => PAY_ERR_UB_VALUE];
                    } else {
                        // списываем деньги по любому
                        $this->changeUserBal($user_id, $pay_ch_ub_data['ub_id'], PS_TYPE_OUT, $pay_ps_data['pay_ch_value'], REASON_CHANGEIN_PAY, $pay_id);
                        $this->changeUserBal($user_id, $pay_ch_ub_data['ub_id'], PS_TYPE_OUT, $pay_data['pay_com'], REASON_CHANGEIN_COM, $pay_id);

                        $this->changeChValue($pay_ps_data['pay_ch_bal_id'], 'minus', $pay_ps_data['pay_ch_value'], $user_id, false, false);

                        $from_data = $this->getBalData($pay_ps_data['pay_ch_bal_id']);
                        $to_data = $this->getBalData($pay_data['bal_id']);

                        if ($bal_data['ch_in_auto'] == 1) {
                            $new_status = PAY_STATUS_DONE;

                            // начисляем деньги если авто
                            $this->changeUserBal($user_id, $pay_data['ub_id'], PS_TYPE_IN, $pay_data['pay_value'], REASON_CHANGEIN_PAY, $pay_id);

                            // остатки
                            $this->changeChValue($pay_data['bal_id'], 'plus', $pay_data['pay_value'], $user_id);

                            // алерты
                            $this->writeAdminAlerts('changein', 'Внутренний обмен, ID:' . $pay_id .
                                ', пользователь [' . $user_id . '] ' . $user_data['user_email'] .
                                ', меняет ' . cutZeros($pay_ps_data['pay_ch_value']) . ' ' . $from_data['bal_name'] .
                                ', на ' . cutZeros($pay_data['pay_value']) . ' ' . $to_data['bal_name'] .
                                '. Обмен проведен автоматически');

                        } else {
                            $new_status = PAY_STATUS_IN_WORK;

                            // алерты
                            $this->writeAdminAlerts('changein', 'Внутренний обмен, ID:' . $pay_id .
                                ', пользователь [' . $user_id . '] ' . $user_data['user_email'] .
                                ', меняет ' . cutZeros($pay_ps_data['pay_ch_value']) . ' ' . $from_data['bal_name'] .
                                ', на ' . cutZeros($pay_data['pay_value']) . ' ' . $to_data['bal_name'] .
                                '. Ожидает подтверждение в админке');

                        }

                        $this->uni_update('payments', ['pay_id' => $pay_id], ['pay_status' => $new_status]);
                        $this->logWrite(LOG_PAY_NEW_STATUS, '#' . $pay_id . ':' . payStatusText($pay_data['pay_status']),
                            '#' . $pay_id . ':' . payStatusText($new_status), ACC_USER, $user_id);
                        $return = ['result' => PAY_ERR_OK];
                    }
                }
            }

            $this->trans_commit();
        } catch (Exception $e) {
            $this->trans_rollback();
            $return = ['result' => PAY_ERR_NA];
        }

        return $return;
    }


    // реф планы
    function getRefPlans($pg, $pp, $sort = [], $s = '', $type = '')
    {
        $wh_arr = [];

        if ($s != '') $wh_arr[] = '`rp_title` LIKE "%' . $this->mres($s) . '%"';
        if ($type != '') $wh_arr[] = '`rp_type` = ' . (int)$type;

        $wh_line = (count($wh_arr) > 0) ? ' WHERE ' . implode(' AND ', $wh_arr) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `rp_id` ASC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `ref_plans` ' . @$wh_line . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getRefPlanOne($rp_id)
    {
        return $this->uni_select_one('ref_plans', ['rp_id' => $rp_id]);
    }

    function getUserDayOut($user_id, $ub_id)
    {
        $q = 'SELECT SUM(`pay_value`) sm FROM `payments` WHERE `user_id` = ' . (int)$user_id . ' AND `ub_id` = ' . (int)$ub_id .
            ' AND `pay_type` = ' . PAY_TYPE_OUT . ' AND `pay_dt` > ' . (time() - 24 * 60 * 60); //echo $q;
        $res = $this->q($q);
        $row = mysqli_fetch_assoc($res);
        if (is_null($row['sm'])) return 0;
        else return $row['sm'];
    }

    function getOnePayment($pay_id)
    {
        return $this->uni_select_one('payments', ['pay_id' => $pay_id]);
    }

    function getBalData($bal_id)
    {
        return $this->uni_select_one('bals', ['bal_id' => $bal_id]);
    }

    function changeUserBal($user_id, $ub_id, $type, $val, $reason, $param)
    {
        $user_data = $this->uni_select_one('users', ['user_id' => $user_id]);
        if ($user_data === false) return 1;

        $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'ub_id' => $ub_id]); //var_dump($user_id,$ub_id);
        if ($ub_data === false) return 2;

        if ($type == PS_TYPE_OUT && $ub_data['ub_value'] < $val) return 3; // вывод бОльшей суммы

        // меняем баланс
        if ($type == PS_TYPE_IN) {
            $q = 'UPDATE `users_bals` SET `ub_value` = `ub_value` + ' . $val . ' WHERE `ub_id` = ' . (int)$ub_id;
        } else if ($type == PS_TYPE_OUT) {
            $q = 'UPDATE `users_bals` SET `ub_value` = `ub_value` - ' . $val . ' WHERE `ub_id` = ' . (int)$ub_id;
        }
        $this->q($q);

        // пишем стату
        $ins = [
            'bal_id' => $ub_data['bal_id'],
            'ub_id' => $ub_id,
            'user_id' => $user_id,
            'ps_type' => $type,
            'ps_value' => $val,
            'ps_reason' => $reason,
            'ps_param' => $param,
            'ps_dt' => time()
        ];
        $this->uni_insert('payments_stat', $ins);

// РЕФЕРАЛКИ ==============================================================================================================================

        // РЕФЕРАЛКА ДЛЯ ПОПОЛНЕНИЙ
        if ($reason == REASON_PAYMENT && $type == PS_TYPE_IN) // по депозитам
        {
            $this->refPayments($user_id, $ub_data['bal_id'], 'depo', $val, $param);
        } // РЕФЕРАЛКА ДЛЯ НОВЫХ ИНВЕСТИЦИЙ
        else if ($reason == REASON_INVEST_NEW && $type == PS_TYPE_OUT) {
            $this->refPayments($user_id, $ub_data['bal_id'], 'invest_depo', $val, $param);
        } // РЕФЕРАЛКА ДЛЯ ДОПОПЛНЕНИЯ ИНВЕСТИЦИЙ
        else if ($reason == REASON_INVEST_APPEND && $type == PS_TYPE_OUT) {
            $this->refPayments($user_id, $ub_data['bal_id'], 'invest_depo', $val, $param);
        }

// РЕФЕРАЛКИ ==============================================================================================================================

        return true;
    }

    function refPayments($user_id, $bal_id, $type, $val, $param)
    {
        switch ($type) {
            case 'depo':

                $field = 'deposit_plan';
                $global_prcs = $this->getDepoDefaultPrcs();
                $reason = REASON_REFPAY;

                break;
            case 'invest_depo':

                $field = 'invest_plan_depo';
                $global_prcs = $this->getPlanPrcsByUi($param, 'depo');
                $reason = REASON_INVEST_REFPAY_DEPO;

                break;
            case 'invest_proc':

                $field = 'invest_plan_proc';
                $global_prcs = $this->getPlanPrcsByUi($param, 'proc');
                $reason = REASON_INVEST_REFPAY_PROC;

                break;
        }

        // если есть спонсоры
        $sponsors = $this->getSponsors($user_id);
        if (count($sponsors) > 0) {
            foreach ($sponsors as $level => $sp_id) {
                $user_prcs = $this->getUserRefPrcs($sp_id, $field);

                if (isset($user_prcs[$level])) $one_prc = $user_prcs[$level];
                else if (isset($global_prcs[$level])) $one_prc = $global_prcs[$level];
                else continue;

                $ref_user_id = $sp_id;
                $ref_value = $val * $one_prc / 100;
                $ref_ub_id = $this->getOrCreateUB($ref_user_id, $bal_id);

                $q = 'UPDATE `users_bals` SET `ub_value` = `ub_value` + ' . $ref_value . ' WHERE `ub_id` = ' . (int)$ref_ub_id;
                $this->q($q);

                // пишем стату
                $ins = [
                    'bal_id' => $bal_id,
                    'ub_id' => $ref_ub_id,
                    'user_id' => $ref_user_id,
                    'ps_type' => PS_TYPE_IN,
                    'ps_value' => $ref_value,
                    'ps_reason' => $reason,
                    'ps_param' => $param,
                    'ps_dt' => time()
                ];
                $this->uni_insert('payments_stat', $ins);
            }
        }
    }

    // SETTINGS
    function setSet($key, $val)
    {
        $this->uni_update('settings', ['set_key' => $key], ['set_val' => $val]);
    }

    function setGet($key)
    {
        $ret = $this->uni_select_one('settings', ['set_key' => $key]);
        if ($ret == false) return false;
        else {
            if ($ret['set_type'] == 'json') return json_decode($ret['set_val'], true);
            else if ($ret['set_type'] == 'int') return (int)$ret['set_val'];
            else return $ret['set_val'];
        }
    }

    // всё в одном
    function unlockLockedUsers()
    {
        $q = 'UPDATE `users` SET `user_lock_date` = "0000-00-00", `user_lock` = 0 
				WHERE `user_lock` = 1 AND `user_lock_date` <> "0000-00-00" AND `user_lock_date` < "' . date('Y-m-d') . '"';
        $this->q($q);
    }

    // refs
    function doRefs($ref_id, $sponsor_id)
    {
        $deep = 7;
        $level = 1;

        while ($level <= $deep) {
            $sponsor = $this->uni_select_one('users', ['user_id' => $sponsor_id]);
            if ($sponsor === false) break;

            $this->uni_insert('refs', ['ref_id' => $ref_id, 'sponsor_id' => $sponsor_id, 'level' => $level]);

            $ref_data = $this->uni_select_one('refs', ['ref_id' => $sponsor['user_id'], 'level' => 1]);
            if ($ref_data === false) break;

            $sponsor_id = $ref_data['sponsor_id'];
            $level++;
        }
    }

    function getSponsors($ref_id)
    {
        $sponsors = $this->uni_select('refs', ['ref_id' => $ref_id]);

        $ret = [];
        if (count($sponsors) > 0) foreach ($sponsors as $one) {
            $ret[$one['level']] = $one['sponsor_id'];
        }

        return $ret;
    }

    function getOrCreateUB($user_id, $bal_id, $full = false)
    {
        $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'bal_id' => $bal_id]);
        if ($ub_data === false) {
            // create
            $ub_id = $this->uni_insert('users_bals', ['user_id' => $user_id, 'bal_id' => $bal_id, 'ub_num' => $this->genUBNum()]);
            $ub_data = $this->uni_select_one('users_bals', ['user_id' => $user_id, 'bal_id' => $bal_id]);
        } else {
            // exists
            $ub_id = $ub_data['ub_id'];
        }

        if ($full) return $ub_data;
        else return $ub_id;
    }

    function getUserRefPrcs($user_id, $type = 'deposit_plan')
    {
        // проверяем персональный план
        $user_data = $this->uni_select_one('users', ['user_id' => $user_id]);
        if ($user_data['user_' . $type] == NULL) {
            return [];
        } else {
            $refplan = $this->uni_select_one('ref_plans', ['rp_id' => $user_data['user_deposit_plan']]);
            return json_decode($refplan['rp_prcs'], true);
        }
    }

    function hasVoucherCode($code)
    {
        $check = $this->uni_select_one('vouchers', ['voucher_code' => $code]);
        if ($check === false) return false;
        else return true;
    }

    function activateVoucher($v_id, $val)
    {
        $q = 'UPDATE `vouchers` SET `voucher_value` = `voucher_value` - ' . $val . ', `voucher_dt_activate` = ' . time() .
            ' WHERE `voucher_id` = ' . $v_id;
        $this->q($q);
    }

    // changes
    function getAllChs($type = 'all', $not = false, $in = false, $with_ps = false)
    {
        $not_line = $not === false ? '' : ' AND b.`bal_id` <> ' . (int)$not;
        $in_line = $in === false ? '' : ' AND b.`bal_id` IN (' . implode(',', $in) . ')';

        if ($type == 'all') $status_line = ' AND (c.`ch_in_status` = 1 OR c.`ch_out_status` = 1)';
        else $status_line = ' AND c.`ch_' . $type . '_status` = 1';

        $ps_line = $with_ps ? ' AND (`ch_out_ps_in` <> 0 OR `ch_out_ps_out` <> 0)' : '';

        $q = 'SELECT b.*, c.* FROM `bals` b, `bals_changes` c WHERE b.`bal_id` = c.`bal_id` ' . $status_line . $not_line . $in_line . $ps_line; //echo $q;
        $res = $this->q($q);

        $ret = [];
        if (mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) {
            $ret[$row['bal_id']] = $row;
        }

        return $ret;
    }

    function getBalFull($bal_id)
    {
        $q = 'SELECT b.*, c.* FROM `bals` b, `bals_changes` c WHERE b.`bal_id` = c.`bal_id` AND b.`bal_id` = ' . (int)$bal_id; //echo $q;
        $res = $this->q($q);
        if (mysqli_num_rows($res) == 0) return false;
        else return mysqli_fetch_assoc($res);
    }


    // изненение остатков
    function changeChValue($bal_id, $type, $value, $acc_id = null, $admin = false, $raw = false)
    {
        $ret = ['result' => false];

        if (!in_array($type, ['plus', 'minus'])) {
            $ret['info'] = 'Неверный тип действия';
        } else if ($value == 0) {
            $ret['info'] = 'Нулевое изменение не допустимо';
        } else {
            $ch_data = $this->uni_select_one('bals_changes', ['bal_id' => $bal_id]);
            if ($ch_data === false) {
                $ret['info'] = 'Баланс не найден';
            } else {
                if ($type == 'minus') {
                    if (!$raw && $value > $ch_data['ch_value']) {
                        $ret['info'] = 'Сумма списания выше остатка';
                    } else {
                        $new_val = number_format(round($ch_data['ch_value'] - $value, 10), 10, '.', '');
                        $this->uni_update('bals_changes', ['bal_id' => $bal_id], ['ch_value' => $new_val]);

                        $ret['info'] = 'Остаток уменьшен';
                        $ret['result'] = true;
                        $ret['log_type'] = LOG_CH_CHANGE_MINUS;
                        $ret['new_val'] = cutZeros($new_val);
                    }
                } else {
                    $new_val = number_format(round($ch_data['ch_value'] + $value, 10), 10, '.', '');
                    $this->uni_update('bals_changes', ['bal_id' => $bal_id], ['ch_value' => $new_val]);

                    $ret['info'] = 'Остаток увеличен';
                    $ret['result'] = true;
                    $ret['log_type'] = LOG_CH_CHANGE_PLUS;
                    $ret['new_val'] = cutZeros($new_val);
                }
            }
        }

        if ($ret['result']) {
            $acc_type = $admin ? ACC_ADMIN : ACC_USER;
            if ($acc_id == null) $acc_type = ACC_UNAUTH;

            $this->logWrite($ret['log_type'], $bal_id, $value, $acc_type, $acc_id);
        }

        return $ret;
    }

    // alerts
    function getASS($type, $id)
    {
        $temp = $this->uni_select('alerts_settings', ['acc_type' => $type, 'acc_id' => $id]);
        $ret = [];
        if (count($temp) > 0) foreach ($temp as $one) {
            $ret[$one['as_set']] = $one['as_val'];
        }

        return $ret;
    }

    function saveASS($type, $id, $arr)
    {
        $this->uni_delete('alerts_settings', ['acc_type' => $type, 'acc_id' => $id]);
        if (count($arr)) foreach ($arr as $k => $v) {
            $this->uni_insert('alerts_settings', ['acc_type' => $type, 'acc_id' => $id, 'as_set' => $k]);
        }
    }

    function writeAdminAlerts($set, $mess, $level = AT_MODER)
    {
        // global alerts
        $this->uni_insert('alerts_global', [
            'ag_dt_write' => time(),
            'ag_mess' => $mess,
            'ag_level' => $level,
        ]);

        // personal alerts
        $admins = $this->uni_select('alerts_settings', ['acc_type' => ACC_ADMIN, 'as_set' => $set]);
        if (count($admins) == 0) return false;

        foreach ($admins as $one) {
            $this->uni_insert('alerts', [
                'acc_type' => $one['acc_type'],
                'acc_id' => $one['acc_id'],
                'a_dt_write' => time(),
                'a_mess' => $mess,
            ]);
        }

        return true;
    }

    function writeUserAlerts($user_id, $set, $mess)
    {
        $user_data = $this->uni_select_one('alerts_settings', ['acc_type' => ACC_USER, 'as_set' => $set, 'acc_id' => $user_id]);
        if ($user_data === false) return false;

        $this->uni_insert('alerts', [
            'acc_type' => ACC_USER,
            'acc_id' => $user_id,
            'a_dt_write' => time(),
            'a_mess' => $mess,
        ]);

        return true;
    }

    function getAlertsToSend()
    {
        $alerts = $this->uni_select('alerts', ['a_status' => 0]);
        return $alerts;
    }

    function getNewAlerts($from, $at)
    {
        $q = 'SELECT * FROM `alerts_global` WHERE `ag_dt_write` >= ' . $from . ' AND `ag_level` >= ' . $at;
        $res = $this->q($q);

        $ret = [];
        if (mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row['ag_mess'];
        }

        return $ret;
    }

    // платежные пароли
    function isNeedPayPass($user_id)
    {
        $q = 'SELECT `user_payhash` FROM `users` WHERE `user_id` = ' . (int)$user_id;
        $res = $this->q($q);
        if (mysqli_num_rows($res) == 0) return false;

        $row = mysqli_fetch_assoc($res);
        if ($row['user_payhash'] == '') return false;
        else return true;
    }

    function checkPayPass($user_id, $pass)
    {
        $q = 'SELECT `user_payhash`, `user_paysalt` FROM `users` WHERE `user_id` = ' . (int)$user_id;
        $res = $this->q($q);
        if (mysqli_num_rows($res) == 0) return false;

        $row = mysqli_fetch_assoc($res);
        if ($row['user_payhash'] == '') return true;

        $hash = sha1($pass . $row['user_paysalt']);
        if ($hash == $row['user_payhash']) return true;
        else return false;
    }

    // рейты
    function getAllRatesOut()
    {
        $q = 'SELECT b.*, bc.* FROM `bals_changes` bc, `bals` b ' .
            'WHERE bc.`bal_id` = b.`bal_id` AND `ch_out_status` = 1 AND (`ch_out_ps_in` <> 0 OR `ch_out_ps_out` <> 0)';
        $res = $this->q($q);

        $all = [];
        if (mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) {
            $all[$row['bal_id']] = $row;
        }

        $ret = [];

        foreach ($all as $from_id => $from_data) {
            $ids = $from_data['ch_out_list'] == '' ? false : explode(',', $from_data['ch_out_list']);

            foreach ($all as $to_id => $to_data) {
                if ($from_id == $to_id) continue;
                if ($ids !== false && !in_array($to_id, $ids)) continue;

                $ret_one = [];
                $ret_one['from'] = $from_id;
                $ret_one['to'] = $to_id;

                $ret_one['amount'] = $to_data['ch_value'] > 0 ? $to_data['ch_value'] : 0;

                if ($from_data['bal_rate'] > $to_data['bal_rate']) {
                    $ret_one['out'] = $from_data['bal_rate'] / $to_data['bal_rate'];
                    $ret_one['in'] = 1;
                } else {
                    $ret_one['out'] = 1;
                    $ret_one['in'] = $to_data['bal_rate'] / $from_data['bal_rate'];
                }

                $ret_one['min'] = $from_data['ch_out_min'];
                $ret_one['max'] = $from_data['ch_out_max'];

                $ret[] = $ret_one;
            }
        }

        return ['data' => $all, 'rates' => $ret];
    }

    // новости
    function getNews($pg, $pp, $sort = [], $search = '', $lang = '', $status = '')
    {
        $whs = [];
        if ($search != '') $whs[] = '`n_title` LIKE "%' . $this->mres($search) . '%"';

        if ($lang != '') $whs[] = '(`n_lang` = "' . $this->mres($lang) . '" OR `n_lang` = "")';

        if ($status != '') {
            if ($status == N_STATUS_DRAFT) $whs[] = '`n_status` = ' . $status;
            else if ($status == N_STATUS_PUB) $whs[] = '(`n_status` = ' . N_STATUS_PUB . ' AND `n_dt_pub` <= ' . time() . ')';
            else if ($status == N_STATUS_FUTURE) $whs[] = '(`n_status` = ' . N_STATUS_PUB . ' AND `n_dt_pub` > ' . time() . ')';
        }

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `news` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getNewsPub($lang)
    {
        $q = 'SELECT * FROM `news` WHERE (`n_lang` = "" OR `n_lang` = "' . $this->mres($lang) . '") AND `n_status` = 1 AND `n_dt_pub` <= ' . time() .
            ' ORDER BY `n_dt_pub` DESC';
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    // инвестиции
    function getInvestPlans($pg, $pp, $sort = [], $search = '')
    {
        $whs = [];
        if ($search != '') $whs[] = '`plan_name` LIKE "%' . $this->mres($search) . '%"';

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `invest_plans` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getUsersInvest($pg, $pp, $sort = [], $user_id = 0, $ui_status = '')
    {
        $whs = [];
        if ($user_id != 0) $whs[] = '`user_id` = ' . (int)$user_id;
        if ($ui_status != '') $whs[] = '`ui_status` = ' . (int)$ui_status;

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `ui_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `users_invest` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp; //echo $q;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getUsersInvestOne($ui_id)
    {
        return $this->uni_select_one('users_invest', ['ui_id' => $ui_id]);
    }

    function getInvestPlanOne($plan_id)
    {
        return $this->uni_select_one('invest_plans', ['plan_id' => $plan_id]);
    }

    function getDepoDefaultPrcs()
    {
        $rp_id = $this->setGet('depo_rp_id');
        $refplan = $this->uni_select_one('ref_plans', ['rp_id' => $rp_id]);
        if ($refplan === false) return [];

        return json_decode($refplan['rp_prcs'], true);
    }

    function getPlanPrcsByUi($ui_id, $type)
    {
        $ui_data = $this->uni_select_one('users_invest', ['ui_id' => $ui_id]);
        if ($ui_data === false) return [];

        $plan_data = $this->uni_select_one('invest_plans', ['plan_id' => $ui_data['plan_id']]);
        if ($plan_data === false) return [];

        if ($type == 'depo') {
            $rp_id = (int)$plan_data['rp_id_depo'];
        } else {
            $rp_id = (int)$plan_data['rp_id_proc'];
        }
        if ($rp_id == 0) return [];

        $refplan = $this->uni_select_one('ref_plans', ['rp_id' => $rp_id]);
        if ($refplan === false) return [];

        return json_decode($refplan['rp_prcs'], true);
    }

    function newUserInvest($user_id, $plan_id, $bal_id, $ub_id, $val, $step, $max)
    {
        $ins = [
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'bal_id' => $bal_id,
            'ub_id' => $ub_id,
            'ui_value_start' => $val,
            'ui_dt_start' => time(),
            'ui_dt_last_calc' => time(),
            'ui_status' => 0,
            'ui_value_now' => $val,
        ];

        return $this->uni_insert('users_invest', $ins);
    }

    function writeInvestLog($ui_id, $type, $val)
    {
        $ins = [
            'ui_id' => $ui_id,
            'il_type' => $type,
            'il_val' => $val,
            'il_dt' => time()
        ];

        $this->uni_insert('invest_logs', $ins);
    }

    function getWorkInvestDepos()
    {
        $uis = $this->uni_select('users_invest', ['ui_status' => INV_STATUS_ON]);
        return $uis;
    }

    // merchants
    function genMerchantId()
    {
        $len = $this->setGet('merchant_len');

        $end = false;
        while (!$end) {
            $temp = genPass(128, 128, true);
            $key = strtoupper(substr($temp, 0, $len));

            $test = $this->uni_select_one('merchants', ['m_num' => $key]);
            if ($test === false) break;
        }

        return $key;
    }

    function getMerchants($pg, $pp, $sort = [], $user_id = 0)
    {
        $whs = [];
        if ($user_id != 0) $whs[] = '`user_id` = ' . (int)$user_id;

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `m_num` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `merchants` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function merchantsSearch($s, $lim)
    {
        $q = 'SELECT * FROM `merchants` WHERE `m_num` LIKE "%' . $this->mres($s) . '%" OR `m_title` LIKE "%' . $this->mres($s) . '%" ORDER BY `m_num` LIMIT 0,' . $lim;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $one = [];
            $one['raw_id'] = $row['m_num'];
            $one['id'] = 'merchant_' . $row['m_num'];
            $one['label'] = $row['m_title'] . ' (' . $row['m_num'] . ')';
            $one['value'] = $row['m_title'] . ' (' . $row['m_num'] . ')';

            $ret[] = $one;
        }

        return $ret;
    }


    // orders
    function orderNew($user_id, $m_num, $m_orderid, $m_amount, $com, $m_desc)
    {
        $ins = [
            'user_id' => $user_id,
            'm_num' => $m_num,
            'order_id_shop' => $m_orderid,
            'order_desc' => $m_desc,

            'order_amount' => $m_amount,
            'order_com' => $com,

            'order_dt_create' => time(),
        ];

        return $this->uni_insert('orders', $ins);
    }

    function getOrders($pg, $pp, $sort = [], $user_id = 0, $m_num = '')
    {
        $whs = [];
        if ($user_id != 0) $whs[] = '`user_id` = ' . (int)$user_id;
        if ($m_num != '') $whs[] = '`m_num` = "' . $this->mres($m_num) . '"';

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `order_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `orders` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getMerchantStat($m_num, $from)
    {
        $q = 'SELECT DATE_FORMAT(FROM_UNIXTIME(`order_dt_create`), "%m-%Y") ym, `order_status`, COUNT(`order_id`) cc, SUM(`order_amount`) sum 
				FROM `orders` 
				WHERE `order_dt_create` >= ' . $from . ' GROUP BY ym , `order_status`';
        $res = $this->q($q);


        $mns = ['cc' => [], 'sum' => []];
        $table_stat = [];
        for ($i = $from; $i < time(); $i += (30 * 24 * 60 * 60)) {
            $dt = date('m-Y', $i);

            $mns['cc'][$dt] = 0;
            $mns['sum'][$dt] = 0;

            $table_stat[$dt] = [
                'total' => ['cc' => 0, 'sum' => 0],
                'done' => ['cc' => 0, 'sum' => 0],
                'cancel' => ['cc' => 0, 'sum' => 0],
                'wait' => ['cc' => 0, 'sum' => 0]
            ];
        }

        $ret = ['total' => $mns, 'done' => $mns, 'cancel' => $mns, 'wait' => $mns, 'table_stat' => $table_stat];

        if (mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) {
            if ($row['order_status'] == ORDER_STATUS_DONE) {
                $ret['done']['cc'][$row['ym']] = $row['cc'];
                $ret['done']['sum'][$row['ym']] = $row['sum'];

                $ret['table_stat'][$row['ym']]['done']['cc'] = $row['cc'];
                $ret['table_stat'][$row['ym']]['done']['sum'] = $row['sum'];
            } else if ($row['order_status'] == ORDER_STATUS_CANCEL) {
                $ret['cancel']['cc'][$row['ym']] = $row['cc'];
                $ret['cancel']['sum'][$row['ym']] = $row['sum'];

                $ret['table_stat'][$row['ym']]['cancel']['cc'] = $row['cc'];
                $ret['table_stat'][$row['ym']]['cancel']['sum'] = $row['sum'];
            } else if ($row['order_status'] == ORDER_STATUS_PAYS || $row['order_status'] == ORDER_STATUS_NEW) {
                $ret['wait']['cc'][$row['ym']] = $row['cc'];
                $ret['wait']['sum'][$row['ym']] = $row['sum'];

                $ret['table_stat'][$row['ym']]['wait']['cc'] = $row['cc'];
                $ret['table_stat'][$row['ym']]['wait']['sum'] = $row['sum'];
            }

            $ret['total']['cc'][$row['ym']] += $row['cc'];
            $ret['total']['sum'][$row['ym']] += $row['sum'];

            $ret['table_stat'][$row['ym']]['total']['cc'] += $row['cc'];
            $ret['table_stat'][$row['ym']]['total']['sum'] += $row['sum'];
        }

        return $ret;
    }

    // callbacks
    function getCallbacks($pg, $pp, $sort = [], $user_id = 0, $m_num = '')
    {
        $whs = [];
        if ($user_id != 0) $whs[] = '`user_id` = ' . (int)$user_id;
        if ($m_num != '') $whs[] = '`m_num` = "' . $this->mres($m_num) . '"';

        $wh = (count($whs) > 0) ? ' WHERE ' . implode(' AND ', $whs) : '';

        if (count($sort) > 0) $sr = 'ORDER BY `' . $this->mres($sort['field']) . '` ' . $this->mres($sort['sort']);
        else $sr = 'ORDER BY `order_id` DESC';

        $q = 'SELECT SQL_CALC_FOUND_ROWS * FROM `callback_list` ' . @$wh . ' ' . @$sr . ' LIMIT ' . (($pg - 1) * $pp) . ',' . $pp;
        $res = $this->q($q);

        $ret = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $ret[] = $row;
        }

        return $ret;
    }

    function getCallbacksToSend()
    {
        $clbs = $this->uni_select('callback_list', ['cbl_status' => ['eq' => 'IN', 'val' => '(' . ORDER_CALLBACK_NONE . ',' . ORDER_CALLBACK_ERROR . ')']]);
        return $clbs;
    }

    function logCallback($cbl_id, $result, $answer, $try)
    {
        $max_try = $this->setGet('merchant_try');

        $time = time();

        $data = [
            'cbl_id' => $cbl_id,
            'send_dt' => $time,
            'send_result' => $result,
            'answer' => $answer,
        ];
        $this->uni_insert('callback_logs', $data);

        $upd = [
            'cbl_dt_last_send' => $time,
            'cbl_status' => $result,
            'cbl_dt_end' => ($result == ORDER_CALLBACK_DONE ? $time : 0),
        ];

        if ($result == ORDER_CALLBACK_ERROR) {
            $try++;
            $upd['cbl_try'] = $try;

            if ($try >= $max_try) {
                $upd['cbl_status'] = ORDER_CALLBACK_TRY_END;
            }
        }

        $this->uni_update('callback_list', ['cbl_id' => $cbl_id], $upd);
    }

    function writeCallback($user_id, $m_num, $order_id, $status)
    {
        $ins = [
            'user_id' => $user_id,
            'm_num' => $m_num,
            'order_id' => $order_id,
            'cbl_send_status' => $status,
            'cbl_dt_create' => time()
        ];

        $this->uni_insert('callback_list', $ins);
    }

}