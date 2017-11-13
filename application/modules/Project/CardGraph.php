<?php

class CardGraph
{

   private $graph = [];
   private $prices = [];

    private function taskSum($card)
    {
        $sum = 0;
        foreach ($card->tasksid as $taskid)
        {
            $task = Dejavu::getObject('Task', $taskid);
            $sum += $task->verhours + $task->exhours;
        }
        return $sum;
    }

    private function getChildrenGraph($stageid)
    {
        $stage = Dejavu::getObject('Stage', $stageid);
        foreach ($stage->desksid as $deskid)
        {
            $desk = Dejavu::getObject('Desk', $deskid);
            foreach ($desk->cardsid as $cardid)
            {
                $card = Dejavu::getObject('Card', $cardid);
                if ($card->status != 'done')
                {

                    $this->prices[$cardid] = $this->taskSum($card);

                    foreach ($card->childrenid as $childid)
                    {
                        if (!isset($this->graph[$cardid]))
                            $this->graph[$cardid] = [$childid];
                        else
                        {
                            array_push($this->graph[$cardid], $childid);
                        }
                    }
                }
            }
        }
    }

    public function maxWay($stageid)
    {
        $this->getChildrenGraph($stageid);
        return $this->max_len($this->graph, $this->prices);

    }

    function max_way(&$graph, &$used, &$max_ways, &$prices, $v)
        {
            $White = 0;
            $Gray = 1;
            $Black = 2;

            if ($used[$v] == $Black){ return $max_ways[$v]; }
            if ($used[$v] == $Gray) { return; }
            $used[v] = $Gray;
            $max_ways[$v] = $prices[$v];
            for ($i = 0; $i < count($graph[$v]); $i++){
                $max_ways[$v] = max($max_ways[$v], $prices[$v] +
                        $this->max_way($graph, $used, $max_ways,
                                $prices, $graph[$v][$i]));
            }
            $used[$v] = $Black;
            return $max_ways[$v];
        }
    function max_len(&$graph, &$prices)
        {
            $White = 0;
            $Gray = 1;
            $Black = 2;

         //   if (count($prices) != count($graph)) { return; }
            $used = [];
            $max_ways = [];
            foreach($graph as $kek){
                array_push($used, $White);
                array_push($max_ways, 0);
            }
            $result = 0;
               foreach($graph as $kek=>$lol){
                $result = max($result, $this->max_way($graph, $used,
                        $max_ways, $prices, $kek));
            }
            return $result;
        }
}
