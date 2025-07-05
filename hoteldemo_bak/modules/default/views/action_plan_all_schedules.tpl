<div id="ap-all-table">
    <table border="1">
        <thead>
        <tr>
            <th width="350">Module</th>
            <th width="250">Target</th>
            <th width="250">Activity</th>
            <th width="100">Date</th>
        </tr>
        </thead>
        <?php
        if(!empty($this->actionPlans))
        {
    ?>
        <tbody>
        <?php
            $i = 0;
            foreach($this->actionPlans as $a) { 
        ?>
        <tr>
            <?php if($a['module_name'] != $this->actionPlans[$i-1]['module_name']) { ?><td class="date-column" <?php if(!empty($this->totalRow['module'][$a['action_plan_module_id']])) echo 'rowspan="'.$this->totalRow['module'][$a['action_plan_module_id']].'"'; ?>><?php if($a['module_name'] != $this->actionPlans[$i-1]['module_name']) echo $a['module_name']; ?></td><?php } ?>
            <?php if($a['target_name'] != $this->actionPlans[$i-1]['target_name']) { ?><td class="date-column" <?php if(!empty($this->totalRow['target'][$a['action_plan_target_id']])) echo 'rowspan="'.$this->totalRow['target'][$a['action_plan_target_id']].'"'; ?>><?php if($a['target_name'] != $this->actionPlans[$i-1]['target_name']) echo $a['target_name']; ?></td><?php } ?>
            <?php if($a['activity_name'] != $this->actionPlans[$i-1]['activity_name']) { ?><td class="date-column" <?php if(!empty($this->totalRow['activity'][$a['action_plan_activity_id']])) echo 'rowspan="'.$this->totalRow['activity'][$a['action_plan_activity_id']].'"'; ?>><?php if($a['activity_name'] != $this->actionPlans[$i-1]['activity_name']) echo $a['activity_name']; ?></td><?php } ?>
            <td class="date-column"><?php echo $a['schedule_date_formatted']; ?></td>
        </tr>
        <?php
                $i++;
            }
        ?>				
        </tbody>
    <?php
        }
    ?>
    </table>
</div>	