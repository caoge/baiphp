<?php
/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>化简PHP（BaiPHP）开发框架</b><br/>
 * <b>新建流域处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class BasinCreateAction extends Action
{
	/**
	 * 子目录
	 */
	private $include = null;

	protected function engage()
	{
		$basin = _LOCAL.$this->target['abasin']._DIR;
		$result = array();
		if (is_dir($basin)) {
			$result['status'] = false;
			return false;
		}
		mkdir($basin);
		if (is_array($this->include)) {
			foreach ($this->include as $item => $flag) {
				if (! $flag) {
					continue;
				}
				mkdir($basin.$item);
			}
		}
		$result['status'] = true;
		$this->target[Flow::ACTION] = $result;
	}
}