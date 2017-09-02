<?php
/* class for handling field inputs
 */

use TheMatrix\View;
use TheMatrix\Plugin;

class MatrixDisplayField {
  /* constants */
  /* properties */
  private $matrix;
  private $schema;
  private $name;
  private $type;
  private $method;
  private $paths = array();
  private $properties;
  private $value;

  /* methods */
  # constructor
  public function __construct($matrix, $schema=array(), $value=null, $paths=array()) {
    // initialize
    $this->matrix = $matrix;
    $this->schema = $schema;
    $this->name   = $schema['name'];
    $this->id     = 'post-'.$this->name;
    $this->type   = $schema['type'];
    if (is_string($value)) {
      $this->value  = (strlen($value) > 0) ? $value : $schema['default'];
    }
    else $this->value = $value;
    $this->paths  = $paths;

    // backwards compatibility
    if (!isset($schema['mask'])) $schema['mask'] = null;

    // get the correct method name
    $this->method = $schema['type'] . (!empty($schema['mask']) ? '_'.$schema['mask'] : '') ;

    // fill in properties
    $this->properties .= 'style="';
    $this->properties .= !empty($schema['width'])? 'width: '.$schema['width'].'; ' : '';
    $this->properties .= !empty($schema['height'])? 'height: '.$schema['height'].'; ' : '';
    $this->properties .= !empty($schema['style'])? $schema['style'] : '';
    $this->properties .= '" ';
    $this->properties .= 'name="post-'.$schema['name'].'" ';
    $this->properties .= 'id="post-'.$schema['name'].'" ';
    $this->properties .= 'placeholder="'.$schema['placeholder'].'" ';
    $this->properties .= $schema['maxlength'] > 0 ? 'maxlength="'.$schema['maxlength'].'" ' : '';
    $this->properties .= strlen($schema['max']) > 0 ? 'max="'.$schema['max'].'" ' : '';
    $this->properties .= strlen($schema['min']) > 0 ? 'min="'.$schema['min'].'" ' : '';
    $this->properties .= strlen($schema['step']) > 0 ? 'step="'.$schema['step'].'" ' : '';
    $this->properties .= !empty($schema['readonly']) ? 'readonly="readonly" ' : '';
    $this->properties .= !empty($schema['required']) ? 'required="required" ' : '';
    $this->properties .= !empty($schema['validation']) ? 'pattern="'.$schema['validation'].'" ' : '';
  }

  /* functions needed for parsing */
  # get keys for multi-based fields
  private function get_multi_keys($rows) {
    if (is_numeric($rows)) {
      $keys = array_fill(0, $rows, 'key');
    }
    else {
      $keys = $this->matrix->explodeTrim("\n", $rows);
      $keys = array_fill_keys($keys, 'key');
    }
    return $keys;
  }

