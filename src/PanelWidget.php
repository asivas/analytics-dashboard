<?php

namespace Asivas\Analytics;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

class PanelWidget implements \ArrayAccess, Arrayable, Jsonable, \JsonSerializable
{
    protected $widgets;
    protected $title;
    protected $type;

    public function __construct($type,$title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    static function create($type,$title) {
        return new static($type,$title);
    }

    /**
     * @return mixed
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * @param mixed $widget
     * @return PanelWidget
     */
    public function setWidgets($widget)
    {
        $this->widgets[] = $widget;
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
     * @return PanelWidget
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return PanelWidget
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



    public function toArray() :array
    {
        return [
            'title'=>$this->title,
            'type'=> $this->type,
            'widgets' => $this->widgets,
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