<?php

namespace Asivas\Analytics\Widget;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class Widget implements \ArrayAccess, Arrayable, Jsonable, \JsonSerializable
{
    protected $url;
    protected $title;
    protected $label;
    protected $serie;
    protected $series;
    protected $formatter;
    protected $type;

    protected $size;

    protected $data;
    protected $controllerClass;

    // attributes used to force information on frontend (css classes and columns of the containing parent)
    protected $baseDisplayClass;
    protected $columns;

    /** @var \Closure */
    protected $shouldDisplayClosure;

    public function __construct($title, $type = 'Widget', $controllerClass=null)
    {
        $this->type = $type;
        $this->title = $title;
        if(isset($controllerClass)) $this->setControllerClass($controllerClass);
        $this->shouldDisplayClosure = function ($from,$to,$data=null) { return true; };
    }

    static function create($title, $type = null) {
        return new static($title, $type);
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

    /**
     * @return mixed
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param mixed $controllerClass
     * @return Widget
     */
    public function setControllerClass($controllerClass)
    {
        $this->controllerClass = $controllerClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseDisplayClass()
    {
        return $this->baseDisplayClass;
    }

    /**
     * @param mixed $baseDisplayClass
     * @return Widget
     */
    public function setBaseDisplayClass($baseDisplayClass)
    {
        $this->baseDisplayClass = $baseDisplayClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param mixed $columns
     * @return Widget
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return \Closure
     */
    public function getShouldDisplayClosure(): \Closure
    {
        return $this->shouldDisplayClosure;
    }

    /**
     * @param \Closure $shouldDisplayClosure
     * @return Widget
     */
    public function setShouldDisplayClosure(\Closure $shouldDisplayClosure): Widget
    {
        $this->shouldDisplayClosure = $shouldDisplayClosure;
        return $this;
    }



    public function toArray() :array
    {
        return [
            'url'=>$this->url,
            'title'=>$this->title,
            'formatter' => $this->formatter,
            'type'=> $this->type,
            'data' => $this->data,
            'size'=> $this->size,
            'cssClass' => $this->baseDisplayClass . " col".(isset($this->columns)?'-'.$this->columns:'')
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