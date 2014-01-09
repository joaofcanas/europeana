<?php
defined('_JEXEC') or die('Access denied');

jimport('joomla.application.component.controller');

class EuropeanaControllerEuropeana extends JController {
    
    /**
    * Setting the toolbar
    */
    protected function addToolBar() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root() . 'media/com_europeana/css/backend-com_europeana.css');
        $doc->addScript('media/com_europeana/js/jquery-1.10.2.min.js');
        $doc->addScript(JURI::root() . 'media/com_europeana/js/backend-scripts-com_europeana.js');
        
        JToolBarHelper::title('COM_EUROPEANA_FILE_LIST','download-icon-48x48.png');
//      JToolBarHelper::title(JText::_('COM_EUROPEANA_FILE_LIST'));
        JToolBarHelper::back();
    }
    
    function export(){
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');        
        
        $this->addToolBar();
		
        echo '<h3>';
        echo JText::_('COM_EUROPEANA_TASK_EXPORT');
        echo '</h3>';
        
        $user = JFactory::getUser();
        $date = JFactory::getDate();
        
        $relatedObjects = $this->prepareData();
		
        $fileContent = null;
        
        $fileName = $date->toFormat('%Y%m%d_%H%M%S');
        $fileName .= '_' . $user->username . '.xml';
        
        $fileNameWithPath = JPATH_ROOT.DS.'media'.DS.'com_europeana'.DS.'xml-files'.DS.$fileName;
		
        if (count($relatedObjects) > 0)
            $fileContent = $this->generatexml($relatedObjects);

        if ($fileContent != null || $fileContent != '')
        {
            // If the destination directory doesn't exist we need to create it
            if (!JFile::exists($fileNameWithPath))
            {
                //JFile::move($fileNameWithPath,$fileNameWithPath);
                if (JFile::write($fileNameWithPath, $fileContent, false))
                {
                    echo '<h3>' . JText::_('COM_EUROPEANA_FILE_WRITE_SUCCESS') . '</h3>';
                    
                    $path = JURI::root() . 'media'. DS . 'com_europeana' . DS . 'xml-files'. DS. JFile::getName($fileNameWithPath);
                    $file = JFile::getName($fileNameWithPath);
                    echo '<div>';
                    echo '<a class="btn" href="' . $path . '" download>' . JText::_('K2_DOWNLOAD') . ' ' . $file . '</a>';
                    echo '<div>';
                    
                    $this->prepareDataToDatabase();
                    $this->storeIntoDatabase($fileName);
                }
            }
        }
        else
        {
            echo '<h3>' . JText::_('COM_EUROPEANA_NO_RECORDS_FOUND_TO_EXPORT') .'</h3>';
            echo '<a href="' . JRoute::_(JURI::base() . 'index.php?option=com_europeana') . '">' . JText::_('BACK') .'</a>';
        }        
    }
    
    private function prepareDataToDatabase() {
        //require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'JSON.php');
        require_once (JPATH_ROOT.DS.'media'.DS.'com_europeana'.DS.'lib'.DS.'JSON.php');

        $json = new Services_JSON;

        $relatedObjects = $this->prepareData();
        $items = array();

        foreach($relatedObjects as $key => $object)
        {
            foreach($object->extra_fields as $k => $extraField)
            {
                unset($relatedObjects[$key]->extra_fields[$k]->name);
            }

            foreach($object->extra_fields as $k => $extraField)
            {
                if ($extraField->id == "56" || $extraField->id == 56) 
                {
                    $relatedObjects[$key]->extra_fields[$k]->value = "2";
                }
            }

            array_push($items,array('id' => $relatedObjects[$key]->id, 'extra_fields' => $relatedObjects[$key]->extra_fields));
        }

        foreach($items as $key => $item)
        {
            $items[$key]['extra_fields'] = $json->encode($item['extra_fields']);
        }

        $this->updateData($items);

    }
	
    private function updateData($items = null) {
        if ($items != null)
        {

            foreach($items as $key => $item)
            {
                // Create a new query object.
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);

                // Fields to update.
                $fields = array(
                    $db->quoteName('extra_fields') . " = " . $db->Quote($item['extra_fields'])
                );

                // Conditions for which records should be updated.
                $conditions = array(
                    $db->quoteName('id') . ' = '. $item['id'] 
                );

                $query->update($db->quoteName('#__k2_items'))->set($fields)->where($conditions);

                $db->setQuery($query);

                $result = $db->query();
                
            }
        }
    }
	
    private function prepareData() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('id,title,catid,id_cota,extra_fields,plugins,language');
        // From the hello table
        $query->from('#__k2_items');
        // Where Clause
        $query->where($db->quoteName('catid') . ' = 49 AND ' . $db->quoteName('published') . ' = 1');
        $query->where(
            array(
                'catid = 49',
                'published = 1',
                'trash = 0'
        ));
        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();
        $extraFieldModel = K2Model::getInstance('ExtraField', 'K2Model');
        $extraFieldsArray = $extraFieldModel->getExtraFieldsByGroup(3);
        $cid = JRequest::setVar('cid',$results[0]->id);
        $k2ItemModel = K2Model::getInstance('Item','K2Model');
        //$item = $k2ItemModel->getData();
        $extraFields = json_decode($results[0]->extra_fields);

        // echo '<div><pre>';
        // print_r($extraFieldsArray);
        // echo '</pre></div>';

        $d = array();

        $relatedObjects = array();

        foreach ($results as $k => $result)
        {
            $extraFields = json_decode($result->extra_fields);
            $result->extra_fields = $extraFields;
            $plugins = json_decode($result->plugins);
            $result->plugins = $plugins;

            foreach($result->extra_fields as $key => $extraField)
            {
                foreach($extraFieldsArray as $field)
                {
                    if ($extraField->id == $field->id)
                    {
                        switch ($field->type)
                        {
                            case 'select':
                                $values = json_decode($field->value);
                                foreach($values as $i => $value)
                                {
                                    if ($value->value == $extraField->value)
                                    {
                                        $extraField->value = $value->name;
                                    }
                                }
                                $result->extra_fields[$key]->name = $field->name;
                                break;
                            default:
                                $result->extra_fields[$key]->name = $field->name;
                                break;
                        }

                    }

                }

                if ($extraField->id == 35 && $extraField->value == 1)
                {
                    array_push($relatedObjects,$result);
                }
            }

            $imagePath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'src' . DS;
            $imageFileName = md5("Image" . $result->id) . "jpg";
            if (file_exists(dirname($imagePath.$imageFileName)))
            {
                $results[$k]->imageLink = JURI::root().'media'.DS.'k2'.DS.'items'.DS.'cache' . DS . md5("Image" . $result->id) . "_L.jpg";
            }
        }

        // echo '<div><pre>';
        // print_r($relatedObjects);
        // echo '</pre></div>';

        foreach ($relatedObjects as $key => $object)
        {
            foreach($object->extra_fields as $k => $extraField)
            {
                if ($extraField->id == 56 && $extraField->value != 1)
                {
                    unset($relatedObjects[$key]);
                }
            }
        }

        foreach($relatedObjects as $key => $object)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__k2_tags_xref');
            $query->where($db->quoteName('itemID') . ' = ' . $object->id);
            $db->setQuery($query);
            $tags = $db->loadObjectList();

            // echo '<div><pre>';
            // print_r($tags);
            // echo '</pre></div>';

            $objectTags = array();

            if(count($tags) > 0) :

            foreach($tags as $tag)
            {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__k2_tags');
                $query->where($db->quoteName('id') . ' = ' . $tag->tagID . ' AND ' . $db->quoteName('published') . ' = 1');
                $db->setQuery($query);
                $result = $db->loadObject();

                // echo '<div><pre>';
                // print_r($result);
                // echo '</pre></div>';

                @array_push($objectTags,$result);
            }

            $relatedObjects[$key]->tags = $objectTags;
            $objectTags = '';
            endif;
        }

        return $relatedObjects;
    }

    private function generatexml($relatedObjects) {

        //$relatedObjects = self::prepareData();

        if (count($relatedObjects) > 0)
        {
            $xml = new DOMDocument("1.0","UTF-8");
            $xmlMetadata = $xml->createElementNS("http://www.europeana.eu/schemas/ese/","metadata");
            $xml->appendChild($xmlMetadata);
            $xmlMetadata->setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:europeana","http://www.europeana.eu/schemas/ese/ESE-V3.4.xsd");
            $xmlMetadata->setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:dc","http://purl.org/dc/elements/1.1/");
            $xmlMetadata->setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:dcterms","http://purl.org/dc/terms/");
            $xmlMetadata->setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:edm","http://www.europeana.eu/schemas/edm/");

            // record
            $xmlRecord;
            // dc:title
            $xmlDCTitle;
            // dc:contributor
            $xmlDCContributor;
            // dc:creator
            $xmlDCCreator;
            // dc:subject
            $xmlDCSubject;
            // dc:description
            $xmlDCDescription;
            // dc:temporal
            $xmlDCTermsTemporal;
            // dcterms:spatial
            $xmlDCTermsSpatial;
            // dcterms:tableOfContens
            $xmlDCTermsTableOfContents;
            // dc:language
            $$xmlDCLanguage = $xml->createElement("dc:language",'pt');
            // dc:type
            $xmlDCType;
            // dc:identifier
            $xmlDCIdentifier;
            // europeana:provider
            $xmlEuropeanaProvider;
            // europeana:type
            $xmlEuropeanaType;
            // europeana:rights
            $xmlEuropeanaRights;
            // europeana:dataProvider
            $xmlEuropeanaDataProvider = $xml->createElement("europeana:dataProvider","Instituto de Historia Contemporanea da Faculdade de Ciencias Sociais e Humanas da Universidade Nova Lisboa – Portugal");;
            // europeana:isShownAt
            $xmlEuropeanaIsShownAt;
            // europeana:isShownBy
            $xmlEuropeanaIsShownBy;
            // edm:type
            $xmlEDMType;
            // edm:begin
            $xmlEDMBegin;
            // edm:end
            $xmlEDMEnd;
            // Latitude
            $xmlWSG84POSLat;
            //Longitude
            $xmlWSG84POSLng;

            foreach($relatedObjects as $key => $object)
            {
                // main element - record
                $xmlRecord = $xml->createElement("record");
                $xmlRecord->setAttribute("id", "PT1914/MEM/OBJ/" . $object->id);

                // dc:title
                $xmlDCTitle = $xml->createElement("dc:title",$object->title);
                $xmlDCTitle->setAttribute("xml:lang","pt");

                // dc:identifier
                $xmlDCIdentifier = $xml->createElement("dc:identifier","PT1914/MEM/OBJ/" . $object->id);

                // europeana:isShownBy
                // europeana:type
                if($object->imageLink != '')
                {
                    $xmlEDMType = $xml->createElement("europeana:type","IMAGE");
                    //$xmlRecord->appendChild($xmlEDMType);
                    $xmlEuropeanaIsShownBy = $xml->createElement("europeana:isShownBy",$object->imageLink);
                }
                else
                {
                    $xmlEDMType = $xml->createElement("europeana:type","TEXT");
                    //$xmlRecord->appendChild($xmlEDMType);
                }

                foreach($object->extra_fields as $k => $extraField)
                {
                    $regiao = '';
                    $local = '';
                    $dataInicio = '';
                    $datafim = '';

                    // dc:contributor
                    //if($extraField->name == "Contribuinte" && $extraField->value != '')
                    if($extraField->id == 173 && $extraField->value != '')
                    {
                        $xmlDCContributor = $xml->createElement("dc:contributor",$extraField->value);
                        //$xmlRecord->appendChild($xmlDCContributor);
                    }

                    // dc:creator
                    //if($extraField->name == "Criador do conteúdo" && $extraField->value != '')
                    if($extraField->id == 171 && $extraField->value != '')
                    {
                        $xmlDCCreator = $xml->createElement("dc:creator",$extraField->value);
                        //$xmlRecord->appendChild($xmlDCCreator);
                    }

                    // dc.description
                    //if($extraField->name == "Descrição do objecto" && $extraField->value != '')
                    if($extraField->id == 49 && $extraField->value != '')
                    {
                        $xmlDCDescription = $xml->createElement("dc:description",$extraField->value);
                        $xmlDCDescription->setAttribute("xml:lang","pt");
                        //$xmlRecord->appendChild($xmlDCDescription);
                    }

                    // dcterms:temporal OR edm:begin
                    //if ($extraField->name == "Data início" && $extraField->value != '')
                    if ($extraField->id == 169 && $extraField->value != '')
                    {
                        $xmlEDMBegin = $xml->createElement("edm:begin",$extraField->value);
                        //$xmlRecord->appendChild($xmlEDMBegin);
                        $dataInicio = $extraField->value;
                    }

                    // dcterms:temporal OR edm:end
                    //if ($extraField->name == "Data fim" && $extraField->value != '')
                    if ($extraField->id == 170 && $extraField->value != '')
                    {
                        $xmlEDMEnd = $xml->createElement("edm:end",$extraField->value);
                        //$xmlRecord->appendChild($xmlEDMEnd);
                        $dataFim = $extraField->value;
                    }

                    // dcterms:spatial
                    //if($extraField->name == "Região" && $extraField->value != '')
                    if($extraField->id == 182 && $extraField->value != '')
                    {
                        $regiao = $extraField;
                    }

                    //if($extraField->name == "Local" && $extraField->value != '')
                    if($extraField->id == 168 && $extraField->value != '')
                    {
                        $local = $extraField;
                    }

                    // dcterms:tableOfContents
                    // TODO

                    // dc:language
                    //if ($extraField->name == "Idioma" && $extraField->value != '')
                    if ($extraField->id == 167 && $extraField->value != '')
                    {
                        switch ($extraField->value)
                        {
                            default:
                                $xmlDCLanguage = $xml->createElement("dc:language",$object->language);
                                break;
                        }
                    }
                    else
                    {
                        $xmlDCLanguage = $xml->createElement("dc:language",substr($object->language, 0, 2));
                    }

                    // dc:type
                    //if($extraField->name == "Tipo de objecto" && $extraField->value != '')
                    if($extraField->id == 29 && $extraField->value != '')
                    {
                        $xmlDCType = $xml->createElement("dc:type",$extraField->value);
                        $xmlDCType->setAttribute("xml:lang","pt");
                        //$xmlRecord->appendChild($xmlDCType);
                    }

                    // dc:identifier - está em cima

                    // europeana:provider
                    //if($extraField->name == "Entidade detentora de direitos" && $extraField->value != '')
                    if($extraField->id == 160 && $extraField->value != '')
                    {
                        $xmlEuropeanaProvider = $xml->createElement("europeana:provider",$extraField->value);
                    }

                    // europeana:type - está em cima

                    // europeana:rights
                    //if($extraField->name == "Tipo de direitos" && $extraField->value != '')
                    if($extraField->id == 159 && $extraField->value != '')
                    {
                        switch ($extraField->value)
                        {
                            case 'Atribuição-NãoComercial 3.0 Portugal (CC BY-NC 3.0 PT)':
                                $xmlEuropeanaRights = $xml->createElement("europeana:rights","http://creativecommons.org/licenses/by-nc/3.0/pt/");
                                break;
                            default :
                                $xmlEuropeanaRights = $xml->createElement("europeana:rights","http://creativecommons.org/licenses/by-nc/3.0/pt/");
                                break;
                        }

                        //$xmlRecord->appendChild($xmlEuropeanaRights);
                    }

                    // europeana:dataProvider
                    //if($extraField->name == "Proprietário" && $extraField->value != '')
                    if($extraField->id == 172 && $extraField->value != '')
                    {
                        $xmlEuropeanaDataProvider = $xml->createElement("europeana:dataProvider","Instituto de Historia Contemporanea da Faculdade de Ciencias Sociais e Humanas da Universidade Nova Lisboa – Portugal");
                    }

                    // europeana:isShownAt
                    //if($extraField->name == "Link para o objecto original" && $extraField->value[1] != '')
                    if($extraField->id == 52 && $extraField->value[1] != '')
                    {
                        $xmlEuropeanaIsShownAt = $xml->createElement("europeana:isShownAt",$extraField->value[1]);
                        //$xmlRecord->appendChild($xmlEuropeanaIsShownAt);
                    }
                    else
                    {
                        $xmlEuropeanaIsShownAt = $xml->createElement("europeana:isShownAt", JURI::root() . 'item' . DS . $object->id);
                    }


                }

                // dcterms:spatial - final
                if (($regiao != '') || ($local != ''))
                {
                    $val = '';
                    if ($regiao) $val = $regiao;
                    if ($local) $val .= '-'.$local;
                    $xmlDCTermsSpatial = $xml->createElement("dcterms:spatial",$val);
                    $xmlDCTermsSpatial->setAttribute("xml:lang","pt");
                }


                //if (count($object->tags > 1))
				if(is_array($object->tags))
                {
                    $xmlDCSubject = array();
                    foreach($object->tags as $tag)
                    {
                        $xmlObj = $xml->createElement("dc:subject",$tag->name);
                        $xmlObj->setAttribute("xml:lang","pt");
                        @array_push($xmlDCSubject,$xmlObj);
                    }
                }
                else
                {
                    $xmlDCSubject = $xml->createElement("dc:subject",$object->tags[0]->name);
                    $xmlDCSubject->setAttribute("xml:lang","pt");
                }


                if ($xmlDCTitle != null) $xmlRecord->appendChild($xmlDCTitle);
                if ($xmlDCContributor != null) $xmlRecord->appendChild($xmlDCContributor);
                if ($xmlDCCreator != null) $xmlRecord->appendChild($xmlDCCreator);

                if ($xmlDCSubject != null)
                {
                    if(is_array($xmlDCSubject))
                    {	
                        foreach($xmlDCSubject as $dcSubject)
                        {
                            $xmlRecord->appendChild($dcSubject);
                        }
                    }
                    else
                    {
                        $xmlRecord->appendChild($xmlDCSubject);
                    }
                }
                if ($xmlDCDescription != null) $xmlRecord->appendChild($xmlDCDescription);
                if ($xmlDCTermsTemporal != null) $xmlRecord->appendChild($xmlDCTermsTemporal);
                if ($xmlDCTermsSpatial != null) $xmlRecord->appendChild($xmlDCTermsSpatial);
                if ($xmlDCTermsTableOfContents != null) $xmlRecord->appendChild($xmlDCTermsTableOfContents);
                if ($xmlDCLanguage != null) $xmlRecord->appendChild($xmlDCLanguage);
                if ($xmlDCType != null) $xmlRecord->appendChild($xmlDCType);
                if ($xmlDCIdentifier != null) $xmlRecord->appendChild($xmlDCIdentifier);
                if ($xmlEuropeanaProvider != null) $xmlRecord->appendChild($xmlEuropeanaProvider);
                if ($xmlEuropeanaType != null) $xmlRecord->appendChild($xmlEuropeanaType);
                if ($xmlEuropeanaRights != null) $xmlRecord->appendChild($xmlEuropeanaRights);
                if ($xmlEuropeanaDataProvider != null) $xmlRecord->appendChild($xmlEuropeanaDataProvider);
                if ($xmlEuropeanaIsShownAt != null) $xmlRecord->appendChild($xmlEuropeanaIsShownAt);
                if ($xmlEuropeanaIsShownBy != null) $xmlRecord->appendChild($xmlEuropeanaIsShownBy);
                if ($xmlEDMType != null) $xmlRecord->appendChild($xmlEDMType);
                if ($xmlEDMBegin != null) $xmlRecord->appendChild($xmlEDMBegin);
                if ($xmlEDMEnd != null) $xmlRecord->appendChild($xmlEDMEnd);
                if ($xmlWSG84POSLat != null) $xmlRecord->appendChild($xmlWSG84POSLat);
                if ($xmlWSG84POSLng != null) $xmlRecord->appendChild($xmlWSG84POSLng);
                $xmlMetadata->appendChild( $xmlRecord );
            }

            // Parse the XML.
            $xml->normalizeDocument();
            $fileContent = $xml->saveXML();

            return $fileContent;
        }
        else
        {
            return null;
        }
    }
    
    private function storeIntoDatabase($filename) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        //$filename .= '_' . $user->username . '.xml';
        
        $query = "INSERT INTO #__europeana_files (`user_id`,`filename`) VALUES ('".$user->id."','". $filename ."')";
        
        $db->setQuery($query);
        $db->query();
        
    }
}