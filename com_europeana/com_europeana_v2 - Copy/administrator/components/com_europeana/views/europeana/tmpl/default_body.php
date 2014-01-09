<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.filesystem.file');
?>
<?php foreach($this->files as $k => $file): ?>
    <tr class="row<?php echo $k % 2; ?>">
        <td with="1%">
            <?php echo JHtml::_('grid.id', $k, $file->id); ?>
        </td>
        <td width="1%">
            <?php echo $file->id; ?>
        </td>
        <td style="text-align:left;">
            <?php echo $file->name; ?>
        </td>
        <td width="10%" style="text-align:center;">
            <?php echo $file->username; ?>
        </td>
        <td width="20%" style="text-align:center;">
            <?php if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'com_europeana'.DS.'xml-files'.DS.$file->filename)) : ?>
            <a class="fileDownload" href="<?php echo JURI::root() . 'media/com_europeana/xml-files/'.$file->filename; ?>" title="<?php echo JText::_('COM_EUROPEANA_DOWNLOAD') . ' ' . $file->filename; ?>" download>
                <?php echo $file->filename; ?>
            </a>
            <?php else: ?>
                <?php echo $file->filename; ?>
            <?php endif; ?>
        </td>
        <td width="15%" style="text-align:center;">
            <?php echo $file->datetime; ?>
        </td>
    </tr>
<?php endforeach; ?>