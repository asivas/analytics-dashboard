<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\ABM\Form\FormField;
use Asivas\Analytics\Analytics;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\PercentualFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

abstract class DashboardWidgetController
{
    protected $dataMap;

    /**
     * @return array[]
     */
    public static function getLabelsSeriesMaps(): array
    {
        return self::$labelsSeriesMaps;
    }

    static public function getWidgetsMap()
    {
        return self::$labelsSeriesMaps;
    }

    public function getWidgetData(Request $request, $analyticName)
    {
        $params = $request->all();
        $from = $params['startDate'];
        $to = $params['endDate'];
        $data = $this->getData($analyticName, $from, $to);

        $dFrom = Carbon::create($from);
        $dTo = Carbon::create($to);

        $response = $this->buildResponse($data, $dFrom, $dTo, $params);

        return $response;
    }

    /**
     * @return string It should return the formatter class needed for the type of graphs and the analitic
     */
    public function getFormatterClass()
    {
        $reflect = new \ReflectionClass($this);
        $formatterName = str_replace('Controller', '', $reflect->getShortName());
        $formatterClass = $this->dataMap['formatter'];
        if (!isset($formatterClass))
            $formatterClass = PercentualFormatter::class;
        return $formatterClass;
    }

    public function getData($analyticsName, $from, $to)
    {
        $this->dataMap = self::$labelsSeriesMaps[$analyticsName];
        return Analytics::$analyticsName($from, $to);
    }

    /**
     * @param $data
     * @param $from
     * @param $to
     * @param $groupBy
     * @return array
     */
    public function buildResponse($data, $from, $to, $groupBy): array
    {
        $allSeries = [];
        $series = [];
        if (isset($this->dataMap['series'])) {
            foreach ($data as $elem) {
                $series[] = $elem[$this->dataMap['series']];
            }
            $series = array_unique($series);
            foreach ($series as $serie) {
                $auxSerie['name'] = $serie;
                $dataSerie = [];
                foreach ($data as $elem) {
                    if (($serie == $elem[$this->dataMap['series']])) {
                        $dataSerie[$elem[$this->dataMap['label']]] = $elem[$this->dataMap['serie']];
                    }
                }
                $auxSerie['data'] = $dataSerie;
                $allSeries[] = $auxSerie;
            }
        } else {
            $uniqueDataSerie = [];
            foreach ($data as $elem) {
                $uniqueDataSerie[$elem[$this->dataMap['label']]] = $elem[$this->dataMap['serie']];
                $auxSerie['name'] = $this->dataMap['serie'];
                $auxSerie['data'] = $uniqueDataSerie;
                $allSeries = [$auxSerie];
            }
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, $allSeries);
    }

    public function getGroupfields()
    {
        $leadedGroups = DoctorGroup::with('members')
            ->whereHas('members', function ($q) {
                $q->where('membership_type', 'responsable')
                    ->where('user_id', Auth::user()->id);
            });
        $groups = FormField::create('group', 'Grupo', 'select')->setFilter($leadedGroups)->setModelItem('group.id')->toArray();
        return $groups;
    }

    public function getUserfields()
    {
        $groups = FormField::create('user', 'Usuario', 'select')->setResource(User::class)->setModelItem('user.id');
        return $groups->toArray();
    }

    /**
     * The implmementing class should resolve the userTypeName according to the project's user type definition
     * @return mixed
     */
    abstract protected function getUserTypeName();

    /**
     * This method should be overridden by the implementing/final class
     * @return array
     */
    public function getAllWidgets() {
        return [];
    }

    public function getAnalytics()
    {
        $userType = $this->getUserTypeName();
        $getWidgetsFn= 'getAllWidgets';
        $getUserTypeWidgetsFn = "get{$userType}Widgets";
        if(method_exists($this,$getUserTypeWidgetsFn)) {
            $getWidgetsFn=$getUserTypeWidgetsFn;
        }
        $widgets= $this->$getWidgetsFn();
        foreach ($widgets as $widget) {
            //TODO: get widget Data
        }
        return $widgets;
        //return $this->getLabelsSeriesMaps();
    }
}
