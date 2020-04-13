<?php

namespace RomanAM;
/**
 * Класс для поиска по группе закупки
 *
 * Class GetProduct
 * @package RomanAM
 *
 * @author Merinov Roman <merinovroman@gmail.com>
 */
class GetProduct
{
    var $dbConn;
    var $debug;
    var $dbHost;
    var $dbLogin;
    var $dbPassword;
    var $dbName;
    var $_DIR;
    var $_doc_root;
    var $_path;
    var $_s404;

    function __construct($_debag)
    {
        $this->dbHost = "database-redsoft";
        $this->dbLogin = "root";
        $this->dbPassword = "docker";
        $this->dbName = "db_redsoft";
        $this->debag = $_debag;
        $this->_DIR = __DIR__ . "/..";
        $this->_doc_root = $_SERVER["DOCUMENT_ROOT"];
        $this->_path = str_replace($this->_doc_root, "", $this->_DIR);
        $this->_s404 = false;

        $this->connect();
        if ($this->dbConn) {
//            $this->Worker();
//            $this->disconnect();
        }
    }

    private function connect()
    {

        $this->dbConn = new \mysqli($this->dbHost, $this->dbLogin, $this->dbPassword, $this->dbName);

        if (!$this->dbConn) {
            $error = "[" . mysqli_connect_errno() . "] " . mysqli_connect_error();
            if ($this->debug)
                echo "<br><font color=#ff0000>Error! mysqli_connect()</font><br>" . $error . "<br>";
            return false;
        }
        $this->query("SET NAMES 'utf8'");
        $this->query('SET collation_connection = "utf8_unicode_ci"');

        return true;
    }

    private function disconnect()
    {
        mysqli_close($this->dbConn);
    }

    protected function query($strSql)
    {
        return $this->dbConn->query($strSql);
    }

    protected function GetError()
    {
        return "[" . mysqli_errno($this->dbConn) . "] " . mysqli_error($this->dbConn);
    }

    public function lastID()
    {
        return mysqli_insert_id($this->dbConn);
    }

    public function showJson($arrData)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(array("status" => "ok", "product" => $arrData), JSON_UNESCAPED_UNICODE);
    }

    public function jsonError($arrData)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(array("status" => "error", "error" => $arrData), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getElementByID(int $id)
    {
        if (!empty($id)) {
            $resDb = $this->query("SELECT * FROM `element` WHERE `ID` = " . $id);

            if ($item = $resDb->fetch_assoc()) {
                $this->showJson($item);
                return;
            }
            $this->jsonError(array("name" => "element not found", "s" => 100));
        } else {
            $this->jsonError(array("name" => "element not found", "s" => 100));
        }
        return;
    }

    /**
     * @param string $search
     * @return string|null
     */
    public function getElementBySubName(string $search)
    {
        if (!empty($search)) {
            $search = htmlspecialchars($search);
            $_items = array();
            $_res = $this->query("SELECT * FROM element WHERE NAME LIKE '%" . $search . "%'");
            while ($_item = $_res->fetch_assoc()) {
                $_items[] = $_item;
            }
            if (sizeof($_items)) {
                $this->showJson($_items);
            } else {

            }
        } else {
            $this->jsonError(array("name" => "element not found", "s" => 100));
        }
        return;
    }

    /**
     * @param array|sting $search
     * @return string|null
     */
    public function getElementByManufactureList($search)
    {
        if (is_array($search) && count($search) > 0) {
            $arrayMap = array_map(function ($item) {
                return "'" . $item . "'";
            }, $search);
            $strSql = implode(', ', $arrayMap);
        } else {
            $strSql = "'" . $search . "'";
        }
        $items = array();
        $resultQuery = $this->query("
                SELECT E.* 
                FROM manufacture as m 
                LEFT JOIN element as E ON m.ID=E.MANUFACTURE 
                WHERE m.NAME IN (" . $strSql . ")"
        );

        while ($_item = $resultQuery->fetch_assoc()) {
            $items[] = $_item;
        }

        if (sizeof($items)) {
            $this->showJson($items);
        } else {
            $this->jsonError(array("name" => "element not found", "s" => 100));
        }
        return;
    }

    /**
     * @param int $sectionId
     * @return string|null
     */
    public function getElementBySection(int $sectionId)
    {
        $items = array();
        if (intval($sectionId)) {
            $resultQuery = $this->query("
                        SELECT e.* FROM elem_sect AS es 
                        LEFT JOIN element as e ON e.ID = es.ELEMENT 
                        WHERE es.SECTION = " . $sectionId
            );
        }
        if ($resultQuery) {
            while ($_item = $resultQuery->fetch_assoc()) {
                $items[] = $_item;
            }
        }
        if (sizeof($items)) {
            $this->showJson($items);
        } else {
            $this->jsonError(array("name" => "element not found", "s" => 100));
        }
        return;
    }

    public function getElementSectionTree(int $sectionId)
    {
        $items = $sections = [];
        if (intval($sectionId)) {
            $resultQuerySections = $this->query("
                    WITH RECURSIVE category_path (ID, NAME, PARENT_SECTION) AS
                    (
                      SELECT ID, NAME, PARENT_SECTION
                        FROM section
                        WHERE ID = $sectionId
                      UNION ALL
                      SELECT c.ID, c.`NAME`, c.PARENT_SECTION
                        FROM category_path AS cp 
                        JOIN section AS c ON cp.ID = c.PARENT_SECTION
                    )
                    SELECT ID FROM category_path
            ");
            if ($resultQuerySections) {
                while ($section = $resultQuerySections->fetch_assoc()) {
                    $sections[] = $section['ID'];
                }
            }
            if (is_array($sections) && count($sections) > 0) {
                $resultQuery = $this->query("
                        SELECT e.* FROM elem_sect AS es 
                        LEFT JOIN element as e ON e.ID = es.ELEMENT 
                        WHERE es.SECTION IN (" . implode(',', $sections) . ")"
                );
            }
        }
        if ($resultQuery) {
            while ($item = $resultQuery->fetch_assoc()) {
                $items[] = $item;
            }
        }

        if (sizeof($items)) {
            $this->showJson($items);
        } else {
            $this->jsonError(array("name" => "element not found", "s" => 100));
        }
        return;
    }

    public function debug($str)
    {
        echo '<pre>';
        print_r($str);
        echo '</pre>';
    }
}