<?
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// этот файл входит в BUNDLE
// после редактировани¤ файла, необходимо пересобрать проект (build.php)
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

class DB
{
/*  Usage of place-holders syntax in all "$sql"-based functions:
		
		DB::query("select * from news where id=?i and title=?", 
								"10", "Hello world");
		
		//	?i for numeric, ?f for float, ? for strings, ?l for like %str%
	*/
	
	
	static $affected_rows;
	static $insert_id;
	
	static function connect($host, $user, $password, $database, $collation='UTF8')
	{
		$res = mysql_connect($host, $user, $password);
        if(!$res) 
        {
            usleep(30000);
            $res = mysql_connect($host, $user, $password);
        }
		if($res) $res = mysql_select_db($database);
		if(!$res) 
		{
			self::logConnectionError(mysql_error());
			throw new ExceptionNotAvailable();
		}
		mysql_query("SET NAMES ".$collation);
	}
	
	
	static function query($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		$st = microtime(true);
		$result = mysql_query($sql);
		if(mysql_error()) DB::error($sql);
		self::$affected_rows = mysql_affected_rows();
		self::$insert_id = mysql_insert_id();
		
        
        // tmp
        // логирование всех типов запросов insert или update сделанных гост¤ми сайта
        /*
        if(!hasCurrentUser())
        {
            $sql_lower = strtolower($sql);
            if( preg_match("/^(insert|update)/",$sql_lower) )
            {
                $op_type = "insert";
                if( preg_match("/^update/",$sql_lower) )
                {
                    $op_type = "update";
                    $sql_lower = str_replace("update ","",$sql_lower);
                }
                else
                {
                    $sql_lower = str_replace("insert into ","",$sql_lower);
                }
                $op_table = substr( $sql_lower, 0, strpos($sql_lower," ") );
                
                $op_url = trim($GLOBALS['controllerClass'].".".$GLOBALS["event"]);
                
                if($op_url!='.' && $GLOBALS['controllerClass']!='Controller_Elapi') // cron и ELAPI не учитываем
                {
                    $rowid = DB::scalarSelect("select id from tmp_guest_updates where url=? and optype=? and optable=?", $op_url, $op_type, $op_table);
                    if($rowid)
                    {
                        mysql_query( "update tmp_guest_updates set cnt=cnt+1 where id=".$rowid );
                    }
                    else
                    {
                        mysql_query( DB::prepare("insert into tmp_guest_updates (url, optype, optable) values (?,?,?)", $op_url, $op_type, $op_table) );
                    }
                }
            }
        }
        */
        
        /*
		if(microtime(true) - $st > 2)
		{
			// замен¤ем переменные на шаблоны (цифровые параметры, значени¤ в скобках)
			// " 2","=1", "(всЄ внутри скобок)"
			$_q = $sql;
			$_q = preg_replace('/\([^)]+\)/', '(...)', $_q);
			$_q = preg_replace('/=\s{0,}\d+/', '=N', $_q);
			$_q = preg_replace('/>\s{0,}\d+/', '>N', $_q);
			$_q = preg_replace('/<\s{0,}\d+/', '<N', $_q);
			$_q = preg_replace('/\+\s{0,}\d+/', '+N', $_q);
			$_q = preg_replace('/\s+\d+/', ' N', $_q);
            $_q = preg_replace("/like '.*'/", "like '...'", $_q);
            $_q = preg_replace("/'\d+'/", "'N'", $_q);
			$_q = preg_replace('/(document\.[a-z0-9_]+,\s*){3,}/', 'document.* ', $_q);
			$_q = preg_replace('/(post\.[a-z0-9_]+,\s*){3,}/', 'post.* ', $_q);
			$_q = preg_replace('/(photo\.[a-z0-9_]+,\s*){3,}/', 'photo.* ', $_q);
			$_q = preg_replace('/(album\.[a-z0-9_]+,\s*){3,}/', 'album.* ', $_q);
			$_q = preg_replace('/(community\.[a-z0-9_]+,\s*){3,}/', 'community.* ', $_q);
			$_q = preg_replace('/(qa\.[a-z0-9_]+,\s*){3,}/', 'qa.* ', $_q);
			
			$_uri = Request::escape( $_SERVER['REQUEST_URI'] );
			$_long_query = DB::prepare("insert into long_query52 (q,q_src,uri,st,dt,ip,ua) values (?,?,?,?i,?i,?,?)", $_q, $sql, $_uri, microtime(true)-$st, time(), $_SERVER['REMOTE_ADDR'], htmlspecialchars($_SERVER['HTTP_USER_AGENT']) );
			mysql_query($_long_query); // служедбный запрос, чтобы не сбил нам insert_id и affected_rows
		}
        */
        
		if(microtime(true)-$st>0.1 && $_SERVER['REMOTE_ADDR']=='82.209.222.57') $GLOBALS['queries'][] = Array("time"=>(microtime(true)-$st), "sql"=>$sql);
		
		return $result;
	}
	
	
	static function getAffectedRows()
	{
		return self::$affected_rows;
	}
	
	
	static function getInsertId()
	{
		return self::$insert_id;
	}
	
	
	static function delete($table, $condition)
	{
		DB::query("DELETE FROM $table WHERE $condition");
	}
	
	
	static function clearTableCache($table)
	{
		// deprecated
	}
	
	
	static function singleRow($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$res = DB::query($sql);
		if($row = mysql_fetch_assoc($res))
		{
			return $row;
		}
		return false;
	}
	
	
	static function scalarSelect($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$res = DB::query($sql);
		if($row = mysql_fetch_row($res))
		{
			return $row[0];
		}
		return false;
	}
	
	
	static function getRows($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$ret=Array();
		$res = DB::query($sql);
		while($row = mysql_fetch_assoc($res))
		{
			$ret[] = $row;
		}
		mysql_free_result($res);
		return $ret;
	}
	
	
	static function hashedSelect($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$ret=Array();
		$res = DB::query($sql);
		while($row = mysql_fetch_row($res))
		{
			$ret[$row[0]] = $row[1];
		}
		mysql_free_result($res);
		return $ret;
	}
	
	
	static function hashedRows($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$ret=Array();
		$res = DB::query($sql);
		while($row = mysql_fetch_assoc($res))
		{
			$first = current($row);
			$ret[$first] = $row;
		}
		mysql_free_result($res);
		return $ret;
	}

