<?php
namespace Osynapsy\Bcl4\CardGrid;

use Osynapsy\Bcl4\Link;
use Osynapsy\Html\Tag;

/**
 * Description of CardGridCellBuilder
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CardGridCellBuilder
{
    const SUB_MAIN = 'main';
    const SUB_IMAGE = 'image';
    const SUB_FOOT = 'foot';

    protected $cell;
    protected $subcell = [];

    public function __construct(CardGridCell $cell)
    {
        $this->cell = $cell;
    }

    public function build()
    {
        $this->subcell[self::SUB_IMAGE] = $this->cell->add(new Tag('div', null, 'p0'));
        $this->subcell[self::SUB_MAIN] = $this->cell->add(new Tag('div', null, 'p1'));
        $this->subcell[self::SUB_FOOT] = $this->cell->add(new Tag('div', null, 'p2'));
        if (!$this->cell->hasCellFoot()) {
            $this->getSubCell(self::SUB_FOOT)->addClass('d-none');
        }
        $fields = $this->cell->getFields();
        $record = $this->cell->getRecord();
        foreach($record as $fieldId => $value) {
            if (!array_key_exists($fieldId, $fields)) {
                continue;
            }
            $type = $fields[$fieldId]['type'];
            $formatting = $fields[$fieldId]['formatting'];
            if (is_callable($type)) {
                $type($record, $this);
                continue;
            }
            if (is_callable($formatting)) {
                $value = $formatting($record, $this);
            }
            $this->fieldFactory($type, $value);
        }
    }

    protected function fieldFactory($type, $value)
    {
        $cell = $this->cell;
        switch($type) {
            case 'checkbox':
                $this->checkboxFactory($value);
                break;
            case 'linkNormal':
                $cell->add($this->linkFactory($value));
                break;
            case 'linkModal':
                $cell->add($this->linkFactory($value));
                break;
            case 'class':
                $cell->addClass($value);
                break;
            case 'img':
                $this->getSubcell('image')->add($this->imageFactory($value));
                break;
            case 'tag':
                $this->getSubcell('foot')->add('<span>'.$value.'</span><br>');
                break;
            case 'title':
                $value = '<strong>'.$value.'</strong>';
            default:
                $this->getSubCell()->add('<div class="p1-row">'.$value.'</div>');
                break;
        }
    }

    protected function checkboxFactory($value)
    {
        $checked = '';
        if (!empty($this->itemSelected[$value])) {
            $this->addClass('osy-cardgrid-item-selected');
            $checked=' checked="checked"';
        }
        $this->cell->add('<span class="fa fa-check"></span>');
        $this->add('<input type="checkbox" name="'.$this->id.'_chk['.$value.']" value="'.$value.'"'.$checked.' class="osy-cardgrid-checkbox">');
    }

    protected function linkFactory($href)
    {
        return (new Link(false, $href, '', 'osy-cardgrid-link fa fa-pencil save-history'));
    }

    protected function linkModalFactory($href)
    {
        return (new Link(false, $href, '', 'osy-cardgrid-link fa fa-pencil fa-pencil-alt'))->inModal();
    }

    protected function imageFactory($src)
    {
        if (!empty($src)) {
           return (new Tag('img', null, 'osy-cardgrid-img'))->attribute('src', $src);
        }
        return '<span class="fa fa-user fa-2x osy-cardgrid-img text-center" style="padding-top: 3px"></span>';
    }

    public function getSubCell($id = 'main')
    {
        return $this->subcell[$id];
    }
}
