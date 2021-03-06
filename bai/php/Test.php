<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>测试工场</h3>
 * <p>
 * 功能与代码覆盖率单元测试。
 * 基于Xdebug。
 * </p>
 * @author 白晓阳
 */
class Test extends Work
{
	/** 测试结果标识：源文件 */
	const SOURCE = 'SOURCE';
	/** 测试结果标识：测试对象 */
	const TESTEE = 'TESTEE';
	/** 测试结果标识：测试结果 */
	const RESULT = 'RESULT';
	/** 测试结果标识：代码覆盖 */
	const LINES  = 'LINES';
	/** 测试结果标识：项目统计 */
	const COUNT  = 'COUNT';

	/** 测试项目标识：测试项目 */
	const ITEM     = 'ITEM';
	/** 测试项目标识：测试参数 */
	const PARAM    = 'PARAM';
	/** 测试项目标识：预期结果 */
	const EXPECTED = 'EXPECTED';
	/** 测试项目标识：测试方式 */
	const MODE     = 'MODE';

	/** 测试方式：构建 */
	const MODE_BUILD  = 'testBuild';
	/** 测试方式：方法 */
	const MODE_METHOD = 'testMethod';
	/** 测试方式：模拟 */
	const MODE_MOCK   = 'testMock';
	/** 测试方式：引用 */
	const MODE_REFER  = 'testRefer';

	/** 测试结果：通过 */
	protected $success = '-';
	/** 测试结果：失败 */
	protected $failure = '/';
	/** 测试结果：忽略 */
	protected $skip    = '.';
	/** 测试结果：出错 */
	protected $error   = '|';

	/** 模拟方法返回结果 */
	protected $mock    = null;

	/**
	 * <h4>执行测试</h4>
	 * <p>
	 * 根据测试对象名和测试文件，执行相应的测试。
	 * </p>
	 * @param string $testee 测试对象名
	 * @param string $source 测试文件名
	 * @return array 测试结果
	 */
	public function entrust($testee = null, $source = null)
	{
		$this->result = null;
		if ($testee == null || ! class_exists($testee)) {
			### 测试对象无效
			$this->notice = Log::logf('testee', $testee, __CLASS__);
			return $this->result;
		}
		if ($source == null) {
			### 默认测试文件与测试对象同名
			$source = $testee;
		}
		$this->load($source);
		$cases = $this->config(__CLASS__, $testee);
		if ($cases == null || ! is_array($cases)) {
			### 测试文件无效
			$this->notice = Log::logf('source', $source, __CLASS__);
			return $this->result;
		}

		### 启用代码统计
		ini_set('xdebug.coverage_enable', 1);
		xdebug_start_code_coverage(XDEBUG_CC_UNUSED + XDEBUG_CC_DEAD_CODE);
		xdebug_start_error_collection();

		### 执行测试
		$this['testee'] = $testee;
		$this['cases']  = $cases;
		$this->result = $this->tests();

		### 关闭代码统计
		xdebug_stop_code_coverage();
		xdebug_stop_error_collection();

		return $this->result;
	}

	/**
	 * <h4>执行测试场景</h4>
	 * <p>
	 * 根据测试文件中的测试场景执行测试。
	 * </p>
	 * @return mixed 测试结果
	 * Test::SOURCE： 源文件（路径）
	 * Test::TESTEE： 测试对象（类名）
	 * Test::RESULT： 测试结果
	 * Test::LINES： 代码统计
	 * Test::COUNT： 项目统计
	 * Test::COUNT[Test::RESULT]：完成的测试
	 * Test::COUNT[Test::LINES]：覆盖的代码
	 */
	protected function tests()
	{
		$testee = $this['testee'];
		$cases  = $this['cases'];
		### 执行测试
		$results = array(self::TESTEE => $testee);
		foreach ($cases as $case) {
			### 测试场景
			$item = $this->pick(self::ITEM, $case);
			if ($case == null || ! is_array($case) || $item == null) {
				### 测试场景无效
				$results[self::RESULT][] = $this->skip;
				Log::logf('case', $item, __CLASS__);
				continue;
			}
			### 测试方式，默认为方法
			$mode   = $this->pick(self::MODE, $case);
			if ($mode == null) {
				$mode = self::MODE_METHOD;
			}
			$this['case'] = $case;
			### 执行测试场景
			Log::logf('test', $item, __CLASS__);
			ob_start();
			$result = $this->$mode();
			$output = ob_get_clean();
			$error = xdebug_get_collected_errors(true);
			if ($error != null) {
			    ### 测试过程中出错
    			$results[self::RESULT][] = $this->error;
			    Log::logf('error', strip_tags(html_entity_decode(implode('', $error))), __CLASS__);
    			continue;
			}
			$results[self::RESULT][] = $result;
			Log::logf('result', $result, __CLASS__);
			if (strlen($output) > 3) {
				Log::logf('output', $output, __CLASS__);
			}
		}

		### 代码覆盖
		$lines = xdebug_get_code_coverage();
		foreach ($lines as $file => $line) {
			if (strcasecmp(basename($file), $testee._EXT) == 0) {
				$results[self::SOURCE] = $file;
				$results[self::LINES] = array_diff($line, array(-2));
				break;
			}
		}

		### 完成的测试
		$count = count(array_intersect($results[self::RESULT], array($this->success)));
		$results[self::COUNT][self::RESULT] = $count;
		### 完成的代码
		$count = count(array_intersect($results[self::LINES], array(1)));
		$results[self::COUNT][self::LINES] = $count;
		return $results;
	}

