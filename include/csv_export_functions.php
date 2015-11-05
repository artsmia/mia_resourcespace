<?php
/**
 * Functions used (mostly) to generate the content needed for CSV files
 */

/**
* Generates the CSV content of the metadata for resources passed in the array
*
* @param $resources
* @return string
*/
function generateResourcesMetadataCSV(array $resources)
{
    global $lang;

    $return                 = '';
    $csv_field_headers      = array();
    $resources_fields_data  = array();

    foreach($resources as $resource)
        {
        foreach(get_resource_field_data($resource['ref'], false, true, -1, getval("k","")!="") as $field_data)
            {
            if($field_data['name'] == '')
                {
                die('Please check field ID ' . $field_data['ref'] . ' and make sure its "' . $lang['property-shorthand_name'] . '" is set!');
                }
            $csv_field_headers[$field_data['name']] = $field_data['title'];

            $resources_fields_data[$resource['ref']][$field_data['name']] = $field_data['value'];
            }
        }
    $csv_field_headers = array_unique($csv_field_headers);

    // Header
    $return = '"' . $lang['resourceids'] . '","' . implode('","', $csv_field_headers) . "\"\n";

    // Results
    $csv_row = '';
    foreach($resources_fields_data as $resource_id => $resource_fields)
        {
        // First column will always be Resource ID
        $csv_row = $resource_id . ',';

        // Field values
        foreach($csv_field_headers as $column_header => $column_header_title)
            {
            if(!array_key_exists($column_header, $resource_fields))
                {
                $csv_row .= '"",';
                continue;
                }

            foreach($resource_fields as $field_name => $field_value)
                {
                if($column_header == $field_name)
                    {
                    $csv_row .= '"' . tidylist(i18n_get_translated($field_value)) . '",';
                    }
                }
            }
        $csv_row .= "\n";
        $return  .= $csv_row;
        }
   
    return $return;
}