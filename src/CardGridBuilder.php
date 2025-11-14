<?php
namespace Osynapsy\Bcl4\CardGrid;

use Osynapsy\Html\Tag;
use Osynapsy\Bcl4\Link;

/**
 * Description of CardGridBuilder
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CardGridBuilder 
{
    protected $grid;
    protected $foot;
    protected $itemSelected;
    
    public function __construct(CardGrid $grid)
    {
        $this->grid = $grid;
    }
    
    protected function getGrid()
    {
        return $this->grid;
    }
    
    public function build()
    {
        $grid = $this->getGrid();
        $dataset = $grid->getDataset();        
        if ($grid->hasPaginator()) {
            $this->addToFoot(new Tag('div', null, 'pt-1 pl-2'))->add($grid->getPaginator())->setPosition('end');
        }
        if (empty($dataset)) {
            $grid->addColumn(12)->add($this->emptyMessageFactory($grid->getEmptyMessage()));            
            return;
        }
        $this->itemSelected = empty($_REQUEST[$grid->id.'_chk']) ? [] : $_REQUEST[$grid->id.'_chk'];
        $this->bodyFactory($grid, $dataset);
        if ($this->foot) {
            $grid->addColumn(12)->add($this->foot);
        }        
    }
    
    public function addToFoot($content)
    {
        if (!$this->foot) {
            $this->foot = new Tag('div', null, 'd-flex justify-content-end mt1');
        }
        $this->foot->add($content);
        return $content;
    }
    
    protected function emptyMessageFactory($emptyMessage)
    {
        return sprintf('<div class="osy-cardgrid-empty mt-5 mb-5"><span>%s</span></div>', $emptyMessage);
    }

    protected function bodyFactory($grid, $dataset)
    {
        $columnWidth = floor(12 / $this->getGrid()->getColumns());
        $numColumns = 12 / $columnWidth;
        foreach($dataset as $i => $rec) {
            $column = $grid->addColumn($columnWidth)->setXs(6);
            $column->add($this->cellFactory($grid, $rec));
            if ((($i+1) % $numColumns) === 0) {
                $grid->addRow();
            }
        }
    }

    protected function cellFactory($grid, $rec)
    {
        return (new CardGridCell('div', null, $grid->getSkin()))
            ->setFields($grid->getFields())
            ->setRecord($rec)
            ->setCellHeight($grid->getCellHeight())
            ->setCellFoot($grid->hasCellFoot());
    }
    
    public function __toString()
    {
        return $this->build();
    }
}
