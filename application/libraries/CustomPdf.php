<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once dirname(__FILE__) . '/mpdf/mpdf.php';
 
class CustomPdf extends mPDF
{
    private $fileName = null;
    private $rutaServer = null;

    function __construct()
    {
        parent::mPDF();
    }

    /**
     * Agregar MetaDataAdicional
     * 
     * @param array? $addMetaTag
     * @return null
    */
    public function addXMP($addMetaTag = [])
    {
        $xmp = "";
        foreach ($addMetaTag as $key => $value) {
            $xmp .= "<" . $key . ">" . $value . "</" . $key . ">";
        }
        $this->custom_xmp = $xmp;
    }

    /**
	 * Set additional XMP data to be added on the default XMP data just before the end of "x:xmpmeta" tag.
	 * IMPORTANT: This data is added as-is without controls, so you have to validate your data before using this method!
	 * @param $xmp (string) Custom XMP data.
	 * @since 5.9.128 (2011-10-06)
	 * @public
	 */
	public function setExtraXMP($xmp) {
		$this->custom_xmp = $xmp;
	}

    /**
     * Define la carpeta donde se guardara el pdf
     * 
     * @param string $path
     * @return null
     */
    public function setPathServer($path)
    {
        $this->rutaServer = $path;
    }

    /**
     * Metódo que permite asignar el nombre del archivo
     * 
     * @param string $name
     * @return null
     */
    public function setFileName($name)
    {
        $this->fileName = $name;
        $this->options['Content-Disposition'] = $name;
    }

    /**
     * Generamos el stream de salida y guardamos
     * 
     * @return string
     */
    public function generar()
    {
        if (!empty($this->rutaServer)) {
            if (!file_exists($this->rutaServer)) {
                mkdir($this->rutaServer, 0755, true);
            }
        }

        $nombre_archivo = $this->rutaServer . "/" . $this->fileName;
        $documento_pdf = $this->Output($nombre_archivo, 'F');
        return $nombre_archivo;
    }
}
 
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */