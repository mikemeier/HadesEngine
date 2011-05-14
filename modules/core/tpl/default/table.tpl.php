<table class="fancy">
    <tr>
<?php if ($checkList): ?>
        <th><!-- check all --></th>
<?php endif; ?>
<?php foreach ($columns as $col): ?>
        <th<?php if ($col['width']): ?> style="width:<?php echo $col['width'] ?>;"<?php endif; ?>><?php echo isSet($col['heading']) ? $col['heading'] : '&nbsp;' ?></th>
<?php endforeach; ?>
<?php if ($actions): ?>
        <th><?php t('Action') ?></th>
<?php endif; ?>
    </tr>
<?php foreach ($rows as $index => $row): ?>
    <tr class="<?php echo $index % 2 == 0 ? 'odd' : 'even' ?>">
<?php  if ($checkList): ?>
        <td style="text-align:center;"><input type="checkbox" name="<?php echo 'check['.$row[$mainKey].']' ?>" id="<?php echo 'check-'.$row[$mainKey] ?>" /></td>
<?php  endif; ?>
<?php  foreach ($columns as $key => $cell): ?>
        <td<?php if ($cell['class']): ?> class="<?php echo $cell['class'] ?>"<?php endif; if ($cell['style']): ?> style="<?php echo $cell['style'] ?>"<?php endif; ?>>
<?php   if ($cell['datatype'] == 'number'): ?>
            <?php echo formatNumber($row[$key], $cell['decimals'], $cell['grouping']) ?>
<?php   elseif ($cell['datatype'] == 'date'): ?>
            <?php echo formatTime($row[$key], $cell['format']) ?>
<?php   elseif ($cell['datatype'] == 'currency'): ?>
            <?php echo formatCurrency($row[$key], $cell['format']) ?>
<?php   elseif ($cell['datatype'] == 'image'): ?>
            <img src="<?php echo str_replace('*', $row[$key], $cell['imageSrc']) ?>" alt="<?php echo $cell['imageAlt'] ?>"<?php if ($cell['imageAttrs']): ?> <?php echo $cell['imageAttrs']; endif; ?> />
<?php   else: ?>
            <?php echo $row[$key] ?>
<?php   endif; ?>
        </td>
<?php  endforeach; ?>
<?php  if ($actions): ?>
        <td style="text-align:center;">
<?php   foreach ($actions as $action): ?>
            <a href="<?php echo str_replace('*', $row[$mainKey], $action['link']) ?>"><?php if (isSet($action['icon'])): ?><img src="<?php echo str_replace('*', $row[$mainKey], $action['icon']) ?>" alt="<?php echo $action['caption'] ?>" title="<?php echo $action['caption'] ?>" /><?php else: echo $action['caption']; endif; ?></a>
<?php   endforeach; ?>
        </td>
<?php  endif; ?>
    </tr>
<?php endforeach; ?>
</table>
