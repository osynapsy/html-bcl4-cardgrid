<?php
/*
 * This file is part of the Osynapsy Bcl4 CartGrid package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4\CardGrid;

use Osynapsy\Bcl4\Panel;

/**
 * Description of Adressbook
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CardGrid extends Panel
{
    protected $columns = 4;
    protected $foot;
    protected array $skin = [];
    protected $cellHeight;
    protected $emptyMessage;
    protected $itemSelected;
    protected $paginator;
    protected $showPaginationPageDimension;
    protected $showPaginationPageInfo;
    protected $fields = [];
    protected $cellFoot = false;

    public function __construct($id, $emptyMessage = 'Grid is empty', $columns = 4)
    {
        parent::__construct($id);
        $this->setClass('','','','osy-cardgrid');
        $this->columns = $columns;
        $this->emptyMessage = $emptyMessage;
        $this->requireCss('bcl4/cardgrid/style.css');
        $this->requireJs('bcl4/cardgrid/script.js');
    }

    public function preBuild()
    {
        (new CardGridBuilder($this))->build();
        parent::preBuild();
    }

    public function addField($field, $type = 'string', callable $formatting = null)
    {
        $this->fields[$field] = ['type' => $type, 'formatting' => $formatting];
        return $this;
    }

    public function enableCellFoot()
    {
        $this->cellFoot = true;
        return $this;
    }

    public function getCellHeight()
    {
        return $this->cellHeight;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getDataset()
    {
        if (empty($this->paginator)) {
            return parent::getDataset();
        }
        try {
            return $this->paginator->loadData(null, true);
        } catch (\Exception $e) {
            $this->emptyMessage = $e->getMessage();
        }
        return [];
    }

    public function getEmptyMessage()
    {
        return $this->emptyMessage;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getPaginator()
    {
        return $this->paginator;
    }

    public function getSkin()
    {
        return $this->skin;
    }

    public function hasCellFoot()
    {
        return $this->cellFoot;
    }

    public function hasPaginator()
    {
        return !empty($this->paginator);
    }

    public function setCellHeight($height)
    {
        $this->cellHeight = $height;
        return $this;
    }

    public function setPaginator($paginator, $showPageDimension = true, $showPageInfo = true)
    {
        $this->paginator = $paginator;
        $this->paginator->setParentComponent($this->id);
        $this->showPaginationPageDimension = $showPageDimension;
        $this->showPaginationPageInfo = $showPageInfo;
        return $this->paginator;
    }

    public function setSkin($skin)
    {
        $this->skin = [$skin];
        return $this;
    }

    public function showBackground()
    {
        $this->addClass('osy-cardgrid-background-white');
        return $this;
    }
}