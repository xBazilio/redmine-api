<?php
/**
 * @author: mix
 * @date: 01.05.14
 */
namespace RedmineApi\Api;

/**
 * Class Deploy_RedmineBot_Api_IssueRelations
 * @see http://www.redmine.org/projects/redmine/wiki/Rest_IssueRelations
 * @package Redmine\Api
 */
class IssueRelations extends Base
{
    public function findFor($id) {
        $res = $this->request("GET", "/issues/$id/relations.json");

        return $res["relations"];
    }

    /**
     * @var array available types
     */
    private $types = ["relates", "duplicates", "duplicated", "blocks", "blocked", "precedes", "follows"];

    /**
     * @param int $from
     * @param int $to
     * @param string $type
     * @param int $delay
     * @return array
     */
    public function create($from, $to, $type, $delay = null) {

        if(!in_array($type, $this->types)){
            return false;
        }

        $params = ["issue_to_id" => $to, "relation_type" => $type];
        if ($delay) {
            $params["delay"] = $delay;
        }
        $res = $this->request(
            "POST",
            "/issues/$from/relations.json",
            ["relation" => $params]
        );

        return $res;
    }
}