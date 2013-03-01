<?php
/*
 * PasteBin.php : pastebin.com api
 */

namespace Oclane;

use Doctrine\DBAL\DBALException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


class PasteBin
{
    const URL='http://pastebin.com/api/api_post.php';
    const HEAD="PHP Live! Snippet (electrolinux.github.com/PhpLive/)\r\n";

    protected $db;

    protected $_error;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function userApiKey($username)
    {
        $stmt = $this->db->executeQuery('SELECT * FROM users WHERE username = ?', array(strtolower($username)));
        if (!$user = $stmt->fetch()) {
            //throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            return false;
        }

        return $user['pastebin_api_key'];
    }

    protected function prepareCode($interp,$code)
    {
        if($interp == 'php') {
            $s = "<?php\r\n// " . PasteBin::HEAD . $code;
        } elseif ($interp == 'javascript') {
            $s = "// " . PasteBin::HEAD . $code;
        } elseif ($interp == 'sql') {
            $s = '-- ' . PasteBin::HEAD . $code;
        } else {
            $s='';
        }
        return urlencode($s);
    }

    public function postCode($key,$interp,$code,$title='')
    {
        $api_dev_key = $key;
        $api_paste_code = $this->prepareCode($interp,$code);
        $api_paste_private = '0'; // 0=public 1=unlisted 2=private
        if (empty($title)) {
            $title='New Pastebin test';
        }
        $api_paste_name = $title;
        $api_paste_expire_date = '10M';
        $api_paste_format = $interp;
        $api_user_key = $key; // if an invalid api_user_key or no key is used, the paste will be create as a guest
        $api_paste_name = urlencode($api_paste_name);
        $url = PasteBin::URL;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        $response = curl_exec($ch);
        return $response;
    }
}