  /* inputs */
  # input (text)
  private function input()
  {
    $view = new View('fields/input');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # input (textlong)
  private function input_long()
  {
    $view = new View('fields/input_long');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # input (slug)
  private function input_slug()
  {
    $view = new View('fields/input_slug');

    echo $view->render([
      'value'      => $this->value,
      'properties' => $this->properties,
      'selector'   => '#' . $this->id
    ]);
  }

  # password
  private function input_password()
  {
    $view = new View('fields/input_password');

    echo $view->render(['properties' => $this->properties]);
  }

  # url
  private function input_url()
  {
    $view = new View('fields/input_url');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # email
  private function input_email()
  {
    $view = new View('fields/input_email');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # int
  private function input_number()
  {
    $view = new View('fields/input_number');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # range
  private function input_range()
  {
    $view = new View('fields/input_range');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # color
  private function input_color()
  {
    $view = new View('fields/input_color');

    echo $view->render(['value' => $this->value, 'properties' => $this->properties]);
  }

  # multi (text)
  private function multi_text()
  {
    $inputs = $this->extractDataForMultipleFields();
    $view   = new View('fields/multi_text');

    echo $view->render(['inputs' => $inputs]);
  }

  /**
   * @return array
   */
  private function extractDataForMultipleFields()
  {
    $this->schema['desc'] = $this->matrix->explodeTrim("\n", $this->schema['desc']);
    $options    = $this->matrix->explodeTrim("\n", $this->schema['options']);
    $keys       = $this->get_multi_keys($this->schema['rows']);
    $labels     = !empty($this->schema['labels']) ? $this->matrix->explodeTrim("\n", $this->schema['labels']) : array();
    $values     = explode_trim("\n", $this->value);
    $data       = [];
    $fieldIndex = 0;

    foreach ($keys as $i => $val) {
      $value = 0;

      if (isset($values[$i])) {
        $value = $values[$i];
      } elseif (isset($values[$fieldIndex])) {
        $value = $values[$fieldIndex];
      } elseif (isset($options[$i])) {
        $value = $options[$i];
      }

      $label       = !empty($labels) && isset($labels[$fieldIndex]) ? $labels[$fieldIndex] : null;
      $name        = $this->name;
      $properties  = $this->properties;
      $placeholder = isset($this->schema['desc'][$i]) ? $this->schema['desc'][$i] : null;
      $readonly    = $this->schema['readonly'];
      $required    = $this->schema['required'];
      $validation  = strlen(trim($this->schema['validation'])) > 0 ? $this->schema['validation'] : null;

      $data[] = (object) [
        'label'       => $label,
        'name'        => $name,
        'value'       => $value,
        'properties'  => $properties,
        'placeholder' => $placeholder,
        'readonly'    => $readonly,
        'required'    => $required,
        'validation'  => $validation
      ];

      $fieldIndex++;
    }

    return $data;
  }

  # multiple colors
  private function multi_color()
  {
    $inputs = $this->extractDataForMultipleFields();
    $view   = new View('fields/multi_color');

    echo $view->render(['inputs' => $inputs]);
  }

  # multi (numeric)
  private function multi_number()
  {
    $inputs = $this->extractDataForMultipleFields();
    $view   = new View('fields/multi_number');

    echo $view->render(['inputs' => $inputs]);
  }

  # multi (textarea)
  private function multi_textarea()
  {
    $textareas = $this->extractDataForMultipleFields();
    $view      = new View('fields/multi_textarea');

    echo $view->render(['textareas' => $textareas]);
  }

  # multi (rich text editor)
  private function multi_rte()
  {
    $textareas = $this->extractDataForMultipleFields();
    $view      = new View('fields/multi_rte');

    echo $view->render([
      'id'        => $this->id,
      'textareas' => $textareas
    ]);
  }

  # multi (code)
  private function multi_code()
  {
    $textareas = $this->extractDataForMultipleFields();
    $view      = new View('fields/multi_code');

    echo $view->render([
      'id'        => $this->id,
      'textareas' => $textareas
    ]);
  }

  # date
  private function date()
  {
    $view = new View('fields/date');

    echo $view->render([
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # time
  private function date_time()
  {
    $value = $this->getFormattedDate($this->value, 'H:i:s');
    $view  = new View('fields/date_time');

    echo $view->render([
      'value'      => $value,
      'properties' => $this->properties,
    ]);
  }

  # datetimelocal
  private function date_timelocal()
  {
    $value = $this->getFormattedDate($this->value, 'Y-m-d\TH:i');
    $view  = new View('fields/date_timelocal');

    echo $view->render([
      'value'      => $value,
      'properties' => $this->properties,
    ]);
  }

  # week
  private function date_week()
  {
    $value = $this->getFormattedDate($this->value, 'Y-\WW');
    $view  = new View('fields/date_week');

    echo $view->render([
      'value'      => $value,
      'properties' => $this->properties,
    ]);
  }

  # month
  private function date_month()
  {
    $value = $this->getFormattedDate($this->value, 'Y-m');
    $view  = new View('fields/date_month');

    echo $view->render([
      'value'      => $value,
      'properties' => $this->properties,
    ]);
  }

  /**
   * @param mixed $date
   * @param string $format
   * @return string
   */
  private function getFormattedDate($date, $format)
  {
    if (empty($date)) {
      $date = time();
    }

    if (is_numeric($date)) {
      $timestamp = $date;
    } else {
      $timestamp = strtotime($date);
    }

    return date($format, $timestamp);
  }

  /* textareas */
  # textarea
  private function textarea()
  {
    $view = new View('fields/textarea');

    echo $view->render([
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # tags
  private function textarea_tags()
  {
    $view = new View('fields/textarea_tags');

    echo $view->render([
      'id'         => $this->id,
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # bbcode
  private function textarea_bbcode()
  {
    $view = new View('fields/textarea_bbcode');

    echo $view->render([
      'id'         => $this->id,
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # wiki
  private function textarea_wiki()
  {
    $view = new View('fields/textarea_wiki');

    echo $view->render([
      'id'         => $this->id,
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # markdown
  private function textarea_markdown()
  {
    $view = new View('fields/textarea_markdown');

    echo $view->render([
      'id'         => $this->id,
      'value'      => $this->value,
      'properties' => $this->properties,
    ]);
  }

  # wysiwyg
  private function textarea_wysiwyg()
  {
    $view = new View('fields/textarea_wysiwyg');

    echo $view->render([
      'id'          => $this->id,
      'value'       => $this->value,
      'properties'  => $this->properties,
      'height'      => $this->schema['height'],
      'placeholder' => json_encode($this->schema['placeholder']),
    ]);
  }

  # code editor
  private function textarea_code()
  {
    $iframeCssPath     = Plugin::getFullPathToFile('js/edit_area/style.css');
    $iframeCssContents = file_get_contents($iframeCssPath);
    $blankSpaces       = ["\r\n", "\r", "\n"];
    $iframeCss         = str_replace($blankSpaces, '', $iframeCssContents);
    $view              = new View('fields/textarea_code');

    echo $view->render([
      'id'          => $this->id,
      'value'       => $this->value,
      'properties'  => $this->properties,
      'iframeCss'   => $iframeCss,
    ]);
  }

  # dropdown
  private function dropdown() {
    $options = $this->matrix->getOptions($this->schema['options']);
    ?>
      <select class="text" <?php echo $this->properties; ?>>
        <?php foreach ($options as $key => $option) { ?>
          <option value="<?php echo $key; ?>" <?php if ($key == $this->value) echo 'selected="selected"'; ?> ><?php echo $option; ?></option>
        <?php } ?>
      </select>
    <?php
  }

  # dropdown for tables
  private function dropdown_table() {
    if ($this->matrix->fieldExists($this->schema['table'], $this->schema['row'])) {
      $fields = 'id' . (($this->schema['row'] != 'id') ? ', '.$this->schema['row'] : '');
      $query = $this->matrix->query('SELECT '.$fields.' FROM '.$this->schema['table'].' ORDER BY id ASC');
    }
    else $query = array();
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($query as $record) { ?>
        <option value="<?php echo $record['id']; ?>" <?php if ($record['id'] == $this->value) echo 'selected="selected"'; ?> ><?php echo $record[$this->schema['row']]; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # dropdown with hierarchy
  private function dropdown_hierarchy() {
    $options = $this->matrix->getHierarcalOptions($this->schema['options']);
    ?>
      <select class="text" <?php echo $this->properties; ?>>
        <?php foreach ($options as $option) { ?>
          <option value="<?php echo $option['value']; ?>" <?php if ($option['value'] == $this->value) echo 'selected="selected"'; ?> ><?php echo $option['option']; ?></option>
        <?php } ?>
      </select>
    <?php
  }

  # pages
  private function dropdown_pages() {
    getPagesXmlValues();
    global $pagesArray;
    $pages = $pagesArray;
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($pages as $slug => $properties) { ?>
        <option value="<?php echo $slug; ?>" <?php if ($slug == $this->value) echo 'selected="selected"'; ?> ><?php echo $properties['title']; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # users
  private function dropdown_users() {
    $users = $this->matrix->getUsers();
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($users as $user => $details) { ?>
        <option value="<?php echo $user; ?>" <?php if ($user == $this->value || (empty($this->value) && $user == $_COOKIE['GS_ADMIN_USERNAME'])) echo 'selected="selected"'; ?> ><?php if (empty($details['NAME'])) echo $user; else echo $details['NAME']; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # components
  private function dropdown_components() {
    $components = $this->matrix->getComponents();
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($components as $slug => $component) { ?>
        <option value="<?php echo $slug; ?>" <?php if ($slug == $this->value) echo 'selected="selected"'; ?> ><?php echo $component['title']; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # template
  private function dropdown_template() {
    // load templates for current theme
    $templates = glob(GSTHEMESPATH.$this->paths['template'].'/*.php');

    // unset 'functions.php' and '*.inc.php'
    foreach ($templates as $key => $template) {
      $tmp = explode('/', $template);
      $templates[$key] = $template = end($tmp);
      if (
        strtolower($template) == 'functions.php' ||
        substr($template, -7, 7) == 'inc.php'
      ) {
        unset($templates[$key]);
      }
    }
    sort($templates);
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($templates as $template) { ?>
        <option value="<?php echo $template; ?>" <?php if ($template == $this->value) echo 'selected="selected"'; ?> ><?php echo $template; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # themes
  private function dropdown_themes() {
    $themes = $this->matrix->getThemes();
    ?>
    <select class="text" <?php echo $this->properties; ?>>
      <?php foreach ($themes as $theme) { ?>
        <option value="<?php echo $theme; ?>" <?php if ($theme == $this->value || (empty($this->value)) && $theme == $this->paths['template']) echo 'selected="selected"'; ?> ><?php echo $theme; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # picker
  private function picker() {
  }
  # upload
  private function upload() {
  }

  # radio
  private function radio() {
    $selected = $this->matrix->getOptions($this->value);
    $options = $this->matrix->getOptions($this->schema['options']);
    ?>
    <span class="radio">
    <?php foreach ($options as $key => $option) { ?>
      <input type="radio" <?php echo $this->properties; ?> name="<?php echo $this->id; ?>[<?php echo $key; ?>]" <?php if (in_array($option, $selected)) echo 'checked="checked"'; ?> class="input"/> <span class="option"><?php echo $option; ?></span><br />
    <?php } ?>
    </div>
    <?php
  }

  # checkbox
  private function options_checkbox() {
    // force value to be an array
    if (!is_array($this->value)) $this->value = array($this->value);

    // load options and values
    $selected = array_map('trim', $this->value);
    $options = $this->matrix->getOptions($this->schema['options']);
    ?>
    <span class="checkbox">
    <?php foreach ($options as $key => $option) { ?>
      <input type="checkbox" class="input" name="<?php echo $this->id; ?>[]" value="<?php echo $key; ?>" <?php echo $this->properties; ?> <?php if (in_array($key, $selected)) echo 'checked="checked"'; ?>/> <span class="option"><?php echo $option; ?></span><br />
    <?php } ?>
    </span>
    <?php
  }

  # radio
  private function options_radio() {
    $selected = $this->matrix->getOptions($this->value);
    $options = $this->matrix->getOptions($this->schema['options']);
    ?>
    <span class="radio">
    <?php foreach ($options as $key => $option) { ?>
      <input type="radio" class="input" <?php echo $this->properties; ?> value="<?php echo $key; ?>" <?php if ($key == $this->value) echo 'checked="checked"'; ?>/> <span class="option"><?php echo $option; ?></span><br />
    <?php } ?>
    </span>
    <?php
  }

  # multiple select
  private function options_selectmulti() {
    // force value to be an array
    if (!is_array($this->value)) $this->value = array($this->value);

    // load options and values
    $selected = array_map('trim', $this->value);
    $options = $this->matrix->getOptions($this->schema['options']);
    ?>
    <select class="text" name="<?php echo $this->id; ?>[]" <?php echo $this->properties; ?> multiple>
      <?php foreach ($options as $key => $option) { ?>
        <option value="<?php echo $key; ?>"<?php if ($key == $this->value) echo 'selected="selected"'; ?> <?php if (in_array($key, $selected)) echo 'selected="selected"'; ?>><?php echo $option; ?></option>
      <?php } ?>
    </select>
    <?php
  }

  # upload image (for admins)
  private function upload_imageadmin() {
    ?>
    <input type="file" class="text imageuploadadmin DM_imageuploadadmin" style="margin: 0 0 10px 0 !important;" name="post-<?php echo $this->name; ?>" disabled/>
    <select class="text imageuploadadmin DM_imageuploadadmin " <?php echo $this->properties; ?>>
      <option value="">--no file--</option>
      <option value="upload">--upload--</option>
      <?php
        $images = glob(GSDATAUPLOADPATH.$this->schema['path'].'*.*');
        $thumbs = glob(GSTHUMBNAILPATH.$this->schema['path'].'*.*');
        foreach ($images as $image) {
          $tmp = explode('/', $image);
          $file = end($tmp);
      ?>
      <option value="<?php echo $file; ?>" <?php if ($file == $this->value) echo 'selected="selected"'; ?>><?php echo $file; ?></option>
      <?php } ?>
    </select>
    <script>
      $(document).ready(function(){
        $('input.imageuploadadmin').hide();
        $('select.imageuploadadmin').change(function(){
          if ($(this).val() == 'upload') {
            $(this).prev('input.imageuploadadmin').slideDown().prop('disabled', false);
          }
          else {
            $(this).prev('input.imageuploadadmin').slideUp().prop('disabled', true);
          }
        }); // change
      }); // ready
    </script>
    <?php
  }

  # image picker
  private function picker_image()
  {
    $properties = $this->properties;
    $name       = $this->name;
    $url        = $this->matrix->getSiteURL() . 'admin/filebrowser.php?CKEditorFuncNum=1&func=addImageThumbNail&returnid=post-' . $this->name . 'type=images';
    $view       = new View('fields/picker_image');

    echo $view->render([
      'properties' => $properties,
      'name'       => $name,
      'url'        => $url,
    ]);
  }

  # file picker
  private function picker_file()
  {
    $properties = $this->properties;
    $name       = $this->name;
    $url        = $this->matrix->getSiteURL().'admin/filebrowser.php?CKEditorFuncNum=1&returnid=post-' . $this->name . 'type=all';
    $view       = new View('fields/picker_file');

    echo $view->render([
      'properties' => $properties,
      'name'       => $name,
      'url'        => $url,
    ]);
  }

  # display
  public function display($params = []) {
    // description
    if (!empty($this->schema['desc'])) {
      ?><span class="description"><?php echo $this->schema['desc']; ?></span><?php
    }
    // field
    if (method_exists(get_class($this), $this->method)) {
      $method = $this->method;
    }
    elseif (method_exists(get_class($this), $this->type)) {
      $method = $this->type;
    }
    else $method = 'input';
    return call_user_func_array(array($this, $method), $params);
  }
}