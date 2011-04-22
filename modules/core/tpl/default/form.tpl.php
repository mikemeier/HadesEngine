<form action="<?php echo $settings['action'] ?>" method="<?php echo $settings['method'] ?>" enctype="multipart/form-data"<?php if ($settings['name']): ?> id="<?php echo $settings['name'] ?>"<?php endif; ?> class="fancy">
    <input type="hidden" name="submit" value="<?php echo ($settings['name'] ? md5($settings['name']) : 1) ?>" />
<?php foreach ($stack as $input): ?>
<?php if ($input['type'] == 'hidden'): ?>
	<input type="hidden" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input.params.value): ?> value="<?php echo $input['params']['value'] ?>"<?php endif; ?> />
<?php elseif ($input['type'] == 'radiobuttons'): ?>
    <div class="controlset field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="controlset-fields">
<?php foreach ($input['params']['options'] as $key => $option): ?>
            <input type="radio" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>_<?php echo $key ?>" value="<?php echo $key ?>"<?php if ($key == $input['params']['selected']): ?> selected="selected"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $input['params']['name'] ?>_<?php echo $key ?>"><?php echo $option ?></label>
<?php endforeach; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'checkbox'): ?>
    <div class="controlset field<?php if (!$input.valid): ?> error<?php endif; ?>">
        <div class="controlset-fields">
            <input type="checkbox" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input.params.checked == true): ?> checked="checked"<?php endif; if ($input.params.value): ?> value="<?php echo $input['params']['value'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label>
        </div>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'checkboxes'): ?>
    <div class="controlset field<?php if (!$input.valid): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="controlset-fields">
<?php foreach ($input['params']['options'] as $key => $option): ?>
            <input type="checkbox" name="<?php echo $key ?>" id="<?php echo $key ?>"<?php if ($key == $input['params']['checked']): ?> checked="checked"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
            <label for="<?php echo $key ?>"><?php echo $option ?></label>
<?php endforeach; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'textinput'): ?>
    <div class="field<?php if (!$input.valid): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <input type="<?php echo ($input.params.password ? 'password' : 'text') ?>" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input.params.value): ?> value="<?php echo $input['params']['value'] ?>"<?php endif; if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['maxlength']): ?> maxlength="<?php echo $input['params']['maxlength'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'fileinput'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <input type="file" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> />
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'select'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <select name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?>>
<?php foreach ($input['params']['options'] as $key => $option): ?>
            <option value="<?php echo $key ?>"<?php if ($key == $input['params']['selected']): ?> selected="selected"<?php endif; ?>><?php echo $option ?></option>
<?php endforeach; ?>
        </select>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'date'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <?php if(isset($input['params']['day'])): ?>
            <select name="<?php echo $input['params']['name'] ?>Day">
                <?php for($i = $input['params']['min_day']; $i <= $input['params']['max_day']; $i++): ?>
                <option value="<?php echo $i ?>" <?php if($i == $input['params']['day']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>
        <?php if(isset($input['params']['month'])): ?>
            <select name="<?php echo $input['params']['name'] ?>Month">
                <?php for($i = $input['params']['min_month']; $i <= $input['params']['max_month']; $i++): ?>
                <option value="<?php echo $i ?>" <?php if($i == $input['params']['month']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>
        <?php if(isset($input['params']['year'])): ?>
            <select name="<?php echo $input['params']['name'] ?>Day">
                <?php for($i = $input['params']['min_year']; $i <= $input['params']['max_year']; $i++): ?>
                <option value="<?php echo $i ?>" <?php if($i == $input['params']['year']): ?>selected="selected"<?php endif; ?>><?php echo $i ?></option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'textarea'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <textarea name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input['params']['rows']): ?> rows="<?php echo $input['params']['rows'] ?>"<?php endif; if ($input['params']['cols']): ?> cols="<?php echo $input['params']['cols'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?>><?php echo $input['params']['value'] ?></textarea>
        <?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php elseif ($input['type'] == 'captcha'): ?>
    <div class="field<?php if (!$input['valid']): ?> error<?php endif; ?>">
        <?php if ($input['params']['title']): ?><label for="<?php echo $input['params']['name'] ?>"><?php echo $input['params']['title']; if ($input['params']['required'] || $input['params']['min_length'] || $input['params']['min_range']): ?> <em class="required">*</em><?php endif; ?></label><?php endif; ?>
        <div class="captcha">
          <img src="data:image/jpeg;base64,{$input.params.captcha|base64_encode}" alt="" style="display: block;" />
          <input type="text" name="<?php echo $input['params']['name'] ?>" id="<?php echo $input['params']['name'] ?>"<?php if ($input['params']['size']): ?> size="<?php echo $input['params']['size'] ?>"<?php endif; if ($input['params']['maxlength']): ?> maxlength="<?php echo $input['params']['maxlength'] ?>"<?php endif; if ($input['params']['class']): ?> class="<?php echo $input['params']['class'] ?>"<?php endif; if ($input['params']['style']): ?> style="<?php echo $input['params']['style'] ?>"<?php endif; ?> /><?php if ($input['params']['note']): ?><a class="help" rel="tooltip" href="javascript:;" title="<?php echo $input['params']['note'] ?>"></a><?php endif; ?>
        </div>
        <?php if ($input['params']['description']): ?><span class="description"><?php echo $input['params']['description'] ?></span><?php endif; ?>
        <?php if (!$input['valid'] && !$input['error_text']): ?><span class="error-text"><?php echo $input['params']['error_text'] ?></span><?php endif; ?>
    </div>
<?php endif; ?>
<?php endforeach; ?>
    <div class="buttons">
<?php if ($settings['buttons']): ?>
<?php foreach ($settings['buttons'] as $button): ?>
        <input type="<?php if ($button['submit']): ?>submit<?php elseif ($button['reset']): ?>reset<?php else: ?>button<?php endif; ?>" value="<?php echo $button['caption'] ?>" class="<?php echo ($button['class'] ? $button['class'] : 'button') ?>"<?php echo $button['attributes'] ?> />
<?php endforeach; ?>
<?php else: ?>
        <input type="submit" value="Submit" class="button" />
        <input type="reset" value="Reset" class="button" />
<?php endif; ?>
    </div>
</form>