  static function getArray($sql)
	{
		if(func_num_args()>1)	$sql = self::prepare($args = func_get_args());
		
		$ret=Array();
		$res = DB::query($sql);
		while($row = mysql_fetch_array($res))
		{
			$ret[] = $row[0];
		}
		mysql_free_result($res);
		return $ret;
	}
	
	
	static function insert($table, $values=Array())
	{
		$fields=array_keys($values);
		$fields_str = implode(',', $fields);
		$values_str ="'".implode("', '", self::escape($values) )."'";
		
		$sql = "insert into $table ($fields_str) values ($values_str)";
		DB::query($sql);

		return self::getInsertId();
	}
	
	
	function update($table, $id, $values=Array())
	{
		$id = intval($id);
		unset($values['id']);
		
		$fields=array_keys($values);
		$fields_str ='';
		foreach($fields as $field)
		{
				if($fields_str) $fields_str.=',';
				$fields_str.=$field."='".self::escape($values[$field])."'";
		}
		
		$sql = "update $table set $fields_str where id={$id}";
		DB::query($sql);
		
		return self::getAffectedRows();
	}
	
	
	
	static function escape($value)
	{
		if(is_array($value))
		{
			return array_map("mysql_real_escape_string", $value);
		}
		else
		{
			return mysql_real_escape_string($value);
		}
	}
	
	
	// usage: prepare($sql, $args)
	//    or: prepare($args)
	static function prepare()
	{
		if(func_num_args()==1) $args = func_get_arg(0); // это надо если параметры передаем массивом
		else $args = func_get_args();
		
		$sql = array_shift($args);
		$sql.=' ';
		
		$i=0;
		$shift=0;
		$pos = strpos($sql, '?', $shift);
		while(is_int($pos))
		{
			$pos2 = $pos+1;
			
			$key = '';
			$next_char = substr($sql, $pos+1, 1);
			if($next_char==='i' || $next_char==='l' || $next_char==='f') 
			{
				$key = $next_char;
				$pos2++;
			}
			
			if($key=='i')
			{
				$subst = intval($args[$i]);
			}
			elseif($key=='f')
			{
				$subst = floatval($args[$i]);
			}
			elseif($key=='l')
			{
				$subst = "'%".mysql_real_escape_string(str_replace('%','',$args[$i]))."%'";
			}
			else
			{
				$subst = "'".mysql_real_escape_string($args[$i])."'";
			}
			
			//  выполн¤ем подстановку
			$sql = substr($sql,0,$pos) . $subst . substr($sql,$pos2);
			
			// ищем следующий placeholder
			$i++;
			$shift = $pos + strlen($subst) + 1;
			$pos = strpos($sql, '?', $shift);
		}
		
		return $sql;
	}
	
	
	static function logConnectionError($error='')
	{
		date_default_timezone_set("Europe/Moscow");
		
		$str = date("H:i:s")." ".$error."\n";
		
		$fp = fopen('tmp/mysql.'.date("d.m.Y").'.log','a');
		fwrite($fp,$str);
		fclose($fp);
	}
	
	
	static function error($sql)
	{
		$info = mysql_error();
		
		if( is_int(strpos($info,'Lost connection')) || is_int(strpos($info,'of memory')) || is_int(strpos($info,'has gone')) )
		{
			self::logConnectionError();
			throw new ExceptionNotAvailable();
		}
		
		$str = "Ошибка при выполнении запроса: ".$sql."\r\n<br>";
		$str .= "Описание ошибки: ".$info."\r\n";
		$str .= "Адрес по которому произошла ошибка: ".$_SERVER['REQUEST_URI']."\r\n";
		$str .= "\r\n";
		
		date_default_timezone_set("Europe/Moscow");
		$fp = fopen('tmp/error.'.date("d.m.Y").'.log','a');
		fwrite($fp,$str);
		fclose($fp);
		
		//debug_print_backtrace();			exit;
		
		throw new ExceptionDB($sql, $info);
	}
}


class ExceptionDB extends Exception
{
	public $sql;
	public $info;
	
	public function __construct($sql, $info)
	{
		$this->sql = $sql;
		$this->info = $info;
		parent::__construct( "ќшибка при выполнении запроса." );
  }
}


class BulkInsert
{
	public $pre;
	public $str;
	public $maxlen = 30000; // DB max_allowed_packet
	
	public function __construct($pre)
	{
		$this->pre = $pre;
		$this->str = '';
  }
	
	function add($sql)
	{
		if(func_num_args()>1)	$sql = DB::prepare($args = func_get_args());
		if($this->str!='') $this->str = $this->str.',';
		$this->str .= '('.$sql.')';
		if(strlen($this->str)>$this->maxlen) $this->commit();
	}
	
	function commit()
	{
		if($this->str!='')
		{
			DB::query($this->pre.' values '.$this->str);
			$this->str = '';
		}
	}
}