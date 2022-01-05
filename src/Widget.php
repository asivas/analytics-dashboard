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
    protected $formatter;
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

    public function toArray() :array
    {
        return [
            'url'=>$this->url,
            'title'=>$this->title,
            'label'=>$this->label,
            'serie'=>$this->serie,
            'formatter'=>$this->formatter
        ];
    }

    public function offsetExists($offset)
    {
        return isset($this->toArray()[$offset]);
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
        return $this->toArray();
    }

    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }

}