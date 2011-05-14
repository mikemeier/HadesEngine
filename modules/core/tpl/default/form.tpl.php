<form action="<?php echo $action ?>" method="<?php echo $method ?>" enctype="multipart/form-data"<?php if ($name): ?> id="<?php echo $name ?>"<?php endif; ?> class="fancy">
    <input type="hidden" name="submit" value="<?php echo isSet($name) ? md5($name) : 1 ?>" />
<?php foreach ($stack as $input): ?>
<?php  if ($input['type'] == 'hidden'): ?>
    <input type="hidden" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['value']): ?> value="<?php echo $input['value'] ?>"<?php endif; ?> />
<?php  elseif ($input['type'] == 'radiobuttons'): ?>
    <div class="controlset field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="controlset-fields">
<?php   foreach ($input['options'] as $key => $option): ?>
            <input type="radio" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'].'_'.$key ?>" value="<?php echo $option[0] ?>"<?php if ($key == $input['params']['selected']): ?> checked="checked"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $input['name'].'_'.$key ?>"><?php echo $option[1] ?></label>
<?php   endforeach; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'checkbox'): ?>
    <div class="controlset field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <div class="controlset-fields">
            <input type="checkbox" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['params']['checked'] == true): ?> checked="checked"<?php endif; ?> value="<?php echo $input['value'] ?>"<?php if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label>
        </div>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'checkboxes'): ?>
    <div class="controlset field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="controlset-fields">
<?php   foreach ($input['options'] as $key => $option): ?>
            <input type="checkbox" name="<?php echo $input['name'].'['.$key.']' ?>" id="<?php echo $input['name'].'['.$key.']' ?>" value="<?php echo $option[0] ?>"<?php if (in_array($key, $input['params']['checked'])): ?> checked="checked"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $input['name'].'['.$key.']' ?>"><?php echo $option[1] ?></label>
<?php   endforeach; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'textinput'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <input type="<?php echo ($input['params']['password'] ? 'password' : 'text') ?>" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['value']): ?> value="<?php echo $input['value'] ?>"<?php endif; if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['maxlength']): ?> maxlength="<?php echo $input['params']['maxlength'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'fileinput'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <input type="file" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'select'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <select name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?>>
<?php   foreach ($input['options'] as $key => $option): ?>
            <option value="<?php echo $key ?>"<?php if ($key == $input['params']['selected']): ?> selected="selected"<?php endif; ?>><?php echo $option ?></option>
<?php   endforeach; ?>
        </select>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'date'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
<?php   if(isSet($input['day'])): ?>
        <select name="<?php echo $input['name'] ?>Day">
            <?php for($i = $input['params']['minDay']; $i <= $input['params']['maxDay']; $i++): ?>
            <option value="<?php echo $i ?>" <?php if($i == $input['day']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
            <?php endfor; ?>
        </select>
<?php   endif; ?>
<?php   if(isSet($input['month'])): ?>
        <select name="<?php echo $input['name'] ?>Month">
            <?php for($i = $input['params']['minMonth']; $i <= $input['params']['maxMonth']; $i++): ?>
            <option value="<?php echo $i ?>" <?php if($i == $input['month']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
            <?php endfor; ?>
        </select>
<?php   endif; ?>
<?php   if(isSet($input['year'])): ?>
        <select name="<?php echo $input['name'] ?>Day">
            <?php for($i = $input['params']['minYear']; $i <= $input['params']['maxYear']; $i++): ?>
            <option value="<?php echo $i ?>" <?php if($i == $input['year']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
            <?php endfor; ?>
        </select>
<?php   endif; ?>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'textarea'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <textarea name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['params']['rows']): ?> rows="<?php echo $input['params']['rows'] ?>"<?php endif; if ($input['params']['cols']): ?> cols="<?php echo $input['params']['cols'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?>><?php echo $input['value'] ?></textarea>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  elseif ($input['type'] == 'captcha'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['minLength'] || $input['params']['minRange']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="captcha">
          <img src="data:image/jpeg;base64,<?php echo base64_encode($input['captcha']) ?>" alt="" style="display: block;" />
          <input type="text" name="<?php echo $input['name'] ?>" id="<?php echo $input['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['maxlength']): ?> maxlength="<?php echo $input['params']['maxlength'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> /><?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['errorText']): ?><span class="error-text"><?php echo $input['params']['errorText'] ?></span><?php endif; ?>
    </div>
<?php  endif; ?>
<?php endforeach; ?>
    <div class="buttons">
<?php if ($buttons): ?>
<?php  foreach ($buttons as $button): ?>
        <input type="<?php if ($button['submit']): ?>submit<?php elseif ($button['reset']): ?>reset<?php else: ?>button<?php endif; ?>" value="<?php echo $button['caption'] ?>" class="<?php echo ($button['class'] ? $button['class'] : 'button') ?>"<?php echo isSet($button['attributes']) ? ' '.$button['attributes'] : '' ?> />
<?php  endforeach; ?>
<?php else: ?>
        <input type="submit" value="<?php t('Submit') ?>" class="button" />
        <input type="reset" value="<?php t('Reset') ?>" class="button" />
<?php endif; ?>
    </div>
</form>
