<?php


namespace Asivas\Analytics;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Analytics
{
    protected $mainModel;

    /**
     * @return Builder
     */
    protected function getInitialQuery()
    {
        return $this->mainModel::query();
    }

    /**
     * @param Builder $q
     * @return Builder
     */
    protected function addJoins(Builder $q) {
        return $q;
    }

    /**
     * @param Builder $q
     * @return Builder
     */
    protected function addWheres(Builder $q,$from,$to) {
        return $q;
    }

    /**
     * @param Builder $q
     * @return Builder
     */
    protected function addGroupBys(Builder $q) {
        return $q;
    }

    /**
     * @return Builder
     * @param $from
     * @param $to
     * @return Builder
     */
    public static function query($from,$to) {
        return (new static)->buildQuery($from,$to);
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    protected function buildQuery($from,$to) {

        $qWithJoins= $this->addJoins($this->getInitialQuery());

        $qWithJoins= $this->addUserDependentJoins($qWithJoins,Auth::user());

        $qWithWheres= $this->addWheres($qWithJoins,$from,$to);

        $qWithWheres= $this->addUserDependentWheres($qWithWheres,Auth::user());

        $qWithGroupsBy= $this->addGroupBys($qWithWheres);

        $qWithGroupsBy= $this->addUserDependentGroupBy($qWithGroupsBy,Auth::user());

        return $qWithGroupsBy;

    }

    public static function getData($from,$to) {
       return self::query($from, $to)->get();
    }

    protected function addUserDependentJoins(Builder $qWithJoins, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        return $qWithJoins;
    }

    protected function addUserDependentWheres(Builder $qWithWheres, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        return $qWithWheres;
    }

    protected function addUserDependentGroupBy($qWithGroupsBy, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        return $qWithGroupsBy;
    }

    protected function processParams($params)
    {
    }
}
