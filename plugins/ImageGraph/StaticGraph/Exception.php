<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package Piwik_ImageGraph_StaticGraph
 */


/**
 *
 * @package Piwik_ImageGraph_StaticGraph
 */
class Piwik_ImageGraph_StaticGraph_Exception extends Piwik_ImageGraph_StaticGraph
{
	const MESSAGE_RIGHT_MARGIN = 5;

	private $exception;

	public function setException($exception)
	{
		$this->exception = $exception;
	}

	protected function getDefaultColors()
	{
		return array();
	}


	public function setWidth($width)
	{
		if(empty($width)) {
			$width = 450;
		}
		parent::setWidth($width);
	}

	public function setHeight($height)
	{
		if(empty($height)) {
			$height = 300;
		}
		parent::setHeight($height);
	}

	public function renderGraph()
	{
		$this->pData = new pData();

		$message = $this->exception->getMessage();
		list($textWidth, $textHeight) = $this->getTextWidthHeight($message);

		if($this->width == null)
		{
			$this->width = $textWidth + self::MESSAGE_RIGHT_MARGIN;
		}

		if($this->height == null)
		{
			$this->height = $textHeight;
		}

		$this->initpImage();

		$this->pImage->drawText(
			0,
			$textHeight,
			$message
		);
	}
}
