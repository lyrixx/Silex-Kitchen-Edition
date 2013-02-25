<?php
/*
 * snippet.php : manage code snippet in db
 */

namespace Oclane;

use Doctrine\DBAL\DBALException;

class Snippet
{
    protected $db;
    protected $interp = 'php';
    protected $qinterp;
    protected $name = 'PHP';

    protected $_error;

    public function __construct($db)
    {
        $this->db = $db;
        $this->qinterp = $db->quote($this->interp);
    }

    public function getAll()
    {
        $db = $this->db;
        $qinterp = $this->qinterp;
        try {
            $res = $db->fetchAll("SELECT id, name, code, rows, comment
                FROM snippet WHERE interp=$qinterp ORDER BY name");
            if ($res) {
                return $res;
            }

            return array();

        } catch (DBALException $e) {
            $this->_error = $e->getMessage();

            return array();
        }
    }

    public function add($name,$code,$comment='')
    {
        $db = $this->db;
        $qname = $db->quote($name);
        $qinterp = $this->qinterp;
        $qcode = $db->quote($code);
        $qcomment = $db->quote($comment);
        $lignes = preg_split("/(\n|\r)+/",$code);
        $rows=count($lignes);
        if (false) {
            $safe_lignes = array_map('addslashes',$rows);
            $js = "xxx='".implode('\n',$safe_lignes)."';\n";
            print("<pre>code=[ $code ]\n----\nqcode=[ $qcode ]\n----\njs=[ ".$js." ]\n");
            $q2 = $db->quote(preg_replace("/(\n|\r)+/","\r",$code));
            $lignes = preg_split("/(\n|\r)+/",$q2);
            $safe_lignes = array_map('addslashes',$lignes);
            $js = "xxx='".implode('\n',$safe_lignes)."';\n";
            print("q2=[ $q2 ]\n----\njs2=[ ".$js." ]\n");
            die('</pre>');
        }
        try {
            $res = $db->executeQuery("INSERT INTO snippet
                (name,interp,code,rows,comment)
                VALUES ($qname,$qinterp,$qcode,$rows,$qcomment)");
            if ($res) {
                return True;
            } elseif ($res === false) {
                return $this->modif($name,$interp,$code,$comment);
            }
        } catch (DBALException $e) {
            //$this->_error = $e->getMessage();
            //return FALSE;
            return $this->modif($name,$code,$comment);
        }
    }

    public function modif($name,$code,$comment='')
    {
        $db = $this->db;
        try {
            $qname = $db->quote($name);
            $qinterp = $this->qinterp;
            $qcode = $db->quote($code);
            $qcomment = $db->quote($comment);
            $_rows = preg_split("/(\n|\r)+/",$code);
            $rows=count($_rows);
            $res = $db->executeUpdate("UPDATE snippet
                SET code = $qcode, rows = $rows,
                comment = $qcomment
                WHERE name = $qname AND interp = $qinterp");
            if($res)

                return True;
            else {
                $errinfo = $db->errorInfo();
                $this->_error = '['.$errinfo[0].'] ('.$errinfo[1].') '.$errinfo[2].'.';

                return FALSE;
            }
        } catch (DBALException $e) {
            $this->_error = $e->getMessage();

            return FALSE;
        }
    }

    public function del($name)
    {
        $db = $this->db;
        try {
            $qname = $db->quote($name);
            $qinterp = $this->qinterp;
            $res = $db->executeQuery("DELETE FROM snippet WHERE name=$qname AND interp=$qinterp");
            if($res)

                return True;
            else {
                $errinfo = $db->errorInfo();
                $this->_error = '['.$errinfo[0].'] ('.$errinfo[1].') '.$errinfo[2].'.';

                return FALSE;
            }
        } catch (DBALException $e) {
            $this->_error = $e->getMessage();

            return FALSE;
        }
    }

    protected function getError()
    {
        return $this->_error;
    }

    public function getOptionsList()
    {
        $snippets=array();
        $options=array(''=>$this->name . ' Snippet...');
        foreach ($this->getAll() as $row) {
            $_val = $row['name'];
            $_text = $row['code'];
            $rows = preg_split("/(\n|\r)+/",$_text);
            $safe_rows = array_map('addslashes',$rows);
            $_name = $safe_rows[0];
            if (strlen($_name) > 20) {
                $parts = explode("\n",wordwrap($_name, 20, "\n", 1));
                $_name = $parts[0];
                if (false) {
                    echo '<!-- $parts: ' ."\n";
                    var_dump($parts);
                    echo '$_name : ' . "$_name -->\n";
                }
            }
            $options[$_val] = "$_val : $_name";
            $snippets[$_val]=implode('\n',$safe_rows);
        }

        return array($options,$snippets);
    }
}
