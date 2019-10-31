<?php
class XSLExtensionHooks {
   // Register any render callbacks with the parser
   public static function onParserFirstCallInit( Parser $parser ) {
      $parser->setFunctionHook( 'xsl', [ self::class, 'xslRender' ] );
      return true;
   }

   public static function xslRender( Parser $parser, $xsl, $xml, $parse=true, $nocache=false ) {
      
		if ($nocache) {
			$parser->disableCache();
		}

		$output = XSLExtensionHooks::xslTransform( $xsl, $xml );
		
		if ($parse == false) {
			return array($output, 'noparse' => true, 'isHTML' => true);
		}

		return $output;
		// to return the contents inline
		// return $parser->insertStripItem( $output, $parser->mStripState );

   }

	public static function xslTransform( $xsl_path, $xml_path ) {
		$doc = new DOMDocument();
		$xsl = new XSLTProcessor();

		$doc->load($xsl_path);
		$xsl->importStyleSheet($doc);

		$doc->load($xml_path);
		return $xsl->transformToXML($doc);
	}

}