	/**
	 * <h4>构建场景</h4>
	 * <p>
	 * 为后续测试构建测试对象。
	 * 场景内容：
	 * Test::ITEM： 构建对象
	 * Test::PARAM： 构建参数
	 * Test::EXPECTED： 构建目标，如果省略则使用构建对象
	 * </p>
	 */
	protected function testBuild()
	{
		### 执行数据
		$case     = $this['case'];
		$item     = $this->pick(self::ITEM, $case);
		$param    = $this->pick(self::PARAM, $case);
		$expected = $this->pick(self::EXPECTED, $case);
		if ($expected == null) {
			$expected = $item;
		}
		### 构建对象
		$testee = $this->build($item, $param);
		if ($testee == null) {
			return $this->failure;
		}
		$this->$expected = $testee;
		return $this->testResult($testee, $expected);
	}

	/**
	 * <h4>方法场景</h4>
	 * <p>
	 * 执行测试对象的方法测试。
	 * 场景内容：
	 * Test::ITEM： 测试方法
	 * Test::PARAM： 测试参数
	 * Test::EXPECTED： 预期结果
	 * </p>
	 */
	protected function testMethod()
	{
		### 执行数据
		$testee   = $this['testee'];
		$case     = $this['case'];
		$item     = $this->pick(self::ITEM, $case);
		$param    = $this->pick(self::PARAM, $case);
		$expected = $this->pick(self::EXPECTED, $case);
		### 检查测试对象
		if ($this->$testee == null || ! method_exists($this->$testee, $item)) {
			Log::logf('testee', $item, __CLASS__);
			return $this->error;
		}
		### 执行测试
		if ($param === null) {
			$actual = $this->$testee->$item();
		} else if (! is_array($param)) {
			$actual = $this->$testee->$item($param);
		} else {
			$actual = call_user_func_array(array($this->$testee, $item), $param);
		}
		return $this->testResult($actual, $expected);
	}

	/**
	 * <h4>引用场景</h4>
	 * <p>
	 * 调用引用参数相关方法，用于准备测试环境。
	 * </p>
	 */
	protected function testRefer()
	{
		### 执行数据
		$case     = $this['case'];
		$item     = $this->pick(self::ITEM, $case);
		$param    = $this->pick(self::PARAM, $case);
		$expected = $this->pick(self::EXPECTED, $case);
		### 检查引用对象
		if ($expected == null || $this->$expected == null || ! method_exists($this->$expected, $item)) {
			Log::logf('refer', $item, __CLASS__);
			return $this->error;
		}
		### 执行测试
		if ($param === null) {
			$this->$expected->$item();
		} else if (! is_array($param)) {
			$this->$expected->$item($param);
		} else {
			call_user_func_array(array($this->$expected, $item), $param);
		}
		return $this->skip;
	}

	/**
	 * <h4>比较场景</h4>
	 * <p>
	 * 比较实际结果与预期结果是否相符。
	 * </p>
	 * @param mixed $actual 实际结果
	 * @param mixed $expected 预期结果
	 */
	protected function testResult($actual, $expected)
	{
		if (is_object($actual)) {
			return ($actual instanceof $expected) ? $this->success : $this->failure;
		}
		if ($actual === $expected || (is_array($actual)
				&& is_array($expected) && $actual == $expected)) {
			return $this->success;
		}
		Log::logf(__FUNCTION__, array(var_export($actual, true), var_export($expected, true)), __CLASS__);
		return $this->failure;
	}

	/**
	 * <h4>模拟对象方法</h4>
	 * <p>
	 * 模拟执行对象方法，并返回预置的模拟结果
	 * </p>
	 */
	public function __call($item, $params)
	{
		return $this->pick($item, $this->mock);
	}
}
?>
