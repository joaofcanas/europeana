<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->files as $k => $file): ?>
    <tr class="row<?php echo $k % 2; ?>">
        <td>
            <?php echo $file->id; ?>
        </td>
        <td>
            <?php echo $file->name; ?>
        </td>
        <td>
            <?php echo $file->username; ?>
        </td>
        <td>
            <?php echo $file->filename; ?>
        </td>
        <td>
            <?php echo $file->datetime; ?>
        </td>
    </tr>
<?php endforeach; ?>