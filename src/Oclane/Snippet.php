<?php
/*
 * snippet.php : manage code snippet in db
 */

namespace Oclane;

use Doctrine\DBAL\DBALException;

class Snippet
{
	protected $db;
    protected $interp;

	protected $_error;
	protected $_interps = array('php','sql','js');

	function __construct($db,$interp='php')
	{
        $this->db = $db;
		$this->interp = in_array($interp,$this->_interps) ? $interp : 'php';
	}

	function getAll()
	{
		$db = $this->db;
		$qinterp = $db->quote($this->interp);
		try
		{
			$res = $db->fetchAll("SELECT id, name, code, rows, comment
				FROM snippet WHERE interp=$qinterp ORDER BY name");
			if($res)
			{
				return $res;
			}
			return array();

		}
		catch(PDOException $e)
		{
			$this->_error = $e->getMessage();
			return array();
		}
	}

	function add($name,$code,$comment='')
	{
		$db = $this->db;
		$qname = $db->quote($name);
		$qinterp = $db->quote($this->interp);
		//$qcode = $pdo->quote(preg_replace("/(\n|\r)+/","\r",$code));
		$qcode = $db->quote($code);
		$qcomment = $db->quote($comment);
		$lignes = preg_split("/(\n|\r)+/",$code);
		$rows=count($lignes);
		if(0) {
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
		try
		{
			$res = $db->executeQuery("INSERT INTO snippet
				(name,interp,code,rows,comment)
				VALUES ($qname,$qinterp,$qcode,$rows,$qcomment)");
			if($res) {
				return True;
            }
			elseif($res === false) {
				return $this->modif($name,$interp,$code,$comment);
			}
		}
		catch(DBALException $e)
		{
			//$this->_error = $e->getMessage();
			//return FALSE;
			return $this->modif($name,$code,$comment);
		}
	}

	function modif($name,$code,$comment='')
	{
		$db = $this->db;
		try
		{
			$qname = $db->quote($name);
			$qinterp = $db->quote($this->interp);
			//$qcode = $pdo->quote(preg_replace("/(\n|\r)+/","\r",$code));
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
			else
			{
				$errinfo = $db->errorInfo();
				$this->_error = '['.$errinfo[0].'] ('.$errinfo[1].') '.$errinfo[2].'.';
				return FALSE;
			}
		}
		catch(DBALException $e)
		{
			$this->_error = $e->getMessage();
			return FALSE;
		}
	}

	function del($name,$interp)
	{
		$db = $this->db;
		try
		{
			$qname = $db->quote($name);
			$qinterp = $db->quote($interp);
			$res = $db->executeQuery("DELETE FROM snippet WHERE name=$qname AND interp=$qinterp");
			if($res)
				return True;
			else
			{
				$errinfo = $db->errorInfo();
				$this->_error = '['.$errinfo[0].'] ('.$errinfo[1].') '.$errinfo[2].'.';
				return FALSE;
			}
		}
		catch(DBALException $e)
		{
			$this->_error = $e->getMessage();
			return FALSE;
		}
	}

	function getError()
	{
		return $this->_error;
	}
}
