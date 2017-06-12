<?php

namespace Controller;

class ArticleList extends Controllers {

    public function __construct() {
        parent::__construct();
        $this->SetAjaxFunction("Filter", array("*"));
    }

    public function Filter() {
        $default = $this->PrepareAjaxParametrs();
        $limit = $this->PrepareAjaxParametrs($_GET["params1"]);
        $sort = $this->PrepareAjaxParametrs($_GET["params2"]);

        $articleList = new \Components\ArticleList();
        $articleList->ShowPager = $limit["showPager"];
        $articleList->LoadItemsCount = $limit["pagerLoadItems"];
        $articleList->NextLoadItemsCount = $limit["pagerNextLoadItems"];
        $articleList->DivId = $default["divId"];
        $articleList->LoadUrl = $default["seoUrl"];

        $articleList->ShowSort = $sort["showSort"];
        $articleList->SortDomain = $sort["sortDomain"];
        $articleList->ShowSortByName = $sort["showSortByName"];
        $articleList->WordSortByName = $sort["wordSortByName"];
        $articleList->SortASC = $sort["sortASC"];
        $articleList->SortDESC = $sort["sortDESC"];
        $articleList->SortQuery = $sort["sortQuery"];
        return $articleList->LoadComponent();

        $params = empty($_GET["params"]) ? array() : $_GET["params"];
        $params1 = empty($_GET["params1"]) ? array() : $_GET["params1"];
        $params2 = empty($_GET["params2"]) ? array() : $_GET["params2"];
        if (empty($params) && empty($params1) && empty($params2))
            return;
        $page = new PageByAjax();
        $url = "";
        $where = "";
        $columns = "";
        $columnList = array();
        $lastAction = "";
        $groupArray = array();
        $groupNames = array();
        $sort = "";
        $limitMode = "none";

        if (!empty($params)) {
            for ($y = 0; $y < count($params); $y++) {
                if (!empty($params[$y][2])) {
                    $url = $params[$y][2];
                    break;
                }
            }
            for ($i = 0; $i < count($params); $i++) {
                if (empty($params[$i][1]))
                    continue;
                $columnName = $params[$i][1];
                $action = $params[$i][0];
                $value1 = $params[$i][3];
                $value2 = empty($params[$i][4]) ? 0 : $params[$i][4];
                $groupName = empty($params[$i][5]) ? "" : $params[$i][5];

                if (empty($groupName)) {
                    if (!in_array($columnName, $columnList))
                        $columns .= ", GROUP_CONCAT(if(ItemName = '$columnName', value, NULL)) AS '$columnName'";
                    $columnList[] = $columnName;
                    if ($action == "BETWEEN") {
                        if (!empty($where))
                            $where .= " AND ";
                        $where .= "(ContentTable.$columnName  BETWEEN $value1 AND $value2)";
                    }
                    else if ($action == "TEXT") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  = '$value1')";
                        }
                    }
                    else if ($action == "StartLike") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  LIKE  '$value1%')";
                        }
                    }
                    else if ($action == "EndLike") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  LIKE  '%$value1')";
                        }
                    }
                    else if ($action == "Like") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  LIKE  '%$value1%')";
                        }
                    }
                    else if ($action == "<") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  < $value1)";
                        }
                    }
                    else if ($action == "<=") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  <= $value1)";
                        }
                    }
                    else if ($action == ">") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName >$value1)";
                        }
                    }
                    else if ($action == ">=") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  >= $value1)";
                        }
                    }
                    else if ($action == "Select1N") {
                        if (!empty($value1)) {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  = $value1)";
                        }
                    }
                    else if ($action == "SelectMN") {

                        if ($value1 == "true") {
                            if (!empty($where))
                                $where .= " AND ";
                            $where .= "(ContentTable.$columnName  = $value2)";
                        }
                    }
                }
                else {
                    $pos = empty($groupArray[$groupName]) ? 0 : count($groupArray[$groupName]);
                    $groupArray[$groupName][$pos] = $params[$i];
                    if (!in_array($groupName, $groupNames))
                        $groupNames[] = $groupName;
                }
                $lastAction = $action;
            }
            if (!empty($groupArray)) {
                for ($n = 0; $n < count($groupNames); $n++) {
                    $name = $groupNames[$n];
                    $ar = $groupArray[$name];
                    $tmpWhere = "";
                    for ($a = 0; $a < count($ar); $a++) {
                        $columnName = $ar[$a][1];
                        if (!in_array($columnName, $columnList))
                            $columns .= ", GROUP_CONCAT(if(ItemName = '$columnName', value, NULL)) AS '$columnName'";
                        $columnList[] = $columnName;
                        $action = $ar[$a][0];
                        $value1 = $ar[$a][3];
                        $value2 = empty($ar[$a][4]) ? 0 : $ar[$a][4];
                        if ($value1 == "true") {
                            if ($tmpWhere != "")
                                $tmpWhere .= " OR ";
                            $tmpWhere .= "ContentTable.$columnName  = '$value2'";
                        }
                    }
                    if (!empty($tmpWhere)) {
                        if (!empty($where))
                            $where .= " AND ";
                        $where .= "($tmpWhere)";
                    }
                }
            }
        }
        if (!empty($params1)) {
            if (empty($url)) {
                $url = $params1[0][2];
            }
            $sortColumn = $params1[0][0];
            $mode = $params1[0][1];
            if (empty($mode)  &&  self::$SessionManager->IsEmpty("SortFrontend")) {
                $mode = "ASC";
            } else {
                $mode = self::$SessionManager->GetSessionValue("SortFrontend");
                if ($mode == "ASC")
                    $mode = "DESC";
                else
                    $mode = "ASC";
            }
            self::$SessionManager->SetSessionValue("SortFrontend", $mode);
            self::$SessionManager->SetSessionValue("SortFrontendColumn", $sortColumn);
            if (!in_array($sortColumn, $columnList))
                $columns .= ", GROUP_CONCAT(if(ItemName = '$sortColumn', value, NULL)) AS '$sortColumn'";
            $sort = "$sortColumn $mode";
        }
        else {
            if (!self::$SessionManager->IsEmpty("SortFrontend")) {
                $mode = self::$SessionManager->GetSessionValue("SortFrontend");
                $sortColumn = self::$SessionManager->GetSessionValue("SortFrontendColumn");
                if (!in_array($sortColumn, $columnList))
                    $columns .= ", GROUP_CONCAT(if(ItemName = '$sortColumn', value, NULL)) AS '$sortColumn'";
                $sort = "$sortColumn $mode";
            }
        }
        if (!empty($params2)) {
            $limitMode = $params2[0];
            if (empty($url))
                $url = $params2[1];
        }



        return $page->LoadPageByAjax($url, $where, $columns, $sort, $limitMode);
    }

}
