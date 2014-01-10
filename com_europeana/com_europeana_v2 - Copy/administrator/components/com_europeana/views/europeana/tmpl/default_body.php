<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.filesystem.file');
$filter = Jrequest::getVar('filter','0');
?>
<?php 
foreach($this->files as $k => $file): 
    $fileExists = JFile::exists(JPATH_ROOT.DS.'media'.DS.'com_europeana'.DS.'xml-files'.DS.$file->filename);
    $fileLogs = json_decode($file->logs);
?>
	
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
        <td width="20%" style="text-align:center;">
            <?php if ($fileExists && !$filter) : ?>
            <a class="fileDownload" href="<?php echo JURI::root() . 'media/com_europeana/xml-files/'.$file->filename; ?>" title="<?php echo JText::_('COM_EUROPEANA_DOWNLOAD') . ' ' . $file->filename; ?>" download>
                <?php echo $file->filename; ?>
            </a>
            <?php else: ?>
                <?php echo $file->filename; ?>
            <?php endif; ?>
        </td>
        <td width="10%" style="text-align:center;">
            <?php echo $file->username; ?>
        </td>
        <td width="15%" style="text-align:center;">
            <?php echo $file->datetime; ?>
        </td>
        <td width="10%" style="text-align:center;">
            <?php if($fileLogs) : ?>
                <span title='<?php echo JText::_('COM_EUROPEANA_IDS_EXPORTED')." ".implode(';',$fileLogs->itemsId); ?>'><?php echo $fileLogs->numOfItemsExported; ?></span>
            <?php else : ?>
                <span>-</span>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>