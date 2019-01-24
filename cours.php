<?php
class cours
{
    public $name;
    public $teacher;
    public $start;
    public $end;
    public $group;


    public function __toString()
    {
        return $this->start.' <> '.$this->end.' : '.$this->name.' ';
    }

}
class salle
{
    public $name;
    public $id;

    public function __construct($name, $id) {
        $this->name = $name;
        $this->id = $id;
    }

    public function __toString()
    {
        return $this->name;
    }

}

class salleDispo
{
    public $name;
    public $next;

    public function __construct($name, $next) {
        $this->name = $name;
        $this->next = $next;
    }

    public function __toString()
    {
        return $this->name.' -> '.$this->next;
    }

}

function cmpStartDate($a, $b)
{
	if( intval(substr($a->start,0,4)) == intval(substr($b->start,0,4))){
		if(intval(substr($a->start,5,7)) == intval(substr($b->start,5,7))){
			if(intval(substr($a->start,8,10)) == intval(substr($b->start,8,10))){
				if(intval(substr($a->start,11,13)) == intval(substr($b->start,11,13))){
					return intval(substr($a->start,14,16)) - intval(substr($b->start,14,16));
				}
				return intval(substr($a->start,11,13)) - intval(substr($b->start,11,13));
			}
			return intval(substr($a->start,8,10)) - intval(substr($b->start,8,10));
		}
		return intval(substr($a->start,5,7)) - intval(substr($b->start,5,7));
	}
	return intval(substr($a->start,0,4)) - intval(substr($b->start,0,4));
}



function cmpSD($a, $b)
{
	if( intval(substr($a->next,0,4)) == intval(substr($b->next,0,4))){
		if(intval(substr($a->next,5,7)) == intval(substr($b->next,5,7))){
			if(intval(substr($a->next,8,10)) == intval(substr($b->next,8,10))){
				if(intval(substr($a->next,11,13)) == intval(substr($b->next,11,13))){
					return intval(substr($a->next,14,16)) - intval(substr($b->next,14,16));
				}
				return intval(substr($a->next,11,13)) - intval(substr($b->next,11,13));
			}
			return intval(substr($a->next,8,10)) - intval(substr($b->next,8,10));
		}
		return intval(substr($a->next,5,7)) - intval(substr($b->next,5,7));
	}
	return intval(substr($a->next,0,4)) - intval(substr($b->next,0,4));
}


function inClass($list, $date)
{
	$nb = 0;
	for ($i = 0; $i < sizeof($list); $i++)
	{
		if(strcmp($list[$i]->start,$date)<0 && strcmp($list[$i]->end,$date)>0){
			$nb=$nb+1;
		}
	}
	return $nb;
}

function nextClass($list, $date)
{


	usort($list, "cmpStartDate");
	$nb = 0;
	for ($i = 0; $i < sizeof($list); $i++)
	{
		if(strcmp($list[$i]->start,$date)>0){
			return $list[$i]->start;
		}
	}
	return 'la semaine prochaine';
}


?>
