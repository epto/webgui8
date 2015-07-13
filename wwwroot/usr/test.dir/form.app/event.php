<?

function form_test_post($data,$formData) {
        global $WGData;
        global $WEBGUI;
        
        setCaption('debug',print_r($formData,true));
        return $data;
	}

?>
