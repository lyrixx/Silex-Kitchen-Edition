<?php
/*
 * Interpreter.php : les interpréteurs de code ^_^
 *
 */

namespace Oclane;

use Doctrine\DBAL\DBALException;

class Interpreter
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function evalPhp($code)
    {
        ob_start();
        eval($code);
        $resultat = ob_get_contents();
        ob_end_clean();
        return ($resultat);
    }

    public function evalJs($code)
    {
        // version minimale
        return '<script type="text/javascript">' . $code . '</script>';
    }

    public function evalSql($code)
    {
        $db = $this->app['db'];

        $decl = preg_split('/;/',$code);
        $resultat = '';
        foreach($decl as $sql)
        {
            $sql=trim($sql);
            if(empty($sql)) continue;

            $resultat .= '<code>' . $sql . '</code><br />' . "\n";
            if ($this->isExec($sql))
            {
                try
                {
                    $res = $db->executeQuery($sql);
                    if($res === false)
                    {
                        $errinfo = $db->errorInfo();
                        $resultat .= '<b>Ooops (1)...: ['.$errinfo[0].'] ('.
                            $errinfo[1].') '.$errinfo[2].'</b>';
                        return $resultat;
                    }
                    else
                    {
                        $resultat .= '<p class="ok">' . $res->fetch() . '</p>';
                    }
                }
                catch(DBALException $e)
                {
                    $resultat .= '<p class="error">Ooops...(2) : ' . $e->getMessage() . '</p>';
                    return $resultat;
                }
            }
            else
            {
                try
                {
                    $res = $db->fetchAll($sql);
                    if($res === false)
                    {
                        $errinfo = $db->errorInfo();
                        $resultat .= '<b>Oops...(3) : ['.$errinfo[0].'] ('.
                            $errinfo[1].') '.$errinfo[2].'</b>';

                        return $resultat;
                    }
                    $resultat .= $this->asTable($res);
                }
                catch(DBALException $e)
                {
                    $resultat .= '<p class="error">Ooops...(4) : ' . $e->getMessage() . '</p>';
                    return $resultat;
                }
            }
        }
        return $resultat;
    }

    /*function __word_iregex($arg)
    {
        return '/\b' . $arg . '\b/i';
    }*/

    protected function isExec($sql)
    {
        //$_exec = array_map('__word_iregex',array('drop','create','insert','update','delete','alter'));
        $words = array('drop','create','insert','update','delete','alter');

        foreach($words as $word)
        {
            if(preg_match('/\b'.$word.'\b/i',$sql))
                return true;
        }
        /*if(preg_match('/\bselect\b/i',$sql) and !preg_match('/\bfrom\b/i',$sql))
            return true;*/
        return false;
    }

    protected function asTable($result)
    {
        $head = '';
        $body = '';
        $classes = array('paire','impaire');
        $x = 0;
        foreach($result as $row)
        {
            if(empty($head))
            {
                $head = '<tr><th>' .
                    implode('</th><th>',array_keys($row)) . '</th></tr>' . "\n";
            }
            $css=$classes[++$x % 2];
            $body .= '<tr class="'.$css.'"><td>' .
                implode('</td><td>',array_values($row)) . '</td></tr>' . "\n";
        }
        return '<table cellpadding="1" cellspacing="1" border="0">' . "\n" .
            '<thead>' . $head . '</thead>' . "\n" .
            '<tbody>' . $body . '</tbody>' . "\n" . '</table>' . "\n";
    }
}