<?php
namespace Osynapsy\Bcl4\CardGrid;

use Osynapsy\Html\Tag;

/**
 * Description of CardGridCellFactory
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CardGridCell extends Tag
{
    protected $cellHeight;
    protected $cellFoot;
    protected $fields;
    protected $rec;


    public function __construct($tag = 'div', $id = null, $class = [])
    {
        $class[] = 'osy-cardgrid-item';
        parent::__construct($tag, $id, implode(' ', $class));
    }

    public function preBuild()
    {
        (new CardGridCellBuilder($this))->build();
        parent::preBuild();
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function hasCellFoot()
    {
        return $this->cellFoot;
    }

    public function setCellFoot($hasCellFoot)
    {
        $this->cellFoot = $hasCellFoot;
        return $this;
    }

    public function getCellHeight()
    {
        return $this->cellHeight;
    }

    public function setCellHeight($height)
    {
        $this->addStyle('height', $height . 'px');
        return $this;
    }

    public function getRecord()
    {
        return $this->rec;
    }

    public function setRecord($record)
    {
        $this->rec = $record;
        return $this;
    }
}
