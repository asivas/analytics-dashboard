<?php

namespace Asivas\Analytics;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

class Widget implements \ArrayAccess, Arrayable, Jsonable, \JsonSerializable
{
    protected $url;
    protected $title;
    protected $label;
    protected $serie;
    protected $series;
    protected $formatter;
    protected $type;

    protected $icon;
    protected $bgcolor;
    protected $data;
    protected $counter;

    public function __construct($type,$title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    static function create($type,$title) {
        return new static($type,$title);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Widget
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Widget
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return Widget
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param mixed $serie
     * @return Widget
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param mixed $formatter
     * @return Widget
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Widget
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     * @return Widget
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBgcolor()
    {
        return $this->bgcolor;
    }

    /**
     * @param mixed $bgcolor
     * @return Widget
     */
    public function setBgcolor($bgcolor)
    {
        $this->bgcolor = $bgcolor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Widget
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param mixed $counter
     * @return Widget
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param mixed $series
     * @return Widget
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }


    public function toArray() :array
    {
        return [
            'url'=>$this->url,
            'title'=>$this->title,
            'label'=>$this->label,
            'serie'=>$this->serie,
            'formatter' => $this->formatter,
            'type'=> $this->type,
            'icon' => $this->icon,
            'bgcolor' => $this->bgcolor,
            'counter' => $this->counter,
            'data' => $this->data,
            'series' => $this->series,
        ];
    }

    public function offsetExists($offset)
    {
        $arr = $this->toArray();
        return isset($arr[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->toArray()[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $setFn = "set".Str::ucfirst($offset);
        $this->$setFn($value);
    }

    public function offsetUnset($offset)
    {
        $setFn = "set".Str::ucfirst($offset);
        $this->$setFn(null);
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

}